#!/usr/bin/env python3
"""Build the PROXON planning catalog from the archived XLSX and PDF sources.

The script is intentionally read-only with respect to the sources. It keeps every
raw row and adds only conservative normalization. It is not a runtime Modbus map.
"""

from __future__ import annotations

import argparse
import hashlib
import json
import re
from collections import Counter, defaultdict
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

import openpyxl
import pdfplumber


CATALOG_VERSION = "0.1.0-plan"
TOKEN_RE = re.compile(r"^(?P<prefix>[34])x(?P<address>\d+)$", re.IGNORECASE)
YELLOW_HOLDING = set([189, 398, 458, 466] + list(range(190, 210)) + list(range(273, 293)) + list(range(313, 317)) + list(range(394, 398)))
YELLOW_INPUT = set([26, 35, 36, 159, 862] + list(range(5115, 5120)))


def clean(value: Any) -> Any:
    if isinstance(value, str):
        value = value.replace("\u00ad", "").replace("\r", "").strip()
        return value
    return value


def sha256(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as handle:
        for block in iter(lambda: handle.read(1024 * 1024), b""):
            digest.update(block)
    return digest.hexdigest().upper()


def token_parts(token: Any) -> tuple[str | None, int | None]:
    text = str(token or "").strip()
    match = TOKEN_RE.match(text)
    if not match:
        return None, None
    return match.group("prefix"), int(match.group("address"))


def scale_model(token: Any, offset: Any = None, device: str | None = None, address: int | None = None) -> dict[str, Any]:
    raw = str(token or "").strip()
    result: dict[str, Any] = {"source_token": raw or None, "multiplier": None, "offset": 0.0, "status": "unparsed"}
    match = re.search(r"([*/])\s*(-?\d+(?:[.,]\d+)?)", raw)
    if match:
        number = float(match.group(2).replace(",", "."))
        if number != 0:
            # Both source conventions describe engineering = raw / N.
            result.update(multiplier=1.0 / number, status="normalized")
    if offset not in (None, ""):
        try:
            result["offset"] = float(str(offset).replace(",", "."))
        except ValueError:
            result["offset_source"] = str(offset)
    if device == "t300" and address in {811, 812, 813, 814}:
        result.update(multiplier=0.1, offset=-100.0, status="source-specific")
    if device == "t300" and address == 882:
        result.update(multiplier=0.01, status="source-specific")
    return result


def source_position(path: Path, sheet: str | None = None, row: int | None = None, page: int | None = None) -> dict[str, Any]:
    result: dict[str, Any] = {"file": path.name}
    if sheet is not None:
        result["sheet"] = sheet
    if row is not None:
        result["row"] = row
    if page is not None:
        result["page"] = page
    return result


def parse_xlsx(path: Path, device: str) -> list[dict[str, Any]]:
    workbook = openpyxl.load_workbook(path, read_only=True, data_only=True)
    records: list[dict[str, Any]] = []
    for sheet_name, first_row in (("Holding Register", 5 if device == "fwt" else 7), ("Input Register", 5 if device == "fwt" else 8)):
        sheet = workbook[sheet_name]
        register_space = "holding" if sheet_name.startswith("Holding") else "input"
        expected_prefix = "4" if register_space == "holding" else "3"
        for row_number, values in enumerate(sheet.iter_rows(min_row=first_row, values_only=True), start=first_row):
            row = [clean(value) for value in values]
            prefix, address = token_parts(row[0] if row else None)
            if address is None:
                continue
            if register_space == "holding":
                names = ["source_address_token", "parameter", "group", "access_mode_0", "access_mode_1", "access_mode_2", "data_type", "raw_min", "raw_max", "engineering_min", "engineering_max", "scale_token", "unit", "comment"]
                offset = None
            elif device == "t300":
                names = ["source_address_token", "parameter", "ident", "access", "data_type", "offset", "scale_token", "unit", "comment"]
                offset = row[5] if len(row) > 5 else None
            else:
                names = ["source_address_token", "parameter", "access", "data_type", "scale_token", "unit", "comment", "extra"]
                offset = None
            raw = {name: (row[index] if index < len(row) else None) for index, name in enumerate(names)}
            records.append({
                "record_id": f"xlsx:{device}:{register_space}:{address}:{row_number}",
                "device": device,
                "register_space": register_space,
                "address": address,
                "function_read": 3 if register_space == "holding" else 4,
                "source_address_token": raw.get("source_address_token"),
                "source_prefix": prefix,
                "source_prefix_mismatch": prefix != expected_prefix,
                "parameter": raw.get("parameter"),
                "access": raw.get("access") or raw.get("access_mode_2") or raw.get("access_mode_1") or raw.get("access_mode_0"),
                "data_type": raw.get("data_type"),
                "unit": raw.get("unit"),
                "scale": scale_model(raw.get("scale_token"), offset, device, address),
                "confidence": "internal_unverified",
                "provenance": source_position(path, sheet_name, row_number),
                "raw": raw,
            })
    workbook.close()
    return records


def parse_pdf(path: Path) -> list[dict[str, Any]]:
    records: list[dict[str, Any]] = []
    with pdfplumber.open(path) as document:
        for page_number in range(2, 17):
            table = document.pages[page_number - 1].extract_tables()[0]
            register_space = "holding" if page_number <= 12 else "input"
            for table_row, values in enumerate(table[1:], start=2):
                row = [clean(value) for value in values]
                prefix, address = token_parts(row[0] if row else None)
                if address is None:
                    continue
                device = "t300" if (register_space == "holding" and address >= 2000) or (register_space == "input" and 800 <= address < 1000) else "fwt"
                if register_space == "holding":
                    names = ["source_address_token", "parameter", "group", "access_mode_0", "access_mode_1", "access_mode_2", "data_type", "raw_min", "raw_max", "engineering_min", "engineering_max", "scale_token", "unit", "comment", "recommendation_glyph", "info"]
                    offset = None
                    access = row[5] or row[4] or row[3]
                    scale_token = row[11]
                    unit = row[12]
                else:
                    names = ["source_address_token", "parameter", "access", "data_type", "scale_token", "unit", "comment", "recommendation_glyph", "info"]
                    access = row[2]
                    scale_token = row[4]
                    unit = row[5]
                    offset = None
                    # Page 16 contains the T300 offset as a shifted cell.
                    if device == "t300" and address in {811, 812, 813, 814}:
                        offset, scale_token, unit = row[4], row[5], row[6]
                raw = {name: (row[index] if index < len(row) else None) for index, name in enumerate(names)}
                yellow = address in (YELLOW_HOLDING if register_space == "holding" else YELLOW_INPUT)
                records.append({
                    "record_id": f"pdf:{device}:{register_space}:{address}:p{page_number}r{table_row}",
                    "device": device,
                    "register_space": register_space,
                    "address": address,
                    "function_read": 3 if register_space == "holding" else 4,
                    "source_address_token": row[0],
                    "source_prefix": prefix,
                    "source_prefix_mismatch": False,
                    "parameter": row[1],
                    "access": access,
                    "data_type": row[6] if register_space == "holding" else row[3],
                    "unit": unit,
                    "scale": scale_model(scale_token, offset, device, address),
                    "confidence": "possible" if yellow else "recommended",
                    "recommendation": "yellow" if yellow else "green",
                    "provenance": source_position(path, page=page_number),
                    "raw": raw,
                })
    return records


def comparable(value: Any) -> str:
    return re.sub(r"\s+", " ", str(value or "").strip()).casefold()


def merge_device(device: str, xlsx_rows: list[dict[str, Any]], pdf_rows: list[dict[str, Any]]) -> list[dict[str, Any]]:
    grouped: dict[tuple[str, int], list[dict[str, Any]]] = defaultdict(list)
    for record in xlsx_rows + pdf_rows:
        if record["device"] == device:
            grouped[(record["register_space"], record["address"])].append(record)
    result = []
    for (register_space, address), sources in sorted(grouped.items(), key=lambda item: (item[0][0], item[0][1])):
        preferred = next((record for record in sources if record["record_id"].startswith("pdf:")), sources[0])
        conflicts = []
        for field in ("parameter", "access", "data_type", "unit"):
            values = {comparable(record.get(field)) for record in sources if comparable(record.get(field))}
            if len(values) > 1:
                conflicts.append({"field": field, "values": sorted(values)})
        scale_values = {json.dumps(record.get("scale"), sort_keys=True, ensure_ascii=False) for record in sources}
        if len(scale_values) > 1:
            conflicts.append({"field": "scale", "values": [json.loads(value) for value in sorted(scale_values)]})
        notes = []
        if device == "t300" and register_space == "input" and 847 <= address <= 860:
            notes.append("u32 pair, low word followed by high word; source unit must be verified")
        if device == "fwt" and register_space == "holding" and address == 438:
            notes.append("source range conflict: raw maximum 55555 versus engineering maximum 2")
        result.append({
            "key": f"{device}.{register_space}.{address}",
            "device": device,
            "register_space": register_space,
            "address": address,
            "function_read": preferred["function_read"],
            "parameter": preferred.get("parameter"),
            "access": preferred.get("access"),
            "data_type": preferred.get("data_type"),
            "unit": preferred.get("unit"),
            "scale": preferred.get("scale"),
            "confidence": preferred.get("confidence"),
            "sources": [record["record_id"] for record in sources],
            "conflicts": conflicts,
            "notes": notes,
        })
    return result


def write_json(path: Path, value: Any) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(json.dumps(value, indent=2, ensure_ascii=False) + "\n", encoding="utf-8")


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--source-dir", required=True, type=Path)
    parser.add_argument("--output-dir", required=True, type=Path)
    args = parser.parse_args()
    source_dir = args.source_dir.resolve()
    output_dir = args.output_dir.resolve()
    files = {
        "fwt_xlsx": source_dir / "Modbus Liste FWT2.0 ver2.xlsx",
        "t300_xlsx": source_dir / "Modbus Liste FWT2.0 ver2 T300.xlsx",
        "glt_pdf": source_dir / "GLT_Adressen_2.0_3.0_Modbus_Holging_Input_1_1.pdf",
    }
    missing = [str(path) for path in files.values() if not path.is_file()]
    if missing:
        raise SystemExit("Missing sources: " + ", ".join(missing))

    xlsx_rows = parse_xlsx(files["fwt_xlsx"], "fwt") + parse_xlsx(files["t300_xlsx"], "t300")
    pdf_rows = parse_pdf(files["glt_pdf"])
    fwt = merge_device("fwt", xlsx_rows, pdf_rows)
    t300 = merge_device("t300", xlsx_rows, pdf_rows)

    common = {
        "catalog_version": CATALOG_VERSION,
        "addressing": "zero-based numeric suffix; never add 1, 30001, or 40001",
        "spaces": {"input": {"read_function": 4}, "holding": {"read_function": 3, "manufacturer_write_function": 6, "write_status": "hardware_validation_required"}},
        "source_priority": ["current manufacturer PDF", "XLSX register lists", "brochure/app/community for context only"],
        "default_serial": {"baud": 19200, "data_bits": 8, "parity": "even", "stop_bits": 1, "default_slave": 41},
        "xlsx_prefix_warning": "XLSX 3x/4x tokens are inverted relative to the register sheet; sheet determines the space.",
    }
    schema = {
        "version": 1,
        "description": "Planning-time normalized PROXON register catalog",
        "required": ["device", "register_space", "address", "function_read", "parameter", "confidence", "sources"],
        "confidence_values": ["recommended", "possible", "internal_unverified"],
        "scale_formula": "engineering = raw * multiplier + offset",
        "provenance": "Source rows remain verbatim in source-registers-*.json and are linked by record_id.",
    }
    write_json(output_dir / "source-registers-xlsx.json", xlsx_rows)
    write_json(output_dir / "source-registers-pdf.json", pdf_rows)
    write_json(output_dir / "fwt.json", fwt)
    write_json(output_dir / "t300.json", t300)
    write_json(output_dir / "common.json", common)
    write_json(output_dir / "schema.json", schema)

    confidence = Counter(record["confidence"] for record in pdf_rows)
    metadata = {
        "catalog_version": CATALOG_VERSION,
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "generator": ".tools/generate_register_catalog.py",
        "counts": {
            "xlsx_records": len(xlsx_rows),
            "pdf_records": len(pdf_rows),
            "pdf_recommended": confidence["recommended"],
            "pdf_possible": confidence["possible"],
            "fwt_unique_registers": len(fwt),
            "t300_unique_registers": len(t300),
        },
        "source_sha256": {key: sha256(path) for key, path in files.items()},
    }
    write_json(output_dir / "catalog-version.json", metadata)

    if len(xlsx_rows) != 822 or len(pdf_rows) != 330 or confidence["recommended"] != 268 or confidence["possible"] != 62:
        raise SystemExit(f"Validation failed: {metadata['counts']}")
    print(json.dumps(metadata, indent=2, ensure_ascii=False))


if __name__ == "__main__":
    main()

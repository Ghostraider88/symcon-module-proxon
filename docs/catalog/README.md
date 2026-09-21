# Registerkatalog

Dieser Katalog ist ein Planungs- und Analyseartefakt, kein unmittelbar ausführbares Laufzeitmapping.

## Dateien

- `source-registers-xlsx.json`: 822 unveränderte Quellzeilen plus konservative Metadaten.
- `source-registers-pdf.json`: 330 PDF-Zeilen plus Empfehlungsklasse.
- `fwt.json`, `t300.json`: nach Registerraum/Adresse gruppierte Sicht.
- `common.json`: gemeinsame Kommunikationsregeln.
- `schema.json`: Schemahinweise.
- `catalog-version.json`: Zählungen und Quellhashes.

## Wichtige Einschränkung

Die obersten Felder eines gruppierten Eintrags sind eine Anzeigeprojektion der aktuelleren PDF, sofern vorhanden. Sie sind **kein** abschließend aufgelöster Gerätewert. Sobald `conflicts` nicht leer ist oder Serie/Firmware unbekannt sind, muss die Laufzeit beide verlinkten `sources` prüfen und bleibt read-only. Ein späteres Laufzeitschema benötigt zusätzlich `profile_scope`, `verification_state` und einen nur durch Hardwareverifikation gesetzten `resolved_value`.

Das gilt insbesondere für FWT Holding 16 und 187. Alle Rohfelder sind in den beiden `source-registers-*.json` vollständig erhalten.

## Reproduzierbarkeit

`.tools/generate_register_catalog.py --source-dir <docs/source> --output-dir <docs/catalog>` erzeugt die JSON-Dateien erneut. Der Lauf bricht ab, wenn die erwarteten 822 Excel-/330 PDF-Zeilen oder 268 grüne/62 gelbe Empfehlungen nicht erreicht werden.

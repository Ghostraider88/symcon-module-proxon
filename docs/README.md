# PROXON-Modul: technische Projektbasis

Stand: 19.09.2026. Diese Dokumentation ist das Lasten- und Pflichtenheft der Planungsphase und noch keine fertige Modulimplementierung.

## Navigation

| Dokument | Inhalt |
|---|---|
| [requirements.md](requirements.md) | Funktionale und nichtfunktionale Anforderungen |
| [architecture.md](architecture.md) | Zielarchitektur, Transport, Instanzen, Datenfluss und Lifecycle |
| [decision-baseline-2026-09-19.md](decision-baseline-2026-09-19.md) | Verbindlicher Produktumfang und Transportentscheid |
| [modbus.md](modbus.md) | Quellen, Adressmodell, Registerkatalog und Kommunikationsregeln |
| [features.md](features.md) | Priorisierte Komfort- und Produktfunktionen |
| [visualization.md](visualization.md) | Aufteilung in native Symcon-Kacheln und Mockups |
| [VISU_STYLE.md](VISU_STYLE.md) | Projektweiter verbindlicher Kachel- und Zustandsstil |
| [setup.md](setup.md) | Greenfield-, Bestands- und Migrationsablauf |
| [testing.md](testing.md) | Teststrategie und Abnahmekriterien |
| [research.md](research.md) | Marktanalyse, Hersteller-, Symcon- und Community-Quellen |
| [open-questions.md](open-questions.md) | Vor Coding-Beginn zu klärende Punkte |
| [decisions.md](decisions.md) | Architekturentscheidungen und verworfene Alternativen |
| [traceability.md](traceability.md) | Rückverfolgbarkeit Anforderungen zu Design und Tests |
| [implementation-plan.md](implementation-plan.md) | Phasenweise Umsetzungsreihenfolge |
| [architecture-review.md](architecture-review.md) | Unabhängiger Review und Go/No-Go-Gates |
| [decision-update-2026-09-18.md](decision-update-2026-09-18.md) | Betreiberentscheidungen G1–G7 und G2-Testanleitung |
| [g2-field-evidence-decision.md](g2-field-evidence-decision.md) | FC06-Langzeitevidenz und Testentscheidung |
| [symcon-basis-approval.md](symcon-basis-approval.md) | Freigabe zur Nutzung der Symcon-Modulbasis |
| [scope-correction-greenfield.md](scope-correction-greenfield.md) | Greenfield-first und Abgrenzung zur Betreiberanlage |

## Verbindlicher Produktstand

- Basis: freigegebenes Repository [symcon/Proxon](https://github.com/symcon/Proxon).
- Transport: ausschließlich Symcon-eigene Modbus-Infrastruktur; kein eigener Modbus-Stack.
- Greenfield: Configurator darf Symcon-Modbus-Gateway, Geräte/Adressen und logische PROXON-Instanzen anlegen.
- Profile: FWT 2.0 und FWT 3.0; T300 ausschließlich über FWT.
- Die direkte T300-Anbindung ist kein unterstütztes Produktprofil.
- Die konkrete Betreiberanlage bleibt Referenz und optionaler Bestandsadapter.
- Der erste Entwicklungszyklus ist read-only; FWT-Schreiben wird später über FC06 ergänzt.

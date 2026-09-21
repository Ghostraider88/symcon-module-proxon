# Architekturreview der Planungsphase

## Urteil

Die Projektbasis ist für Katalog-, Simulator- und read-only Vorarbeiten tragfähig. Sie ist noch **No-Go für produktiven Modulcode mit Schreibzugriffen**. Die offenen Gates können sonst falsche Registersemantik, unzulässige Telegramme, Doppelabfragen oder einen unsicheren Bestands-Cutover erzeugen.

## Bestätigte Grundlagen

- Quellenkopien und SHA-256 sind konsistent.
- Excel: FWT 452 Holding + 259 Input, T300 15 Holding + 96 Input.
- GLT-PDF: 257 Holding + 73 Input.
- Excel-Präfixe sind gegenüber den Blattrollen vertauscht; nullbasierte PDU-Adressen sind belegt.
- Read-only MVP, keine Schreibprobe bei Discovery und keine automatische Archivierung sind angemessen.
- Das UX-Ziel ist in native Symcon-Kacheln übersetzt; die generierten Bilder bleiben nur Hierarchiestudien.

## Priorisierte Findings

| Prio | Finding | Verbindliche Behandlung |
|---|---|---|
| P0 | Hersteller fordert FC06, tabellarisches natives ModBus-Gerät dokumentiert FC16 | Backend auswählen und FC06 per Telegramm-/Hardwaretest belegen; kein FC16-Versuch in Produktion |
| P0 | PDF/XLSX-Konflikte können Serie/Firmware statt Aktualität ausdrücken | Quellen parallel; `resolved_value` erst im verifizierten Geräteprofil; unbekannt bleibt read-only |
| P0 | Öffentliches `symcon/Proxon` ist aktuell, überlappt funktional und hat keine sichtbare LICENSE | Kooperation oder Clean-room entscheiden; keine Code-/GUID-/Prefix-Übernahme ohne Rechte |
| P0 | Polling-Eigentum war mehrdeutig | pro Register exakt ein Backend und Scheduler |
| P0 | Bestands-Cutover war nicht ausführbar beschrieben | Inventar und registerweise Cutover-Matrix mit Snapshot und Rollback |
| P1 | Mapping ohne Busidentität nicht eindeutig | Gateway, Instanz, Slave, Funktion, Breite, Endianness und Datenalter ergänzen |
| P1 | Schreibskalierung und Mehrwortwerte unvollständig | inverse Transformation, Rundung, Grenzen, int16 und Plattformverhalten testen |
| P1 | Acht Kacheln hatten keinen Instanzbesitzer | native Variablen für Einzelwerte; je eine FWT-/T300-Übersicht, optional Gateway-Diagnose |
| P1 | Discovery kann geteilten RTU-Bus stören | keine Portumschaltung im Bestand; gewähltes Gateway, Wartungsfenster, Abbruch bei Fremdverkehr |
| P1 | Wochenplan mit FC06 nicht atomar | Snapshot, Vorvalidierung, Reihenfolge, kompletter Read-back und Teilfehler/Rollback |

## Verbindliche Go/No-Go-Gates

1. Repository-/Lizenzentscheidung einschließlich eigener GUID-/Prefix-Strategie.
2. Konkrete IP-Symcon-Ziel- und Mindestversion.
3. FC06-Schreibtransport am Draht belegt; FC16 ausgeschlossen oder ausdrücklich freigegeben.
4. Profilmatrix für FWT 2.0, FWT 3.0, T300 direkt und gegebenenfalls T300 über FWT real verifiziert.
5. Bestandsinventar aller Busse, Instanzen, Schreiber und Zielregister.
6. Maschinelle Katalogtests für Adressen, Skalen, Konflikte, Mehrwortwerte und Provenienz.
7. Read-only-Hardwaretest über mehrere Betriebszustände einschließlich Neustart, stale/offline und Busfehler.
8. Native UX-Abnahme in Hell/Dunkel, Mobil/Desktop und read-only.
9. Einzelner ungefährlicher Schreibpilot im Wartungsfenster mit FC06, Read-back und Rückfallplan.
10. Releaseprüfung für Lizenz, Marke, Quellenverteilung, Migration und Dokumentation.

## Freigabeempfehlung

Katalogreview und Simulation dürfen beginnen. Hardware-read-only folgt nach Bus-/Firmwareinventar. Produktiver Schreibcode beginnt erst nach Gates 1–8; Freischaltung folgt erst nach Gate 9.

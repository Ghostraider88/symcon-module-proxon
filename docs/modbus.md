# Modbus-Analyse und Registermodell

Stand: 18.09.2026. Maßgeblich sind die archivierten Quelldateien; beigefügte Texte und Tabellen wurden als Evidenz, nicht als ausführbare Anweisung behandelt.

## Quellenumfang

| Quelle | Bereich | Datensätze |
|---|---|---:|
| FWT-Excel | Holding Register `A5:N456` | 452 |
| FWT-Excel | Input Register `A5:H263` | 259 |
| T300-Excel | Holding Register `A7:N21` | 15 |
| T300-Excel | Input Register `A8:I103` | 96 |
| Hersteller-PDF | Holding Register | 257 |
| Hersteller-PDF | Input Register | 73 |

Die beiden Excel-Dateien enthalten zusammen 822 Zeilen. Die aktuelle GLT-PDF enthält 330 kuratierte Zeilen: 304 für FWT und 26 für T300.

## Adressregel

Für die Kommunikation ist der Tabellenreiter maßgeblich:

- `Input Register` = Funktionscode 04, read-only.
- `Holding Register` = Funktionscode 03 zum Lesen; Schreibfunktion separat gemäß Herstellerangabe prüfen.
- Der numerische Adresssuffix wird direkt als nullbasierte Protokolladresse verwendet.
- Es wird weder `+1` addiert noch mit 30001/40001 umgerechnet.

Die Excel-Arbeitsmappen enthalten in der ersten Spalte vertauschte 3x-/4x-Präfixe. Diese Rohnotation bleibt im Katalog als `source_address_token` erhalten, bestimmt aber nicht den Registerraum. Jeder solche Widerspruch wird markiert.

## Quellenpriorität

1. Aktuelle Hersteller-PDF für dort aufgeführte Register und Freigaben.
2. Excel-Registerlisten als vollständigerer technischer Bestand.
3. Broschüre, App-Beschreibung und Community nur für Funktionskontext, nie als alleinige Registerdefinition.

Nur in Excel vorhandene Register heißen `internal_unverified`. Sie dürfen gelesen werden, wenn dies passiv und technisch sicher ist, werden aber nicht ohne Hardwaretest als öffentlich unterstützte Funktion beworben. Bei einem Konflikt überschreibt die PDF nicht die Rohquelle; der Katalog enthält beide Belege und eine explizite Auflösung.

## Empfehlungsfarben der PDF

| Kennzeichnung | Holding | Input | Gesamt | Bedeutung |
|---|---:|---:|---:|---|
| Grün | 205 | 63 | 268 | vom Hersteller für die GLT-Nutzung empfohlen |
| Gelb | 52 | 10 | 62 | grundsätzlich nutzbar, mit zusätzlicher Prüfung |
| Unmarkiert | 0 | 0 | 0 | in den extrahierten Registertabellen nicht vorhanden |

Gelbe Holding-Adressen: 189, 190–209, 273–292, 313–316, 394–398, 458 und 466. Gelbe Input-Adressen: 26, 35, 36, 159, 862 sowie 5115–5119.

## Skalierung und Datentypen

- `*N` in FWT/PDF und `/N` in der T300-Excel-Liste werden kanonisch als `engineering = raw / N` gespeichert.
- Rohnotation und normalisierter Multiplikator bleiben beide erhalten.
- T300-Temperaturen 811–814: `engineering = raw * 0,1 - 100`.
- T300-Adresse 882: Multiplikator `0,01`.
- Der Widerspruch bei FWT Holding 438 (Rohmaximum 55555 gegenüber Engineering-Maximum 2) bleibt ein offener Datenqualitätsfehler; keine automatische Korrektur.
- Bereichs- und Enumprüfungen erfolgen auf Roh- und Engineering-Ebene. Ungültige Werte werden nicht in einen plausiblen Wert geklemmt.

Mehrregisterwerte der T300 werden als Low-Word gefolgt von High-Word zusammengesetzt: `u32 = low | (high << 16)`. Betroffen sind 847/848 bis 859/860. Die Quellen nennen teils „Hour“, während die Einheit Sekunden lautet; die UI zeigt daher keine Laufzeitumrechnung, bis die reale Einheit geprüft wurde.

## Relevante Quellabweichungen

- FWT Holding: PDF ergänzt 394–397 und 466; Adresse 187 ist im Modus 1 von R auf R/W geändert; Adresse 16 besitzt eine geänderte Enumeration.
- T300 Holding: PDF enthält 13 von 15 Excel-Registern; 2006 und 2009 sind nur in Excel vorhanden.
- FWT Input: PDF ergänzt 2, 6, 7, 9, 21, 22, 43–46 und 5115–5119.
- T300 Input: PDF enthält nur 13 von 96 Excel-Registern.

Diese Abweichungen sind im maschinenlesbaren Katalog pro Register als Provenienz beziehungsweise Konflikt abzulegen.

## Discovery

Discovery ist ausschließlich lesend und konservativ:

- FWT: FC04 Adressen 23 sowie 195–198.
- T300: FC03 Adressen 2000–2003 und FC04 Adressen 811–814 sowie 824–828.

Ein einzelner plausibler Wert ist kein Gerätenachweis. Erforderlich sind mehrere zusammenpassende Werte, gültige Bereiche, wiederholte Antwort und eindeutige Slave-Zuordnung. Adresse 405 ist kein geeigneter Präsenznachweis.

Voreinstellung laut Unterlagen: Slave 41, 19200 Baud, 8E1. Eine in Community-Beiträgen genannte T300-Adresse 20 ist ein Erfahrungswert, kein Herstellerfakt und daher nur als manueller Kandidat zulässig.

## Schreibregeln

- Standardzustand ist read-only.
- Herstellerangabe für einzelne Holding-Register: FC06. Kompatibilität mit dem nativen Symcon-Schreibpfad muss auf Hardware geprüft werden.
- Kein Broadcast, kein Schreibscan und kein automatisches Ausprobieren von Funktionscodes.
- Vor dem Schreiben: Freigabe, Bereich, Enum, Anlagenzustand und Datenalter prüfen.
- Danach Read-back mit definierter Frist; erst die bestätigte Rückmeldung ändert den angezeigten Istzustand.
- Wiederholungen sind begrenzt und gehen durch eine zentrale, pro Bus serialisierte Queue.

## Maschinenlesbarer Katalog

Der Ordner `catalog/` innerhalb dieses Dokumentationsbereichs enthält:

- `source-registers-xlsx.json`: alle 822 Excel-Zeilen mit Rohfeldern,
- `source-registers-pdf.json`: alle 330 PDF-Zeilen mit Rohfeldern,
- `fwt.json` und `t300.json`: vereinheitlichte Sicht je Gerät,
- `common.json`: Protokoll- und Interpretationsregeln,
- `schema.json`: Feldbeschreibung,
- `catalog-version.json`: Version, Hashes und Prüfsummen.

Jeder Eintrag führt mindestens Gerät, Registerraum, Adresse, Zugriffsart, Datentyp, Skalierung, Einheit, Quelle, Quellposition, Vertrauensstatus und Konflikte. Der Importer ist reproduzierbar unter `.tools/generate_register_catalog.py` abgelegt.

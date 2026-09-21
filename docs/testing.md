# Teststrategie und Abnahmekriterien

## Teststufen

| ID | Bereich | Mindestprüfungen |
|---|---|---|
| TEST-001 | Katalogimport | 822 XLSX-Zeilen, 330 PDF-Zeilen, keine verlorenen Adressen, Herkunft/Zeile/Seite vorhanden |
| TEST-002 | Adressen | Blattrolle statt falschem XLSX-Präfix, direkter nullbasierter Suffix, keine stillen +1/-1-Regeln |
| TEST-003 | Skalierung | `*N` als Division gemäß Roh-/Istgrenzen, Offset, negative T300-Werte, Adresse 438 als Konflikt |
| TEST-004 | Datentyp | uint16/int16, Masken via `& 0xFFFF`, Grenzwerte und ungültige Rohwerte |
| TEST-005 | Mehrwort | sieben T300-Low/High-Paare, Overflow, Byte-/Wortreihenfolge und Einheitenkonflikt Sekunden/Stunden |
| TEST-006 | Mapping | eindeutig, mehrere Kandidaten, falscher Slave/Funktion/Typ/Skalierung, gelöscht und stale |
| TEST-007 | Discovery | keine Schreibprobe, begrenzte Adressen, Teilantworten, falsches Profil und wiederholte Plausibilität |
| TEST-008 | Lifecycle | wiederholtes Create/ApplyChanges, Kernelstart, Reconnect, Update, Migrate, Profilwechsel, Destroy |
| TEST-009 | Schreiben | Observe/Manual/Automation, FC06 vs FC16, Bereich, Sperre, Rate-Limit, Read-back und Timeout |
| TEST-010 | Arbitration | Prioritäten, Ablauf, Neustart während Override, Konflikt und Fremdschreiber |
| TEST-011 | Busfehler | Port getrennt, Parität/Baud falsch, Slave fehlt, CRC/Timeout, zweiter Master, Backoff |
| TEST-012 | Fähigkeiten | optionale Register fehlen, Feature verschwindet/degradiert ohne Gesamtausfall |
| TEST-013 | Visualisierung | native Symcon-Kachel, Hell/Dunkel, klein/mittel/breit, mobil, pending/stale/offline/error |
| TEST-014 | Migration | Parallel-Schreiber verhindert, kontrollierter Cutover, Rollback und stabile Idents/Archive |
| TEST-015 | Sicherheit | keine Secrets/PII in Logs/Export, Expertenaktionen bestätigt, kein automatisches Archivieren |

## Katalogkontrollen

- FWT-XLSX: `Holding Register!A5:N456` = 452 und `Input Register!A5:H263` = 259 Register.
- T300-XLSX: `Holding Register!A7:N21` = 15 und `Input Register!A8:I103` = 96 Register.
- Hersteller-PDF: 257 Holding und 73 Input.
- Keine doppelte Adresse innerhalb desselben Geräts und Registerraums ohne dokumentierten Variantenkontext.
- Alle PDF-/XLSX-Deltas erzeugen entweder eine aufgelöste Übernahme oder einen Konflikteintrag.

## Hardware-in-the-loop

Der erste reale Test ist vollständig lesend. Er protokolliert Profil, Slave, Funktion, Adresse, Rohwert, normalisierten Wert, Zeit und Plausibilität. Erst nach Freigabe folgt genau ein ungefährlicher Schreibtest im Wartungsfenster. FC06 und FC16 werden nicht blind ausprobiert; die Testmatrix und Rückfallaktion müssen vorab feststehen.

Zu prüfen sind mindestens FWT 2.0, FWT 3.0, T300 in FWT-Topologie und direkte T300-Anbindung, sofern Hardware vorhanden ist. P 3.0 darf ohne eigenes Profil nicht als unterstützt gelten.

## Visualisierungsabnahme

- Keine erfundenen oder simulierten Werte in einer produktiven Kachel.
- Ein Mockupwert ist klar als Beispiel markiert und wird nicht als Registerfähigkeit dokumentiert.
- T300 erscheint nicht als thermischer Ausgang der FWT-Wärmepumpe; beide Systeme werden topologisch korrekt getrennt dargestellt.
- Per-room CO2/Feuchte, WRG-Wirkungsgrad, m³/h und Energie erscheinen nur mit tatsächlich gemapptem, belegtem Datenpunkt.
- Jede Aktion zeigt Sperrgrund, Pending-Zustand und bestätigtes Ergebnis.

## Release-Gates

1. JSON/PHP-Validierung und Test-Suite grün.
2. Katalogzählungen und Konfliktliste geprüft.
3. Read-only-Hardwaretest bestanden.
4. FC06/FC16-Entscheidung dokumentiert.
5. Keine parallelen Schreiber im Zielsystem.
6. Mobile und Desktop-Kacheln geprüft.
7. Lizenz-, Marken- und Repository-Gap geklärt.

Außerhalb einer echten Symcon-Laufzeit dürfen Tests nicht als Integrationstest bezeichnet werden.

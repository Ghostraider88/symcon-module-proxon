# Einrichtung und Migration

## Vorbedingungen

- IP-Symcon-Zielversion, Geräte/Firmware, physische Topologie, Busparameter und Slave-Adressen sind erfasst.
- Alle bestehenden Gateways, I/O-, Splitter-, Modbus-Instanzen, Skripte, Ereignisse, Aktionen, Archive und Verbraucher sind inventarisiert.
- Für jedes Register ist genau ein Transport-/Polling-Eigentümer festgelegt.
- Der Profil-Smoke-Test war read-only erfolgreich.
- Schreibbackend und FC06 sind erst erforderlich, wenn eine Schreibfunktion freigegeben werden soll.

## Variante A: neue Infrastruktur

1. Nutzer wählt seriell oder RTU-over-TCP und einen expliziten I/O-Pfad.
2. 19200, 8E1 und Slave 41 erscheinen nur als Hersteller-Vorschlag.
3. Konfliktprüfung für Port, Gateway, Slave, vorhandene Abfragen und zweiten Busmaster.
4. Begrenzte Read-only-Erkennung mit mehreren Signaturregistern; keine automatische Änderung von Portparametern auf einem Bestandsbus.
5. Transportbackend festlegen: native Adresse/Gerät oder direkter Parent, niemals parallel für dasselbe Register.
6. Vorschau aller anzulegenden und zu referenzierenden Objekte samt Eigentümer.
7. Nutzerbestätigung und idempotente Anlage anhand Modul-ID, Verbindung, Slave und Konfigurations-Fingerprint.
8. Start ausschließlich read-only.

Pollingklassen schnell, normal, langsam und Service werden erst nach einem gemessenen Busbudget eingerichtet. Ob das tabellarische native „ModBus Gerät“ sinnvoll ist, hängt zusätzlich vom FC06-Gate ab.

## Variante B: bestehende Infrastruktur

1. Nutzer wählt Gateway/Verbindung und Quellinstanzen ausdrücklich aus.
2. Kandidaten werden nach Gateway, Instanztyp, Slave, Funktion, Adresse, Breite, Typ, Vorzeichen, Byte-/Wortreihenfolge, Skalierung und Datenalter verglichen.
3. Eindeutige Treffer werden vorgeschlagen, nie allein anhand des Namens übernommen.
4. Mehrdeutige oder unvollständige Zuordnungen benötigen manuelle Bestätigung.
5. Fremde Instanzen werden nicht automatisch verändert oder gelöscht.
6. Gelöschte/geänderte Quellen führen zu einem klaren Mappingstatus, nicht zu einem stillen Ersatz.

## Read-only-Smoke-Test

- FWT: FC04 Adresse 23 sowie 195–198.
- T300: FC03 2000–2003 sowie FC04 811–814 und 824–828.
- Mehrere zusammenpassende Werte und wiederholte Antworten sind erforderlich.
- Exception/Timeout bedeutet `unsupported` oder `offline`, niemals Messwert null.
- Adresse 405 ist kein ausreichender T300-Präsenznachweis.
- Keine Schreibprobe während Discovery.

Auf einem geteilten RTU-Bus erfolgt Discovery nur am ausgewählten Gateway in einem Wartungsfenster. Laufende Fremdkommunikation führt zum Abbruch; die Portkonfiguration wird nicht automatisch umgestellt.

## Bestandsmigration und Cutover

1. Vollständigen Konfigurationssnapshot erzeugen.
2. Je schreibbarem Zielregister eine Cutover-Zeile anlegen: Alt-Schreiber, Neu-Schreiber, Abhängigkeiten, Abschaltpunkt, Test, Rückfallaktion.
3. Modul read-only installieren und bestehende Quellen zuordnen.
4. Mehrere Tage Roh-/Normalwerte, Zeitstempel, Qualität und Betriebszustände vergleichen.
5. Alt-Schreiber für genau ein ungefährliches Pilotregister im Wartungsfenster deaktivieren.
6. FC06-Telegramm, PDU-Adresse, Rohwert und FC03-Read-back protokollieren.
7. Bei Erfolg den neuen manuellen Pfad beobachten; bei Fehler sofort gemäß Cutover-Zeile zurückrollen.
8. Weitere Register einzeln umschalten. Alt und neu sind für dasselbe Register nie gleichzeitig aktiv.
9. Erst nach vollständiger manueller Abnahme Komfortautomation registerweise freigeben.

Ein abweichender Read-back ist ein Konfliktverdacht, aber kein sicherer Beweis für einen Fremdschreiber. Wochenpläne werden vorab vollständig validiert, geordnet als Einzelregister geschrieben und komplett zurückgelesen; ein FC06-Ablauf ist nicht atomar.

## Deinstallation

Das Modul führt eine persistente Besitzliste. Eine Bereinigung zeigt Vorschau und Abhängigkeiten und entfernt nur nach Bestätigung selbst erzeugte, nicht mehr verwendete Objekte. Fremde I/O-/Modbus-Instanzen, Archive und Historien bleiben unangetastet. Datenverlust wird vorab explizit benannt.

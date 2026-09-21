# Offene Punkte und Coding-Gates

Die Punkte G1 bis G7 müssen vor produktivem Modulcode geklärt sein. Alles Weitere kann während des read-only Prototyps präzisiert werden.

| ID | Priorität | Offener Punkt | Benötigter Nachweis | Auswirkung |
|---|---|---|---|---|
| G1 | Blocker | Abgrenzung zum öffentlichen Repository `symcon/Proxon` | Code-, Funktions-, Lizenz- und Wartungsanalyse | entscheidet über Fork, Beitrag oder eigenständiges Modul |
| G2 | Blocker | Darf das Zielgerät FC16 statt des geforderten FC06 akzeptieren? | kontrollierter Hardwaretest je Serie/Firmware | entscheidet über nativen Schreibpfad |
| G3 | Blocker | Welche IP-Symcon-Mindestversion ist verbindlich? | Nutzerentscheidung und SDK-Abgleich | bestimmt Modulbasis und Visualisierungs-API |
| G4 | Blocker | Nutzungsrecht für Registerbezeichnungen und abgeleitete Kataloge | Lizenz-/Freigabeklärung | entscheidet über Veröffentlichung des Katalogs |
| G5 | Blocker | Reale Slave-Adressen, Busparameter und Topologie | Konfiguration bzw. read-only Scan mit Freigabe | verhindert falsche Gerätezuordnung |
| G6 | Blocker | Ziel-Firmwarestände von FWT und T300 | Typenschild/Serviceanzeige | bestimmt Registervarianten |
| G7 | Blocker | Welche bestehenden Modbus-Instanzen dürfen weiterverwendet werden? | Inventar der konkreten Symcon-Anlage | entscheidet über Greenfield oder Adapter |
| O1 | Hoch | Bedeutung/Einheit der T300-Zähler 847–860 | Vergleich mit Servicewert und Zeitverlauf | korrekte Laufzeitanzeige |
| O2 | Hoch | FWT Holding 438: Rohmaximum 55555 vs. Engineering-Maximum 2 | Herstellerklärung oder Hardwaretest | sichere Validierung |
| O3 | Hoch | Geänderte Enumeration an FWT Holding 16 | Firmwarebezogener Read-only-Vergleich | korrekte Betriebsart |
| O4 | Hoch | Modusabhängige Schreibbarkeit von Holding 187 | Testmatrix Modus/Firmware | verhindert abgewiesene Befehle |
| O5 | Mittel | Sind Zonen feste Unterobjekte oder spätere Einzelinstanzen? | reale Zahl, Bedien- und Archivbedarf | Objektstruktur |
| O6 | Mittel | Welche Register sollen archiviert werden? | Nutzerentscheidung zu Historie und Last | Archiv- und Speicherbedarf |
| O7 | Mittel | T300-Energiequelle | externer Zähler oder belegtes Register | Energiekennzahlen |
| O8 | Mittel | Kopplung zwischen FWT, T300, PV und externen Sensoren | konkrete Anlagenplanung und IDs | Komfortautomation |
| O9 | Mittel | Erlaubte Schreibfunktionen im ersten Release | einzeln bestätigte Freigabeliste | Umfang und Sicherheitskonzept |
| O10 | Niedrig | Store-Veröffentlichung oder privates Repository | Produktentscheidung | Metadaten, Support und Releaseprozess |

## Benötigte Informationen vom Betreiber

- exakte Gerätebezeichnungen, Serien/Firmware und Bedienpanel-Varianten,
- Foto oder Export der RS485-/Modbus-Einstellungen,
- Übersicht vorhandener Symcon-I/O-, Splitter- und ModBus-Instanzen,
- gewünschte Slave-Adresse je Gerät,
- Entscheidung, ob der erste Feldtest strikt read-only bleibt,
- Liste externer Sensoren und ihrer Einheiten, falls Komfortfunktionen geplant sind.

## Zulässiger Fortschritt trotz offener Punkte

Ohne die Blocker zu schließen, dürfen Schema, Parser, statische Tests, simulierte Provider, read-only Domainmodell und native Kachelprototypen entwickelt werden. Nicht zulässig sind produktive Schreibbefehle, automatische Migrationen der Bestandsanlage oder eine Veröffentlichung mit ungeklärten Rechten.

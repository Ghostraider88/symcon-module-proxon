# Verbindlicher Produkt-Baseline-Entscheid

Stand: 19.09.2026. Dieses Dokument konkretisiert und überschreibt widersprüchliche frühere Planungsannahmen.

## Basisrepository

Die Entwicklung baut auf der von Symcon freigegebenen Basis auf:

<https://github.com/symcon/Proxon>

Der konkret verwendete Commit/Branch wird beim Start der Implementierung festgehalten. Bestehende GUIDs und tragende Strukturen werden nicht ohne Prüfung geändert.

## Transport

Es wird ausschließlich die Symcon-eigene Modbus-Infrastruktur verwendet. Das PROXON-Modul implementiert keinen eigenen Modbus-Stack, keinen eigenen RTU-/TCP-Treiber und keinen konkurrierenden Parent.

Im Greenfield-Fall darf der Configurator die erforderlichen Symcon-Instanzen anlegen oder konfigurieren, insbesondere:

- Modbus-Gateway beziehungsweise I/O,
- Symcon-Modbus-Geräte oder -Adressen,
- logische PROXON-Instanzen und deren Variablen.

Im Bestandsfall können bestehende Symcon-Modbus-Instanzen referenziert werden. Beide Wege verwenden dieselbe Symcon-Transportinfrastruktur.

## Geräteumfang

- FWT 2.0 und FWT 3.0 sind die Zielprofile.
- T300 wird ausschließlich über die FWT-Topologie unterstützt.
- Eine direkte T300-Anbindung ist kein unterstütztes Produktprofil und wird nicht als separater Installationsweg modelliert.
- Varianten ohne verifiziertes Profil bleiben sichtbar als unverified/read-only oder werden nicht als unterstützt angeboten.

## Funktionsumfang zum Start

Der erste Entwicklungszyklus umfasst Greenfield-read-only für FWT 2.0/3.0 und T300 hinter FWT:

- Registerprofile und Skalierung,
- Zustands- und Qualitätsmodell,
- Diagnose,
- native Symcon-Kacheln,
- optionales Erzeugen der Symcon-Modbus-Struktur.

Komfortautomation und Schreibaktionen folgen später. Der FWT-Schreibpfad basiert auf FC06; FC16 wird für diese FWT nicht benötigt.

## Bestandsintegration

Die konkrete Betreiberanlage ist Referenz und optionale Migrationsumgebung, nicht die normative Produktkonfiguration. Ihre Objekt-IDs, Pfade, Skripte und Schreiber werden nicht in den Greenfield-Standard übernommen. Der G5-Inventarbericht ist daher für die Entwicklung informativ, aber kein Startblocker.

## Verbindlicher Status

| Entscheidung | Status |
|---|---|
| Symcon-Basisrepository | entschieden |
| Symcon-eigene Modbus-Infrastruktur | entschieden |
| FWT 2.0/3.0 | entschieden |
| T300 nur hinter FWT | entschieden |
| Greenfield-first | entschieden |
| Bestandsmapping | optional |
| FC06 für FWT | entschieden |
| FC16 für FWT | nicht erforderlich |

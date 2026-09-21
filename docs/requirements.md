# Anforderungen

## Geltungsbereich

Ziel ist eine lokale IP-Symcon-Integration für PROXON FWT 2.0/3.0 und T300, die Registerkommunikation, logische Gerätezustände, sichere Bedienung, Diagnose und Komfortfunktionen verbindet. P 3.0 und weitere Varianten sind katalogseitig vorzubereiten, aber nicht ohne verifizierte Registerprofile als unterstützt zu bezeichnen.

## Funktionale Anforderungen

| ID | Anforderung | Priorität | Abnahmehinweis |
|---|---|---|---|
| REQ-001 | Zwei Installationswege: neue Modbus-Struktur und Nutzung vorhandener Infrastruktur | Muss | Beide Wege erzeugen dieselben logischen Gerätewerte |
| REQ-002 | Vollständig versionierter Registerkatalog mit Herkunft und Konflikten | Muss | Jede Definition nennt Quelle, Registerraum, Funktion, Adresse und Skalierung |
| REQ-003 | FWT-Zentralgerät mit Betriebsart, Temperaturen, Lüftung, Heiz-/Kühlstatus und Diagnose | Muss | Read-only-MVP auf realer Anlage verifiziert |
| REQ-004 | T300 mit Tanktemperaturen, Betriebszuständen, PV, Boost und Diagnose | Muss | Direktanbindung und „hinter FWT“ sind getrennte Profile |
| REQ-005 | Dynamische Zonen-/Raumabbildung, ohne feste Objekt-IDs oder Namen | Muss | 0, 1, 2 und mehrere Panel-/Raumkonfigurationen getestet |
| REQ-006 | Automatische, begrenzte Geräteerkennung mit Vorschau und Bestätigung | Soll | Kein Vollscan als Standard; keine Schreibprobe |
| REQ-007 | Mapping vorhandener Variablen nach Slave, Funktion, Adresse, Typ und Skalierung | Muss | Mehrdeutigkeiten und fehlende Quellen werden angezeigt |
| REQ-008 | Einheitliche logische Variablen unabhängig vom Transportweg | Muss | Verbraucherlogik kennt keine fremden Objekt-IDs |
| REQ-009 | Zentraler Schreibarbiter mit genau einem autoritativen Schreiber je Zielregister | Muss | Parallel- und Prioritätstests bestanden |
| REQ-010 | Schreibschutz standardmäßig aktiv; explizite Freigabe je Geräteprofil | Muss | Frische Daten, Bereich, Modus und Bestätigung werden geprüft |
| REQ-011 | Read-back nach FC06; Befehl, bestätigter Wert und Aktorzustand getrennt | Muss | Timeout erzeugt „unbestätigt“, nicht „erfolgreich“ |
| REQ-012 | Polling-Gruppen schnell, normal, langsam und Service mit Backoff | Muss | Buslast ist messbar begrenzt |
| REQ-013 | Kommunikationsqualität, Datenalter, letzte Antwort und Fehlerzähler | Muss | Offline und stale sind unterscheidbar |
| REQ-014 | Zentrale Betriebsarten und temporäre Overrides mit Ablauf und Rückkehr | Soll | Neustart und Ablauf liefern deterministischen Zustand |
| REQ-015 | Dusch-/Badeboost, Intensivlüftung, Ruhe, Abwesenheit und Urlaub | Soll | Sicherheits- und Rückkehrbedingungen dokumentiert |
| REQ-016 | PV-/SG-Ready-Komfortbetrieb mit Hysterese und Mindestlaufzeit | Soll | Keine Reaktion auf kurze Leistungsspitzen |
| REQ-017 | CO2-/Feuchteautomation nur mit validen, frischen Sensorwerten | Soll | Sensorfehler deaktiviert Automatik kontrolliert |
| REQ-018 | Zeitprogramme lesbar; Schreiben erst nach separater Freigabe | Soll | Vollständiger Wochenplan wird atomar validiert |
| REQ-019 | Erklärbare Zustände mit Regelgrund und blockierender Bedingung | Muss | „Aus“ nennt Ursache statt nur Status |
| REQ-020 | Native IP-Symcon-Kacheln einschließlich Anlagenschaubild | Muss | Kernzustände ohne Unterdialog sichtbar und im Kachelraster responsive |
| REQ-021 | Read-only-Selbsttest mit konkreten Empfehlungen | Muss | Selbsttest verändert keine Infrastruktur |
| REQ-022 | Historisierung nur als Empfehlung; niemals automatisch aktivieren | Muss | Keine Archivmutation durch ApplyChanges |
| REQ-023 | Migration ohne gleichzeitige alte und neue Schreiblogik | Muss | Umschaltung enthält kontrollierten Cutover und Rollback |
| REQ-024 | Service-/Rohregister standardmäßig verborgen oder deaktiviert | Muss | Alltagssicht bleibt kompakt |
| REQ-025 | Registerprofile nach Serie, Firmware und Quellenversion | Muss | Unbekannte Firmware bleibt read-only/degraded |

## Nichtfunktionale Anforderungen

| ID | Anforderung |
|---|---|
| NFR-001 | Kompatibilität mit der vor Coding festgelegten IP-Symcon-Version; neue Module nutzen `IPSModuleStrict`, falls Mindestversion 8.1 bleibt. |
| NFR-002 | Keine Cloud-Pflicht, keine Zugangsdaten im Code oder Log und keine Nachbildung der Hersteller-App-API. |
| NFR-003 | Idempotentes `Create()`/`ApplyChanges()` und updatefeste Idents; keine Duplikate bei wiederholter Konfiguration. |
| NFR-004 | Keine stillen Annahmen: unbekannte Skalierung, Byte-Reihenfolge oder Schreibbarkeit bleibt „unverifiziert“. |
| NFR-005 | Ein Busmaster bzw. serialisierte Kommunikation pro RTU-Bus; begrenzte Timeouts, Retries und Backoff. |
| NFR-006 | Barrierearme Darstellung: Text/Icon zusätzlich zur Farbe, ausreichender Kontrast, Touch-Ziele und Hell-/Dunkeldesign. |
| NFR-007 | Erweiterbar um weitere Serien ohne Änderung vorhandener Katalog-IDs oder Nutzerhistorien. |
| NFR-008 | Unit-, Katalog-, Lifecycle-, Migrations- und Hardware-in-the-loop-Tests sind Release-Gates. |
| NFR-009 | Originalquellen werden nicht verändert und ohne Rechteprüfung nicht veröffentlicht. |

## Abgrenzung

- Kein vollständiges Modul in dieser Phase.
- Keine produktiven Schreibtests, Objektänderungen oder Gerätesteuerung.
- Keine garantierte Energie- oder JAZ-Auswertung, solange Herstellerregister dies nicht implementieren oder externe Messung fehlt.
- Keine automatische lernende Regelung im MVP.

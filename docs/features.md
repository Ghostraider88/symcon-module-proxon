# Funktionsmatrix

Legende: **MVP** = erste produktive, überwiegend lesende Stufe; **Später** = nach Hardwarevalidierung; **Optional** = experimentell oder von Fremddaten abhängig.

| ID | Kategorie | Funktion | Nutzen | Benötigte Daten | Machbarkeit | Stufe | Quelle |
|---|---|---|---|---|---|---|---|
| FEATURE-001 | Basisintegration | FWT-Basiszustand | Zentrale, verständliche Anlage statt Rohregister | Modus, Temperaturen, Lüfter, WP, Fehler | Hoch | MVP | XLSX/PDF |
| FEATURE-002 | Basisintegration | T300-Basiszustand | Warmwasser transparent und lokal | Tankfühler, Relais, Sollwert, Fehler | Hoch | MVP | XLSX/PDF |
| FEATURE-003 | Basisintegration | Dynamische Zonen/Räume | Unterstützt unterschiedliche Anlagen | Panel-/Zonenregister, Zuordnung | Mittel | MVP | XLSX/PDF/Broschüre |
| FEATURE-004 | Diagnose | Kommunikationsqualität | Fehlerursache statt „keine Daten“ | Zeitstempel, Timeout, CRC/Modbusstatus | Hoch | MVP | Architektur |
| FEATURE-005 | Diagnose | Erklärbarer Regelgrund | Nutzer versteht Aus/An/Blockade | Domainzustand, Sperrzeiten, Anforderungen | Mittel | MVP | Produktvorschlag |
| FEATURE-006 | Wartung | Filter-/Wartungsstatus | Planbare Wartung | Filterlaufzeit, Betriebsstunden | Hoch | MVP | XLSX/App |
| FEATURE-007 | Steuerung | Betriebsart mit Read-back | Sichere Alltagsbedienung | Holding 16 bzw. Profilzuordnung | Hoch nach Test | MVP spät aktiv | PDF |
| FEATURE-008 | Steuerung | Lüfterstufe | Direkter Komfort | Stufe, WP-/Modusabhängigkeit | Hoch nach Test | MVP spät aktiv | PDF |
| FEATURE-009 | Komfort | Intensivlüftung mit Timer/Rückkehr | Kochen/Duschen ohne Dauerbetrieb | Dauer, Stufe, vorheriger Modus | Hoch | Später | PDF/Bestand |
| FEATURE-010 | Komfort | Warmwasser-/Duschboost | Temporär mehr Warmwasser | T300 Soll, Kompressor/Heizstab, Timer | Mittel, sicherheitskritisch | Später | Bestand/PDF |
| FEATURE-011 | Komfort | Abwesenheit/Urlaub | Energie sparen bei Schutzfunktionen | Modus, Sollwerte, Rückkehrtermin | Mittel | Später | Produktvorschlag/App |
| FEATURE-012 | Komfort | Ruhemodus | Begrenzte Geräuschentwicklung | Lüftergrenze, Zeitfenster | Mittel | Später | Produktvorschlag |
| FEATURE-013 | Automatisierung | Zentrale Prioritätssteuerung | Keine konkurrierenden Schreiber | Alle Command Intents, Sicherheitszustand | Hoch, zentral | MVP-Grundlage | Bestand |
| FEATURE-014 | Automatisierung | CO2-/Feuchteführung | Luftqualität bedarfsgerecht | Sensoren, Schwellen, Hysterese, Datenalter | Hoch bei Sensoren | Später | PDF/Broschüre |
| FEATURE-015 | Automatisierung | Sommerliche Nachtkühlung | Passive Komfortverbesserung | Außen/Innen, Bypass, Feuchte, Heizbedarf | Mittel | Später | Broschüre/Produktvorschlag |
| FEATURE-016 | Automatisierung | Fenster-/Anwesenheitslogik | Vermeidet Energieverlust | Externe Kontakte/Präsenz | Mittel, fremde Daten | Optional | Produktvorschlag |
| FEATURE-017 | Energie | PV-/SG-Ready-Betrieb | Eigenverbrauch erhöhen | PV-Freigabe/Überschuss, T300/FWT, Hysterese | Mittel | Später | PDF/Broschüre |
| FEATURE-018 | Energie | Leistungstrend FWT | Verbrauch sichtbar | FWT-Leistungsregister + Archivfreigabe Nutzer | Hoch | Später | PDF |
| FEATURE-019 | Energie | T300-Energie | Echte Verbrauchsaussage | Externer Zähler oder belegtes Register | Derzeit nicht belegt | Optional | Offener Punkt |
| FEATURE-020 | Monitoring | Tagesband der Betriebszustände | Ursachen und Laufzeiten verstehen | Archivierte Domainzustände | Hoch nach Nutzerfreigabe | Später | Produktvorschlag |
| FEATURE-021 | Visualisierung | Natives Anlagenschaubild | Luftwege und Zustände auf einen Blick | Temperaturen, Klappen, WP, Zonen, T300 | Hoch | MVP | Broschüre/Mockup |
| FEATURE-022 | Diagnose | Read-only-Selbsttest | Konkrete Hilfe ohne Risiko | Mapping, Profile, Datenalter, Schreibmodus | Hoch | MVP | Template |
| FEATURE-023 | Wartung | Support-Export | Reproduzierbare Diagnose | Maskierte Konfiguration und Status | Hoch | Später | Marktanalyse |
| FEATURE-024 | Automatisierung | Vorheizen/Vorkühlen | Komfort zum Termin | Historie, Außentemperatur, Zeitplan | Mittel | Optional | Produktvorschlag |
| FEATURE-025 | Automatisierung | Wetterprognose | Vorausschauende Regelung | Externer Wetterdienst | Mittel, Abhängigkeit | Optional | Produktvorschlag |
| FEATURE-026 | Automatisierung | Lernende Profile als Vorschlag | Muster erkennen ohne Autonomierisiko | Historie und Nutzerbestätigung | Niedrig/Mittel | Optional | Produktvorschlag |

## Produktregeln

- Automationen besitzen die Modi `Beobachten`, `Vorschlagen` und `Automatisch`, soweit ein Fehlbefehl materiell wäre.
- Elektroheizung, Legionellenfunktion, hohe Warmwassertemperaturen, Fehlerquittierung und Neustart sind Expertenaktionen mit Bestätigung.
- „Nicht implementierte“ JAZ-Register und aus FWT-Leistung abgeleitete T300-Energie werden nicht als valide Kennzahl ausgegeben.
- Ein Komfortfeature wird nur angezeigt, wenn benötigte Register und Sensoren vorhanden und frisch sind.

## Empfohlener MVP

Configurator, Registerprofil, Mapping, FWT Central, dynamische Zonen, T300, Datenqualität, Diagnose, native Symcon-Kacheln und vollständig read-only Betrieb. Schreibaktionen werden technisch vorbereitet, aber erst nach Hardwareabnahme selektiv freigeschaltet.

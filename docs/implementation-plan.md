# Umsetzungsplan

Der Plan startet erst nach bewusster Freigabe zur Implementierung. Die aktuelle Lieferung endet mit Phase 0.

## Phase 0 – Projektbasis und Gates

Ergebnis: archivierte Quellen, reproduzierbarer Registerkatalog, Architektur, UX-Konzept, Testplan und offene Entscheidungen.

Abnahme:

- Quellenhashes stimmen,
- Katalog zählt 822 Excel- und 330 PDF-Datensätze,
- alle Blocker G1–G7 besitzen Eigentümer oder Entscheidung,
- keine produktive Instanz und kein Gerät wurde verändert.

## Phase 1 – Fundament und Simulation

- Modulmetadaten, stabile GUIDs, Lokalisierung und CI festlegen.
- Katalogloader, Skalierung, Enum-/Bitmasken-Decoder und Qualitätsmodell implementieren.
- simulierten Provider mit aufgezeichneten, anonymisierten Rohwertfolgen erstellen.
- Configurator und Gateway-Lifecycle ohne realen Schreibpfad testen.

Exit: statische Tests grün, Wiederholung von `ApplyChanges()` idempotent, Parser deckt alle Katalogtypen ab.

## Phase 2 – Read-only FWT

- native Modbus- bzw. Bestandsvariablen-Anbindung konfigurieren,
- konservative Erkennung und Mapping,
- FWT-Fachzustand, Zonen, Diagnose und Kacheln,
- Buslast und Datenalter messen.

Exit: mindestens 72 Stunden stabiler read-only Betrieb, keine parallele Busbelegung, plausible Werte und dokumentierte Firmware.

## Phase 3 – Read-only T300

- T300-Erkennung und Fachmodell,
- Temperaturen, Relais, Fehler und Mehrregisterzähler,
- Einheit der Zähler 847–860 verifizieren,
- Warmwasser- und Diagnosekacheln integrieren.

Exit: mindestens 72 Stunden stabil, Sensoren mit Serviceanzeige verglichen, unklare Einheiten nicht produktiv umgerechnet.

## Phase 4 – Schreibpfad im Labor

- Gate FC06/FC16 schließen,
- zentrale Queue, Semaphore, Limits, Cooldown und Auditlog,
- pro freigegebenem Register Negativtests und Read-back,
- Neustart, Timeout, Busfehler und konkurrierende Intents testen.

Exit: jede Aktion besitzt Zielregister, Bereich, Rückmeldung, Rückfallverhalten und Betreiberfreigabe.

## Phase 5 – Selektive Bedienung

- zunächst eine ungefährliche Funktion freigeben,
- native Profile und Bestätigungsdialoge,
- Beobachtungsphase und Vergleich mit Gerätebedienung,
- erst danach Betriebsart, Lüfter und Warmwassersollwert einzeln ergänzen.

## Phase 6 – Komfortfunktionen

Reihenfolge: zeitlich begrenzte Intensivlüftung, Ruhe-/Abwesenheitsmodus, Warmwasserboost, CO2/Feuchte, PV/SG Ready, Nachtkühlung. Jede Funktion beginnt in `Beobachten`, wechselt optional zu `Vorschlagen` und erst nach Abnahme zu `Automatisch`.

## Phase 7 – Veröffentlichung und Betrieb

- Installations-, Update-, Mehrinstanz- und Deinstallationstest,
- Datenschutz- und Lizenzprüfung,
- Supportexport und Troubleshooting,
- semantische Versionierung, Changelog und Rückfallplan,
- optional Module Store nach Abgrenzung zum bestehenden Repository.

## Empfohlene Arbeitspakete

1. G1 Repository-/Lizenz-Gap klären.
2. G3 Zielversion und SDK-Basis festlegen.
3. Registerkatalog fachlich reviewen.
4. Businventar und Firmware erfassen.
5. Simulator-Fixturns erstellen.
6. Read-only Fundament implementieren.
7. FWT und T300 getrennt in Betrieb nehmen.
8. FC06/FC16-Labortest durchführen.
9. Schreibfunktionen einzeln abnehmen.
10. Komfortregeln nur mit realen Sensordaten ergänzen.

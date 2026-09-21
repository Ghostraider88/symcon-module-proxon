# Betreiberentscheidungen und Klärung der Gates

Stand: 18.09.2026. Diese Ergänzung hält die aktuellen Antworten des Betreibers fest und präzisiert die ursprünglichen G1–G7-Gates.

## G1 – Abstimmung mit Symcon

Der Betreiber klärt die Abgrenzung und mögliche Zusammenarbeit direkt mit Symcon. Bis zu dieser Rückmeldung bleibt die Entscheidung zu Repository, GUIDs, Prefix und Veröffentlichung offen. Für interne read-only Planung und Simulation wird kein fremder Code übernommen.

## G2 – FC06/FC16 sicher testen

Der Test soll nicht durch einen produktiven Komfortbefehl erfolgen. Empfohlen ist ein isoliertes Wartungsfenster, zunächst mit einem einzigen vom Hersteller als harmlos eingestuften R/W-Holding-Register.

### Stufe A: Konfiguration prüfen

1. Temporäre ModBus-Adresse oder ein temporäres ModBus-Gerät anlegen.
2. Lesen auf Holding/FC03 und die direkte PDU-Adresse konfigurieren.
3. Bei „Funktion Schreiben“ prüfen, ob die Zielversion FC06 („Write Single Register“) überhaupt anbietet. `Status emulieren` bleibt deaktiviert.
4. Bestehenden Rohwert lesen und exakt denselben Rohwert als Testwert verwenden. Dadurch soll sich der Anlagenzustand nicht ändern.

Das Vorhandensein eines FC06-Auswahlfeldes ist nur ein Indiz, noch kein Beweis für das tatsächlich gesendete Telegramm.

### Stufe B: Telegramm und Read-back

1. Schreibtelegramm im Testfenster auslösen.
2. Bei Modbus TCP den Datenstrom am Gateway beziehungsweise mit einem geeigneten Netzwerk-Mitschnitt prüfen; bei Modbus RTU ist ein galvanisch geeigneter RS485-Analysator oder ein Gateway-Log erforderlich.
3. Nachweisen: Slave, FC06, PDU-Adresse, Rohwert und Echo-Antwort des Geräts.
4. Direkt danach FC03 lesen und Rohwert vergleichen.
5. Den gleichen Test mindestens dreimal wiederholen und mit Gerätename, Firmware, Busparametern, Datum und Ergebnis protokollieren.

Ein erfolgreicher Symcon-Wert allein reicht nicht, wenn `Status emulieren` aktiv ist oder nur der lokale Sollwert geändert wurde. Es zählt die Antwort des Geräts plus Read-back. FC16 wird nicht als Versuch an der produktiven Anlage gesendet.

### Sicherheitsgrenze

Nicht für den Ersttest verwenden: Heizstab, Legionellenfunktion, PV-Boost, Warmwasser-Sollwert, Betriebsartwechsel, Neustart, Fehlerquittierung oder ein Register mit unbekannter Wirkung. Falls kein sicher eingestuftes R/W-Register verfügbar ist, bleibt der Schreibpfad read-only und der FC-Test wird an einem Laboraufbau, Simulator oder ausdrücklich freigegebenen Wartungsgerät durchgeführt.

## G3 – Zielversion IP-Symcon

Als Planungsbasis wird IP-Symcon **9.0** angenommen. Die verwendeten APIs und das Visualisierungskonzept werden gegen 9.0 geprüft. Höhere Versionsfunktionen dürfen nicht stillschweigend vorausgesetzt werden; ein optionaler 9.1+-Pfad wird nur separat dokumentiert.

Das ändert nicht die Sicherheitsreihenfolge: Die read-only Phase kann auf 9.0 geplant werden, der Schreibpfad bleibt bis G2 gesperrt.

## G4 – Verwendung veröffentlichter PROXON-Daten

Für ein privates, internes Projekt können die von PROXON veröffentlichten Modbus-Informationen als technische Interoperabilitätsquelle verwendet und mit Quellenangabe archiviert werden. Für ein öffentlich verteiltes Modul gilt zusätzlich:

- veröffentlichte Registerfakten und selbst erstellte Normalisierung klar trennen,
- keine vollständige Hersteller-Tabelle, Logos oder App-Screenshots ungeprüft in das Release kopieren,
- Original-PDF/XLSX als Entwicklungsquelle außerhalb des Releases behandeln,
- Lizenz-, Marken- und Namensverwendung vor Store-/Repository-Veröffentlichung prüfen.

Damit ist G4 für interne Planung kein Blocker, bleibt aber ein Release-Gate. Die Projektdokumentation verwendet die Daten bereits als Quellen, nicht als fremden Programmcode.

## G5 – Was mit Bestandsaufnahme gemeint ist

G5 bedeutet nicht, dass der Betreiber jede Registerzeile manuell aufschreiben muss. Gemeint ist eine Übersicht der bereits laufenden Symcon-Kommunikation, damit das neue Modul nicht dasselbe Register doppelt abfragt oder parallel zu alten Skripten schreibt.

Minimal benötigt werden:

- verwendetes Modbus-Gateway beziehungsweise I/O und RTU/TCP-Verbindung,
- vorhandene ModBus-, Splitter- und Device-Instanzen,
- Slave-Adressen und Busparameter,
- vorhandene FWT-/T300-Registervariablen oder Modbus-Tabellen,
- Skripte und Ereignisse, die auf diese Variablen schreiben,
- Archive und wichtige Verbraucher, die an den Variablen hängen.

Ein Screenshot des Objektbaums, ein Export oder eine von Symcon gelieferte Liste reicht zunächst. Objekt-IDs werden erst aus der aktuellen Anlage übernommen, niemals geraten.

## G6 – Firmware und Serie

Firmware ist für die erste Architektur- und Katalogphase nicht zwingend. Für die konkrete Hardwarefreigabe bleibt sie jedoch relevant, weil Register 16 und 187 unterschiedliche Semantik beziehungsweise Schreibbarkeit zeigen können.

Praktische Konsequenz:

- stabile, read-only Register können mit einem unbekannten Profil vorsichtig gelesen werden,
- unbekannte Firmware bleibt `read-only/degraded`,
- mehrdeutige Enumerationen und sämtliche Schreibfunktionen bleiben gesperrt,
- vor einem produktiven Profil wird Modell, Serie und Firmware einmal dokumentiert.

G6 wird daher von einem frühen Blocker zu einem Hardware-/Schreibfreigabe-Gate herabgestuft, nicht vollständig gestrichen.

## G7 – Vorhandene Quellen nutzen, fehlende optional anlegen

Das wird als Bestandsmodus unterstützt:

- alle brauchbaren vorhandenen Variablen und Modbus-Instanzen werden bevorzugt referenziert,
- fehlende Werte können nach Vorschau und Nutzerbestätigung optional ergänzt werden,
- neue Pollingobjekte werden nur für tatsächlich fehlende Daten angelegt,
- keine parallele Abfrage desselben Registers,
- fremde Instanzen, Archive und Schreiber werden nicht automatisch gelöscht.

Damit entsteht ein hybrider Installationsweg: vorhandene Infrastruktur bleibt erhalten, nur belegte Lücken werden kontrolliert ergänzt.

## Aktueller Gate-Status

| Gate | Status |
|---|---|
| G1 | externe Abstimmung läuft |
| G2 | Hardware-/Telegrammtest offen |
| G3 | vorläufig entschieden: IP-Symcon 9.0 |
| G4 | für internes Projekt freigegeben; Veröffentlichung bleibt zu prüfen |
| G5 | Bestandsinventar der Anlage noch erforderlich |
| G6 | für Architektur nicht blockierend, für Profile/Schreiben erforderlich |
| G7 | entschieden: Bestand bevorzugen, Lücken optional ergänzen |

# MCP-Auftrag: G5 – Bestandsaufnahme der vorhandenen Symcon-Modbus-Struktur

## Rolle

Du arbeitest als read-only IP-Symcon-Inventaragent. Ermittle die vorhandene Modbus- und PROXON-Struktur, damit ein neues PROXON-Modul brauchbare Quellen wiederverwenden und fehlende Daten später kontrolliert ergänzen kann. Du veränderst keine Objekte, Variablen, Werte, Aktionen, Archive oder Ereignisse.

## Harte Regeln

1. Ausschließlich lesende MCP-Aufrufe verwenden: `symcon_get_object`, `symcon_get_children`, `symcon_get_variable`, `symcon_get_value`, `symcon_get_variable_by_path`, `symcon_get_object_id_by_name`, `symcon_get_script_content` und, falls erforderlich, `symcon_snapshot_variables`.
2. Nicht verwenden: `symcon_set_value`, `symcon_request_action`, `symcon_run_script`, `symcon_run_script_text`, `symcon_script_set_content`, `symcon_script_create` oder `symcon_script_delete`.
3. Niemals IDs aus früheren Projekten oder aus dem Registerkatalog erraten. Jede ID muss mit aktuellem Pfad, Objekttyp und Metadaten verifiziert werden.
4. Wenn ein Bereich nicht aufgelöst werden kann, als „nicht ermittelbar“ ausweisen und nicht durch Vermutungen ersetzen.
5. Zugangsdaten, Tokens, Passwörter und vollständige sensible Konfigurationsdaten maskieren.

## Suchstrategie

Beginne mit der Objektstruktur und arbeite dann gezielt in Richtung Modbus/PROXON. Verwende vorhandene bekannte Namen nur als Suchbegriffe, nicht als Beweis. Suche nach Begriffen wie `ModBus`, `Modbus`, `PROXON`, `FWT`, `T300`, `RS485`, `RTU`, `TCP`, `Gateway`, `Holding`, `Input`, `Lüfter`, `Betriebsart` und `Temperatur`.

Für jeden relevanten Bereich erfassen:

### 1. Verbindungen und Gateways

- Objekt-ID und Pfad,
- Objekttyp/Instanztyp,
- seriell oder TCP,
- sichtbare Verbindungsparameter ohne Geheimnisse,
- Baudrate, Datenbits, Parität, Stopbits, Port und IP maskiert beziehungsweise gekürzt,
- aktueller Status.

### 2. Modbus- und PROXON-Instanzen

- Gateway-/Parent-Bezug,
- Instanz-ID und Pfad,
- Gerätetyp (ModBus Adresse, ModBus Gerät, Splitter, eigenes Modul),
- Slave-/Geräteadresse,
- Lese-/Schreibfunktion und Registeradresse, falls sichtbar,
- Intervall und aktive/deaktivierte Konfiguration,
- Variablen und Profile darunter.

### 3. Kandidaten für FWT und T300

Für jede Variable oder Modbus-Tabelle mit möglichem Bezug erfassen:

- ID, Pfad, Name, Typ, Profil und Einheit,
- aktueller Wert und Zeitstempel,
- Registerraum, Adresse, Funktion, Skalierung, Breite, Vorzeichen und Byte-/Wortreihenfolge,
- ob es sich um Istwert, Sollwert, Status oder Diagnose handelt,
- ob eine Geräteaktion vorhanden ist,
- ob Quelle und Zuordnung eindeutig, mehrdeutig oder unbekannt sind.

### 4. Schreiber und abhängige Logik

Durchsuche unterhalb relevanter Bereiche Skripte, Ereignisse und Aktionsvariablen. Lies Skriptquellen nur, um schreibende Ziele zu erkennen. Erfasse:

- Skript-/Ereignis-ID und Pfad,
- Trigger beziehungsweise Intervall,
- Zielvariable beziehungsweise Zielinstanz,
- verwendete Aktion oder direkte Wertsetzung,
- Bezug zu FWT/T300/Modbus,
- Risiko einer späteren Doppelsteuerung.

Wenn Ereignisdetails oder Aktionsinformationen über MCP nicht vollständig lesbar sind, dokumentiere genau diese Lücke.

### 5. Archive und Verbraucher

Für relevante Variablen erfassen, ob Archivierung aktiv ist, welcher Archivtyp verwendet wird und welche offensichtlichen Verbraucher/Visualisierungen daran hängen. Keine Archivierung aktivieren oder ändern.

## Bewertung

Ordne jeden Datenpunkt einer Kategorie zu:

- `reuse_candidate`: brauchbare bestehende Quelle,
- `duplicate_risk`: bereits vorhandene oder parallele Abfrage,
- `writer_conflict`: bestehender Schreiber erkannt,
- `missing_optional`: für das Zielmodell sinnvoll, aber nicht vorhanden,
- `ambiguous`: mehrere nicht sicher unterscheidbare Quellen,
- `unverified`: Metadaten oder Profil nicht ausreichend.

Ein fehlender Datenpunkt ist kein Fehler. Er wird als optionaler Ergänzungskandidat dokumentiert, niemals automatisch angelegt.

## Ergebnisformat

Erstelle einen kompakten Inventarbericht mit:

1. Zusammenfassung der vorhandenen Gateways und Verbindungen,
2. Tabelle aller relevanten Modbus-/PROXON-Instanzen,
3. Tabelle der wiederverwendbaren FWT-/T300-Variablen,
4. Tabelle bestehender Schreiber und möglicher Konflikte,
5. Archive und abhängige Verbraucher,
6. fehlende, optionale Datenpunkte,
7. Mehrdeutigkeiten und nicht lesbare Bereiche,
8. Empfehlung für Greenfield, Bestandsmapping oder hybriden Betrieb.

Der Bericht muss ausdrücklich bestätigen: „Es wurden keine Werte, Aktionen, Objekte oder Archive verändert.“

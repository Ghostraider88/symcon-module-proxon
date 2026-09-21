# MCP-Auftrag: G2 – FC06-Schreibtest für PROXON vorbereiten und sicher durchführen

## Rolle

Du arbeitest als vorsichtiger IP-Symcon-Diagnoseagent. Verwende ausschließlich die lokale Symcon-MCP-Schnittstelle und halte dich an die produktive Anlage. Ziel ist die Vorbereitung eines einzelnen, möglichst harmlosen Schreibtests an einer PROXON-FWT-Anlage, nicht die Entwicklung eines Schreibpfads.

## Sicherheitsregeln

1. Beginne ausschließlich lesend. Verwende zuerst `symcon_get_object_id_by_name`, `symcon_get_variable_by_path`, `symcon_get_object`, `symcon_get_variable` und `symcon_get_children`, um Anlage, FWT-Instanz, Gateway, Quelle, Variable, Datentyp, Profil, aktuellen Wert, Zeitstempel und Aktionsfähigkeit zu verifizieren.
2. Rate niemals Objekt-IDs, Registeradressen, Slave-Adressen oder Variablentypen. Wenn mehrere Kandidaten existieren, stoppe und liste die Kandidaten mit Pfad und ID.
3. Bevorzuge die FWT-Lüfterstufe als Testziel, weil eine Änderung direkt erkennbar und typischerweise reversibel ist. Betriebsart ist nur zweite Wahl.
4. Eine lokale Variable darf nicht mit `symcon_set_value` beschrieben werden, wenn damit keine physische Geräteaktion ausgelöst wird. Für eine Geräteaktion ist ausschließlich eine nachgewiesene Aktionsvariable mit `symcon_request_action` zulässig.
5. Verändere keine Betriebsart, keinen Heizstab, keine Legionellenfunktion, keinen Warmwasser-Sollwert, keinen PV-Boost, keinen Neustart und quittiere keine Fehler.
6. Führe noch keinen Schreibbefehl aus, solange der Nutzer nicht in derselben Aufgabe ausdrücklich bestätigt hat, dass genau das identifizierte Testziel im Wartungsfenster beschrieben werden darf.
7. Wenn die Anlage aktiv heizt/kühlt, eine Automatik läuft, die Variable nicht eindeutig physisch wirkt oder kein sicherer Rückkehrwert bekannt ist: nicht schreiben, nur vorbereiten.

## Vorbereitungsphase (immer ausführen)

Ermittle und dokumentiere:

- PROXON-Gerät, Modell/Firmware soweit in Symcon sichtbar, Objektpfad und Objekt-ID,
- Modbus-Gateway/I/O, Verbindungstyp, Slave-Adresse und relevante Parent-/Child-Instanzen,
- Kandidat Lüfterstufe: Variable-ID, Pfad, Typ, Profil, aktueller Roh-/Engineeringwert, zulässige Werte, Zeitstempel, `HasAction` beziehungsweise Aktionsmerkmal,
- Kandidat Betriebsart nur als Fallback,
- ob die Variable ein bestätigter Istwert, ein Sollwert oder nur eine lokale Hilfsvariable ist,
- ob ein aktueller Wert und ein sicherer Rückkehrwert vorhanden sind,
- ob bestehende Skripte/Ereignisse oder andere Schreiber erkennbar sind.

Prüfe den FWT-Registerkatalog nur als Plausibilitätsreferenz. Übernimm keine Adresse aus dem Katalog, wenn sie nicht der aktuellen Symcon-Instanz und dem aktuellen Geräteprofil zugeordnet werden kann.

## Empfohlener Testmodus A: gleicher Rohwert

Wenn der Nutzer den Schreibtest ausdrücklich freigibt und eine eindeutige, physisch steuerbare Lüfterstufen-Aktion vorhanden ist:

1. Lies den aktuellen Wert unmittelbar vor dem Test erneut.
2. Prüfe, dass der Wert im gültigen Bereich liegt und dass `Status emulieren` beziehungsweise eine lokale Sollwertsimulation deaktiviert ist.
3. Sende mit `symcon_request_action` exakt denselben gültigen Roh-/Aktionswert zurück.
4. Lies die Variable und, falls vorhanden, den getrennten bestätigten Istwert erneut.
5. Warte nur im Rahmen eines kurzen, definierten Read-back-Zeitfensters; keine langen Sleep-Ketten und keine unbegrenzten Wiederholungen.
6. Führe höchstens drei identische Versuche aus und stoppe bei Timeout, Exception, Wertabweichung oder unerwarteter Anlagenreaktion.

Dieser Modus soll den Zustand unverändert lassen. Er beweist eine Geräteantwort und einen Read-back, aber ohne Telegramm-Mitschnitt nicht sicher den auf dem Draht verwendeten Funktionscode.

## Optionaler Testmodus B: sichtbar um eine Stufe ändern

Nur wenn der Nutzer dies ausdrücklich zusätzlich bestätigt:

1. Lies und speichere den ursprünglichen Lüfterwert.
2. Wähle genau eine benachbarte, gültige Lüfterstufe.
3. Sende genau einen Befehl.
4. Prüfe bestätigten Istwert und beobachtbare Änderung.
5. Stelle unmittelbar den gespeicherten ursprünglichen Wert wieder her.
6. Lies den Rückkehrwert erneut und stoppe bei jeder Abweichung.

Diesen Modus nicht verwenden, wenn das Gerät in einer Schutz-, Abtau-, Fehler- oder sicherheitskritischen Betriebsphase ist.

## FC06-Nachweis und MCP-Grenze

Prüfe, ob die verwendete Symcon-Instanz oder ein vorhandenes Gateway den tatsächlich gesendeten Funktionscode und die PDU-Adresse protokolliert. Falls nicht:

- nicht behaupten, FC06 sei bewiesen,
- Ergebnis als „Geräteaktion und FC03-Read-back erfolgreich; Wire-Level-FC06 offen“ kennzeichnen,
- für den abschließenden Nachweis einen Modbus-TCP-Mitschnitt oder RS485-Analysator im Wartungsfenster empfehlen,
- niemals zum Beweis testweise FC16 an die produktive Anlage senden.

## Ergebnisformat

Gib einen kompakten Prüfbericht zurück:

1. Gerät, Profil/Firmware, Gateway, Slave und Zielvariable mit Pfad/ID,
2. Testmodus A oder B,
3. Ausgangswert, gesendeter Wert, Read-back-Wert und Zeitstempel,
4. bestätigte Geräteaktion oder Abbruchgrund,
5. erkannter oder nicht erkannter Funktionscode,
6. Risiken, offene Punkte und nächster sicherer Schritt.

Bei jedem Abbruch klar sagen: „Kein Schreibbefehl ausgeführt.“

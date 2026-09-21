# G2-Testevidenz: PROXON FWT Lüfterstufe

## Ergebnis

Vorbereitung abgeschlossen, Test sicher abgebrochen. Es wurde kein Schreibbefehl ausgeführt.

## Verifizierte Konfiguration

| Merkmal | Ergebnis |
|---|---|
| FWT-Instanz | Technik/Proxon/FWT, ID 37437, aktiv |
| Symcon | Kernel 9.1 |
| Gateway | ModBus Gateway, ID 45775 |
| Verbindung | Serial Port Proxon COM7, ID 47843, 19200 Baud, 8E1, geöffnet |
| Slave | 41 |
| Zielvariable | `Technik/Proxon/FWT/FWT_Lufterstufe`, ID 41635 |
| Typ | Integer |
| Wertebereich | 1–4 |
| Lesen | FC03, Adresse 22 |
| Schreiben | FC06, Adresse 22 |
| aktueller Wert | 3 |
| Zuluft/Abluft | jeweils 3 |
| Aktionsfähigkeit | `VariableAction=37437`, Modbus-Zuordnung aktiv |

## Abbruchgrund

In der FWT-Instanz war `EmulateStatus=true` aktiv. Ein Schreibtest hätte damit einen lokalen Erfolg vortäuschen können, ohne dass ein physischer Geräte-Read-back nachgewiesen ist. Die normalen Symcon-Logs enthalten außerdem keine Telegramme mit Funktionscode und PDU-Adresse.

## Bewertung FC06 und FC16

Für die einzelne 16-Bit-Lüfterstufe ist FC06 der fachlich passende Testpfad: ein Holding-Register wird einzeln geschrieben. FC16 wird für diesen Test nicht benötigt und soll nicht zusätzlich ausprobiert werden.

FC16 bleibt nur in diesen Fällen relevant:

- ein bestimmtes Symcon-Backend unterstützt für diesen Gerätetyp ausschließlich FC16,
- das Zielgerät akzeptiert FC06 nicht, aber ausdrücklich FC16,
- mehrere zusammenhängende Register müssen als Block geschrieben werden und das Gerät unterstützt diesen Ablauf,
- ein zukünftiger Mehrregister-/Wochenplanpfad wird implementiert.

Das ist keine Freigabe für FC16 an der FWT. Ein falscher Funktionscode kann abgewiesen, ignoriert oder je nach Gerät unerwartet behandelt werden.

## Nächster sicherer Schritt

1. `EmulateStatus` an der FWT-Instanz deaktivieren.
2. Konfiguration speichern und den aktuellen Wert frisch per FC03 einlesen.
3. Den identischen Wert 3 nur nach erneuter ausdrücklicher Freigabe per `symcon_request_action` senden.
4. Für den Wire-Level-Nachweis einen RS485-Analysator einsetzen. Ein Modbus-TCP-Mitschnitt ist nur relevant, wenn ein TCP/RTU-Gateway zwischengeschaltet wird.
5. FC06, PDU-Adresse 22, Rohwert 3, Geräteantwort und anschließenden Read-back dokumentieren.

Ohne Telegramm-Mitschnitt darf das Ergebnis höchstens lauten: „Geräteaktion und Read-back erfolgreich; FC06 auf Wire-Level nicht bestätigt.“

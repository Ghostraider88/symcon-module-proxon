# Geltungskorrektur: Greenfield-first statt installationsspezifischer Entwicklung

## Betreiberentscheidung

Die konkrete Symcon-Anlage des Betreibers ist **nicht** die Zielarchitektur des Moduls. Sie dient als optionale Referenz-, Test- und Migrationsumgebung. Das Modul muss allgemein für neue Installationen nutzbar sein und darf nicht von den vorhandenen Objekt-IDs, Pfaden, Skripten, Ereignissen oder Schreibern abhängen.

## Produktprinzip

Greenfield ist der primäre Installationsweg:

- Configurator und Modulbasis erzeugen eine saubere neue Struktur.
- Registerprofile, Slave, Gateway und Polling werden über Konfiguration bestimmt.
- Keine feste ID aus der Betreiberanlage wird in Modulcode, Katalog oder Standardkonfiguration übernommen.
- Keine bestehende Automation wird vorausgesetzt.
- Neue Schreiber gehören ausschließlich zur neuen Modulinstanz und werden zentral arbitriert.

Bestandsmapping ist ein optionaler zweiter Weg:

- vorhandene Variablen können ausdrücklich referenziert werden,
- fehlende Datenpunkte können optional ergänzt werden,
- vorhandene Schreiber werden nur angezeigt und erst nach bewusstem Cutover ersetzt,
- keine automatische Übernahme, Löschung oder Umschaltung fremder Objekte.

## Konsequenz für den Inventarbericht

Der Read-only-Inventarbericht der Betreiberanlage bleibt wertvoll für:

- reale Register- und Geräteerfahrungen,
- plausible Wertebereiche und Aktualisierungsraten,
- Erkennung typischer Komfortfunktionen,
- Migrations- und Regressionstests,
- praktische Hinweise zu vorhandenen Schreibern.

Er ist jedoch keine verbindliche Objektstruktur für das Modul. Die dort gefundenen IDs und Pfade dürfen nicht als Standardwerte übernommen werden.

## Konsequenz für Entwicklung und Tests

1. Entwicklung und Unit-Tests erfolgen mit simuliertem Provider und neutraler Greenfield-Konfiguration.
2. Danach folgt eine saubere neue Symcon-Testinstallation ohne alte Schreiber.
3. Erst anschließend wird optional der Bestandsadapter gegen die Betreiberanlage geprüft.
4. Produktentscheidungen werden aus Registerprofilen, Symcon-Basis und allgemeiner Gerätekompatibilität abgeleitet, nicht aus einer einzelnen Hausinstallation.

## Statusänderung

- G5 ist für den Greenfield-MVP kein Blocker.
- G5 bleibt nur für den optionalen Bestandsadapter und den späteren Migrationstest relevant.
- G7 wird korrigiert: Greenfield und Bestandsmapping sind gleichwertig unterstützte Installationswege; keiner wird aus der Betreiberanlage als allgemeiner Standard abgeleitet.

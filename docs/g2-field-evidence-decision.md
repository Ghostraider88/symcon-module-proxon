# G2-Entscheidung: FC06 durch Langzeitbetrieb bestätigt

## Betreiber-Evidenz

Die PROXON-Anlage läuft beim Betreiber seit fast zehn Jahren stabil über Modbus mit FC06. Damit liegt ein wesentlich stärkerer Praxisnachweis vor als ein einmaliger Labor-Mitschnitt. Für die konkrete Anlage und den bestehenden Kommunikationsweg wird FC06 als praktisch verifiziert behandelt.

## Konsequenz

- Kein zusätzlicher RS485-Analysator erforderlich.
- Kein FC16-Test an der produktiven Anlage.
- Der PROXON-Schreibpfad bleibt auf FC06 ausgelegt.
- FC16 wird nicht als allgemeine Fallback-Strategie für diese FWT eingeführt.
- FC16 bleibt nur für andere Geräte, andere Symcon-Backends oder spätere Mehrregisterfunktionen eine mögliche separate Prüfung.

## Einmalige Sicherheitsmaßnahme vor dem ersten MCP-Test

`EmulateStatus` muss vor dem Test deaktiviert werden, damit ein lokaler Symcon-Erfolg nicht mit einem bestätigten Gerätezustand verwechselt wird. Danach genügt für den ersten Test:

1. aktuellen Wert der Lüfterstufe per FC03 lesen,
2. identischen Wert `3 → 3` nur nach ausdrücklicher Freigabe senden,
3. FC03-Read-back prüfen,
4. Ergebnis und Zeitstempel dokumentieren.

Der Test bestätigt dann die aktuelle MCP-/Symcon-Zuordnung, ist aber keine notwendige erneute Protokollgrundsatzprüfung. Eine sichtbare Änderung der Lüfterstufe ist nicht erforderlich.

## Status

G2 ist für die Architekturentscheidung geschlossen: **FC06 verwenden, FC16 nicht erforderlich.** Offen bleibt nur die sichere Konfigurationsprüfung ohne `EmulateStatus` und der einmalige Read-back-Test.

# Repository- und Upstream-Strategie

## Verbindliche Entscheidung

Die Entwicklung erfolgt ausschließlich in unserem eigenen Repository. Der `master`-Branch von Symcon dient als freigegebene Quellbasis und wird in unser Repository übernommen.

Wir arbeiten nicht im Symcon-Repository und committen keine Änderungen in den Symcon-Branch.

## Ablauf

1. Den freigegebenen Stand des Symcon-`master` identifizieren und den Commit dokumentieren.
2. Die benötigten Dateien und Strukturen in unser Repository kopieren beziehungsweise übernehmen.
3. PROXON-Erweiterungen, T300-Unterstützung, Profile, Diagnose und Tests ausschließlich in unserem Repository entwickeln.
4. Herkunft und Upstream-Commit in einer Quellen-/Versionsdatei festhalten.
5. Spätere Updates vom Symcon-`master` nur kontrolliert und bewusst übernehmen.
6. Nach jeder Synchronisation Regressionstests für bestehende FWT-Funktionen ausführen.

## Grenzen

- Unser Repository besitzt seine eigene Commit-Historie und Releaseplanung.
- Symcon-Dateien werden bei der Übernahme nicht stillschweigend inhaltlich verändert; Erweiterungen bleiben nachvollziehbar.
- Konflikte zwischen Upstream-Änderungen und unseren Erweiterungen werden manuell geprüft.
- Bestehende GUIDs und Strukturen der freigegebenen Basis bleiben erhalten, sofern die Freigabebedingungen nichts anderes verlangen.
- T300 und weitere PROXON-Erweiterungen werden als unsere Erweiterungen auf der übernommenen Basis gepflegt.

## Aktueller Startpunkt

Vor dem ersten Coding-Lauf wird der aktuelle Symcon-`master`-Commit als `upstreamBaseCommit` dokumentiert. Danach beginnt die Entwicklung in unserem Repository auf einem eigenen Arbeitsbranch.

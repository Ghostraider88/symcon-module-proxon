# Freigabe zur Nutzung der Symcon-Modulbasis

## Betreiberentscheidung

Für dieses Projekt liegt die Freigabe von Symcon vor, die bestehende Symcon-PROXON-Modulbasis als Grundlage zu verwenden und darauf aufzubauen.

Damit wird die bisher offene G1-Entscheidung geschlossen:

- keine Clean-room-Neuentwicklung als Ausgangspunkt,
- bestehende Modulstruktur und bewährte Symcon-Integration werden als Basis betrachtet,
- Erweiterungen für T300, Qualitätsmodell, Read-back, Bestandsmapping und Komfortfunktionen werden darauf aufgebaut,
- die konkreten Bedingungen der Freigabe bleiben für Veröffentlichung, Repository und Lizenztexte maßgeblich.

## Umsetzungsvorgaben

1. Vorhandene Modul-GUIDs, Klassen- und Identstrukturen werden nicht ohne Prüfung geändert.
2. Erweiterungen erfolgen kompatibel zur bestehenden Basis und mit nachvollziehbaren Migrationen.
3. Änderungen an bestehendem Verhalten werden mit Regressionstests abgesichert.
4. Die Freigabe wird bei öffentlichen Releases, Repository-Metadaten und README-/Lizenztexten berücksichtigt.
5. Die Bestandsbasis wird zuerst gelesen und verstanden; neue Funktionen werden nicht parallel als konkurrierende Kommunikationsschicht implementiert.

## Abgrenzung

Die Freigabe zur Nutzung der Modulbasis ersetzt nicht die technische Prüfung der konkreten FWT-/T300-Registerprofile. FC06 bleibt der bestätigte FWT-Schreibpfad. G5, also die Bestandsaufnahme der konkreten Symcon-Anlage, bleibt vor einer Migration erforderlich.

## Status

**G1 geschlossen – Symcon-Modulbasis darf als Grundlage verwendet werden.**

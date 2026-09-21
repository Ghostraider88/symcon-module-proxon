# PROXON-Erweiterung für IP-Symcon

[![Check Style](https://github.com/DEIN-USER/DEIN-REPO/actions/workflows/style.yml/badge.svg)](https://github.com/DEIN-USER/DEIN-REPO/actions/workflows/style.yml)
[![Run Tests](https://github.com/DEIN-USER/DEIN-REPO/actions/workflows/tests.yml/badge.svg)](https://github.com/DEIN-USER/DEIN-REPO/actions/workflows/tests.yml)

Dieses Repository baut mit vorliegender Freigabe auf der bestehenden Symcon-PROXON-Modulbasis auf. Die Produktentwicklung ist Greenfield-first und verwendet ausschließlich Symcons eigene Modbus-Infrastruktur.

## Verbindlicher Produktumfang

- FWT 2.0 und FWT 3.0,
- T300 ausschließlich über die FWT-Topologie,
- kein direkter T300-Installationsweg,
- kein eigener Modbus-Stack,
- Greenfield-Configurator darf Symcon-Modbus-Gateway, Geräte/Adressen und logische Instanzen anlegen,
- Bestandsmapping als optionaler Adapter,
- read-only zuerst, spätere FWT-Schreibaktionen über FC06.

Die verbindliche Auslegung steht in [docs/decision-baseline-2026-09-19.md](docs/decision-baseline-2026-09-19.md). Die Symcon-Freigabe zur Nutzung der Modulbasis ist in [docs/symcon-basis-approval.md](docs/symcon-basis-approval.md) dokumentiert.

## Basisrepository

<https://github.com/symcon/Proxon>

Der konkrete Branch/Commit wird zu Beginn der Implementierung dokumentiert. Bestehende GUIDs und tragende Strukturen werden nicht ungeprüft geändert.

## Installationswege

### Greenfield

Das Modul richtet die Symcon-eigene Modbus-Struktur und die PROXON-Instanzen neu ein. Es benötigt keine Objekt-IDs, Pfade, Skripte oder Schreiber aus einer bestehenden Hausinstallation.

### Bestandsmapping

Vorhandene Symcon-Modbus-Instanzen und Variablen können ausdrücklich referenziert werden. Fremde Schreiber, Archive und Visualisierungen werden nicht automatisch übernommen, gelöscht oder umgeschaltet.

## Planungsstand

Die technische Projektbasis, Quellenarchive, Registerkataloge, Teststrategie und Architektur befinden sich in [docs/README.md](docs/README.md). Die erste Implementierungsstufe bleibt read-only.

## Voraussetzungen

- IP-Symcon 9.0 als Planungsbasis,
- Symcon-eigene Modbus-Infrastruktur,
- verifiziertes FWT-2.0-/FWT-3.0-Profil,
- T300 hinter einer FWT-Topologie,
- keine feste Bindung an eine Einzelinstallation.

## Visualisierung und Entwicklung

Die Erweiterung verwendet native Symcon-Variablen und Kacheln. Zusammengesetzte HTML-Kacheln bleiben auf FWT-Übersicht, T300 und optionale Diagnose begrenzt. Greenfield-Simulation und eine saubere neue Symcon-Testinstallation sind die primären Entwicklungsstufen.

## Quellen und Rechte

Originale Excel-/PDF-Quellen liegen ausschließlich als Entwicklungsquellen unter [docs/source](docs/source/README.md). Veröffentlichungen müssen die Symcon-Freigabe sowie Hersteller-, Marken- und Quellenbedingungen beachten.

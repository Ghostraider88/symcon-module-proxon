# Architekturentscheidungen

## ADR-001 – Fachmodule hinter einem logischen Gateway

**Status:** angenommen für die Planung.

**Entscheidung:** Configurator, logisches Gateway, FWT- und T300-Gerätemodul. Der Broker zentralisiert Mapping, Qualität, Queue und Konfliktauflösung.

**Warum:** Mehrere Fachinstanzen dürfen nicht unabhängig auf denselben Bus schreiben. Das Gateway schafft einen testbaren Vertrag und unterstützt sowohl native Modbus- als auch Bestandsvariablen-Provider.

**Verworfen:** Ein monolithisches Modul vermischt Bus, Gerätelogik und UI; vollständig autonome Gerätemodule vervielfachen Arbitration und Diagnose.

## ADR-002 – Kein eigener Modbus-Stack im MVP

**Status:** angenommen mit Gate G2.

**Entscheidung:** Vorhandene Symcon-Infrastruktur nutzen. Ein FC06-fähiger Zusatztransport wird nur erwogen, wenn der Hardwaretest die native Schreibkompatibilität widerlegt.

**Warum:** Weniger parallele Verbindungen, geringeres Betriebsrisiko und bessere Einfügung in Symcon.

## ADR-003 – Registerraum aus Tabellenkontext

**Status:** angenommen.

**Entscheidung:** Tabellenreiter/PDF-Kapitel bestimmen Input oder Holding; der numerische Suffix ist die nullbasierte Adresse. Quellpräfixe werden unverändert archiviert, aber wegen nachgewiesener Vertauschung nicht zur Kommunikation verwendet.

## ADR-004 – PDF priorisiert, Excel bleibt vollständig erhalten

**Status:** angenommen.

**Entscheidung:** Bei überlappenden Registern gilt die aktuelle Hersteller-PDF als stärkere fachliche Evidenz. Excel-only Register bleiben als `internal_unverified` verfügbar. Keine Quelle wird überschrieben oder verworfen.

## ADR-005 – Read-only zuerst

**Status:** angenommen.

**Entscheidung:** Erste Feldstufe liest, normalisiert, prüft und visualisiert. Schreibfunktionen werden erst registerweise nach FC06/FC16-, Wertebereichs- und Read-back-Test aktiviert.

## ADR-006 – Native Symcon-Kacheln

**Status:** angenommen.

**Entscheidung:** Einzelne Kacheln statt eigener Dashboard-Hülle. Das Anlagenbild ist eine optionale große HTML-SDK-Kachel; Standardwerte und Aktionen bleiben native Variablen/Profile.

**Verworfen:** Eine nachgebaute App-Navigation würde sich schlecht in bestehende Visualisierungen einfügen und mobile Bedienung duplizieren.

## ADR-007 – Zonen zunächst innerhalb FWT

**Status:** angenommen, später überprüfbar.

**Entscheidung:** Dynamische Zonenobjekte unter FWT. Ein eigenes Zonenmodul entsteht nur bei nachgewiesenem Bedarf an eigener Konfiguration, Lifecycle oder sehr großer Variablenmenge.

## ADR-008 – Mindestversion 8.1 als Planungsbasis

**Status:** vorgeschlagen; Gate G3 offen.

**Entscheidung:** `IPSModuleStrict`, HTML-SDK und Visualisierungstyp 1. APIs mit höherer Mindestversion werden vermieden, solange der Betreiber 9.1+ nicht verbindlich festlegt.

## ADR-009 – Qualität ist Teil jedes Werts

**Status:** angenommen.

**Entscheidung:** Neben dem Wert werden Quelle, Messzeit, Empfangszeit, Gültigkeit und Fehlergrund geführt. Alte oder widersprüchliche Daten erscheinen nicht als aktueller Anlagenzustand.

## ADR-010 – Komfortautomation als zentraler Intent

**Status:** angenommen.

**Entscheidung:** Boost, Zeitplan, PV-Optimierung und Schutzfunktionen erzeugen priorisierte, zeitlich begrenzte Intents. Sie schreiben nicht direkt auf Modbus-Variablen.

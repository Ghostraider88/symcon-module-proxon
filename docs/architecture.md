# Zielarchitektur

Stand: 18.09.2026. Die Architektur ist eine geprüfte Planungsgrundlage, noch keine Implementierung.

## Modulzuschnitt

| Modul | Symcon-Typ | Verantwortung |
|---|---:|---|
| PROXON Configurator | 4 | Verbindung wählen, Geräte/Quellen erkennen, Vorschau und Instanzen erzeugen |
| PROXON Gateway | 2 | Broker für Mapping, Qualität, Polling-Eigentum, Schreibqueue und Arbitration |
| PROXON FWT | 3 | Fachmodell für Zentrale, Lüftung, Wärmepumpe und dynamische Zonen |
| PROXON T300 | 3 | Fachmodell für Warmwasser-Wärmepumpe |

Ein separates Zonenmodul ist im MVP nicht vorgesehen. Zonen bleiben dynamische Unterobjekte der FWT-Instanz, bis reale Anlagen einen eigenen Lifecycle oder Konfigurationsbedarf belegen.

## Schichten

```text
ausgewählter Transport/Bestandsquelle (genau einer je Register)
                              |
                       Provider-Adapter
                              |
                   PROXON Gateway/Broker
       Mapping · Qualität · Scheduler · Queue · Audit
                        /             \
                  FWT-Domain       T300-Domain
                       |               |
           native Variablen/Profile + wenige HTML-Kacheln
```

Kommunikation, Katalognormalisierung, Fachzustand und Darstellung sind getrennt testbar. Fachcode enthält keine versteckten Registeradressen. Unbekannte Enumwerte werden als `Unbekannt (Rohwert)` angezeigt.

## Genau ein Transport- und Polling-Eigentümer

| Backend | Lesen | Aktualisierung | Schreiben |
|---|---|---|---|
| Vom Modul angelegte native Adresse | native Variable | Variablen-Nachricht | nur nach FC06-Nachweis des konkreten Backendtyps |
| Vorhandene Infrastruktur | explizit referenzierte Variable | Variablen-Nachricht | nur bestätigte Aktionsvariable und Betreiberfreigabe |
| Direkter Modbus-Parent | Gateway liest FC03/FC04 | zentraler Gateway-Scheduler | FC06 nach Hardware-/Telegrammtest |

Ein Register darf nie gleichzeitig nativ gepollt und direkt abgefragt werden. Der konkrete Quellschlüssel umfasst Gateway/Verbindung, Instanztyp, Slave, Registerraum, Funktionscode, Adresse, Breite, Vorzeichen, Byte-/Wortreihenfolge, Skalierung und Datenalter.

## Schreibpfad

Jeder Schreibwunsch ist ein Command Intent. Das Gateway prüft Freigabe, verifiziertes Geräteprofil, Wertebereich, inverse Skalierung/Rundung, Datenalter, Schutzregeln, Priorität, Cooldown und Wiederholungsgrenze. Anschließend folgen genau ein erlaubter Schreibvorgang und FC03-Read-back. Ein Befehl bleibt bis zur Bestätigung `pending` und wird bei Timeout nicht als Istzustand dargestellt.

Priorität: Schutz/Sicherheit, manueller Nutzerbefehl, zeitlich begrenzter Boost, Komfortautomation, Optimierung. Overrides speichern ein absolutes `expiresAt`; abgelaufene Intents werden nach Neustart nie erneut gesendet.

### FC06-Gate

Die Herstellerunterlagen verlangen FC06. Die öffentliche Dokumentation des tabellarischen Symcon-„ModBus Gerät“ weist Holding-Schreiben als FC16 aus; das öffentliche `symcon/Proxon` sendet FC06 direkt an den Parent. Daher ist noch kein Schreibbackend freigegeben.

Zulässige Kandidaten sind:

1. direkter Parent-Datenfluss mit FC06,
2. einzelne native „ModBus Adresse“ mit nachgewiesenem FC06,
3. tabellarisches „ModBus Gerät“ nur, falls die festgelegte Zielversion FC06 je Eintrag tatsächlich unterstützt.

`Status emulieren` bleibt aus. FC16 wird nicht probeweise an produktiver Hardware gesendet. Der Labortest dokumentiert Telegramm, PDU-Adresse, Rohwert und anschließenden Read-back.

## Registerprofile und Quellenkonflikte

XLSX und PDF werden parallel gehalten. Ein Profil ist mindestens an Serie, Firmware, Anschlussvariante und Katalogversion gebunden. Konflikte wie Register 16 und 187 erhalten erst nach Verifikation einen `resolved_value`. Unbekannte Firmware bleibt read-only/degraded; das Alter einer Quelle allein erteilt keine Schreibfreigabe.

Mehrwortwerte verwenden mathematisch `low + high * 65536`; Datentyp und Verhalten auf der Zielplattform werden explizit getestet. Register 438 wird ausschließlich gelesen und kann die modulseitige Schreibfreigabe niemals selbst aktivieren.

## Kachelverantwortung

Eine Modulinstanz liefert höchstens ihre zusammengesetzte Fachkachel:

- FWT: Übersicht/Anlagenbild mit Vollbilddetails,
- T300: Warmwasserübersicht mit Vollbilddetails,
- Gateway: Diagnose, nur wenn aktiviert.

Einfache Temperaturen, Sollwerte, Schalter, Meldungen und Zonen werden als native Symcon-Variablen/Profile dargestellt und können dadurch einzeln im Kachelraster platziert werden. Es entstehen im MVP keine acht künstlichen Presentation-Instanzen. `SetVisualizationType(1)` ist die Basis für IP-Symcon 8.1; Typ 2 wird nur nach bewusster Mindestversionsanhebung und realem UX-Test genutzt. Keine eigene Sidebar oder App-Navigation.

## Discovery

Discovery ist read-only, auf ein explizit ausgewähltes Gateway begrenzt und ändert in Bestandsanlagen weder Baudrate noch Parität automatisch. Auf einem geteilten RTU-Bus ist ein Wartungsfenster erforderlich; laufende Fremdkommunikation führt zum Abbruch. Eine Erkennung ist erst nach mehreren plausiblen, wiederholten Antworten ein Vorschlag und keine automatische Schreibfreigabe.

## Lifecycle, Migration und Besitz

- `Create()` und `ApplyChanges()` sind idempotent; IDs und Archive bleiben stabil.
- Kernelstart, Reconnect, Profil- und Providerwechsel haben definierte Zustände.
- Das Modul führt persistent Buch über selbst erzeugte Objekte; fremde Infrastruktur wird nie ungefragt gelöscht.
- `Destroy()` und Deinstallation bereinigen nur eigene Objekte nach Vorschau und Abhängigkeitsprüfung.
- Vor Bestandsmigration: Snapshot aller Gateways, Instanzen, Properties, Skripte, Ereignisse, Aktionen, Archive und Verbraucher.
- Cutover erfolgt registerweise mit Alt-Schreiber, Neu-Schreiber, Abschaltpunkt, Test und Rollback. Abweichender Read-back ist nur Konfliktverdacht, kein sicherer Nachweis eines Fremdschreibers.
- Wochenpläne werden vollständig vorvalidiert, dann geordnet registerweise geschrieben und zurückgelesen; sie sind mit FC06 nicht atomar.

## Version, Sicherheit und Veröffentlichung

Planungsbasis ist mindestens IP-Symcon 8.1 mit `IPSModuleStrict`; das bleibt bis zur Betreiberentscheidung ein Gate. Keine Cloud-Zugangsdaten, keine Schreib-Discovery und keine automatische Archivaktivierung. Heizstab, Legionellenfunktion, hohe Warmwassertemperaturen, Neustart und Fehlerquittierung benötigen Expertenfreigabe.

Das aktuelle öffentliche Repository `symcon/Proxon` überlappt bei Central, Zone und Configurator, besitzt aber keine sichtbare LICENSE-Datei. Vor Coding ist daher Kooperation/Erweiterung oder eine unabhängige Clean-room-Implementierung zu entscheiden. Ohne Rechteklärung werden weder Code noch GUIDs oder Prefix übernommen.

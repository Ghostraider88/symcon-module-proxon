# Visualisierung im Symcon-Kacheldesign

## Leitlinie

Die Lösung fügt sich in das vorhandene IP-Symcon-Kachelraster ein. Sie baut keine zweite App-Navigation, Sidebar oder Dashboard-Hülle nach. Raster, Größe, Reihenfolge und Raumzuordnung bleiben beim Nutzer.

## Technische Aufteilung

- Einfache Temperaturen, Sollwerte, Schalter, Zustände und Meldungen nutzen native Symcon-Variablen und Profile. Sie sind direkt als einzelne Kacheln platzierbar.
- Die FWT-Instanz liefert eine zusammengesetzte Übersichts-/Anlagenbildkachel.
- Die T300-Instanz liefert eine zusammengesetzte Warmwasserkachel.
- Das Gateway kann optional eine Diagnosekachel liefern.
- Zonen werden als native Unterobjekte der FWT dargestellt; keine künstliche Präsentationsinstanz je Kachel im MVP.

Damit bleibt die Kachelverantwortung mit `GetVisualizationTile()` eindeutig. Basis ist `SetVisualizationType(1)` für IP-Symcon 8.1; Typ 2 kommt nur bei festgelegter höherer Mindestversion und nach UX-Test infrage.

## Inhaltliche Kacheln

| Inhalt | Darstellung | Kerninformation |
|---|---|---|
| Anlagenstatus | native Statusvariable oder FWT-Übersicht | bereit/gestört, Modus, letzter gültiger Kontakt |
| Temperaturen | native Variablen | Außen-, Zu-, Ab- und Fortluft mit Datenalter |
| Lüftung | native Profile | Iststufe, Sollstufe, Pending und Sperrgrund |
| Zonen | native Unterobjekte | Soll/Ist und Anforderung je belegter Zone |
| T300 | zusammengesetzte T300-Kachel plus native Werte | Tanktemperaturen, Sollwert, Relais, Betriebsart |
| Meldungen | native Liste/Status | Fehler, Warnungen und Wartung |
| Diagnose | optionale Gateway-Kachel | Verbindung, Slave, Antwortzeit, Profil/Katalog |

## Zustands- und Bedienregeln

- Zustand nie ausschließlich durch Farbe ausdrücken.
- Aktiv, Warnung, Fehler, veraltet und unbekannt erhalten Text, Symbol und Zeitpunkt.
- Istwert, Sollwert und ausstehender Befehl bleiben getrennt.
- Jede temporäre Aktion zeigt Endzeit und Rückkehrzustand.
- Kritische Aktionen nennen die Wirkung und verlangen Bestätigung.
- Nicht belegte Funktionen sind verborgen oder mit Grund deaktiviert.
- Ohne Archiv degradiert eine Trendansicht kontrolliert und behauptet keine Historie.

## Anlagenbild

Das responsive HTML-SDK-Element zeigt nur nachgewiesene Komponenten: Außenluft → FWT → Zuluft/Zonen sowie Abluft → FWT → Fortluft. Wärmepumpe, Bypass und Heiz-/Kühlzustand erscheinen nur mit belegter Semantik. Die T300 ist eine separate Warmwasser-Komponente. Eine Verbindung oder thermische Abhängigkeit zur FWT wird erst nach belegter realer Topologie gezeichnet.

## Mockups

- [Desktop-Konzept](mockups/proxon-dashboard-desktop-concept.png)
- [Mobil-Konzept](mockups/proxon-dashboard-mobile-concept.png)

Die Bilder sind ausschließlich Studien der Informationshierarchie. Dashboard-Rahmen, Navigation und Anordnung werden nicht implementiert. Alle sichtbaren Werte, Räume und Zustände sind erfunden. Auch die dortige räumliche Nähe von FWT und T300 belegt keine technische Verbindung. Für die Umsetzung werden die Inhalte in native Variablenkacheln und die drei klar verantworteten zusammengesetzten Kacheln zerlegt.

## Abnahme

- Hell/Dunkel sowie klein/mittel/breit in echter Symcon-Visualisierung,
- Desktop und Mobilgerät,
- normal, pending, stale, offline, Warnung und Fehler,
- Touch-Bedienung, Kontrast und Textskalierung,
- keine simulierten Werte oder unbelegten Kennzahlen in Produktion.

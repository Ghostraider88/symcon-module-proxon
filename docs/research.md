# Markt- und Quellenrecherche

Recherchezeitpunkt: 18.09.2026. Herstellerfakten, offizielle Symcon-Informationen, Community-Erfahrungen und Produktvorschläge werden getrennt behandelt.

## Hersteller und Produkt

- [Zimmermann/PROXON](https://www.zimmermann-lueftung.de/) beschreibt FWT 3.0 als Premium- und P 3.0 als Basissystem. Die lokale Produktbroschüre belegt unter anderem R290, Varianten, optionale Kühlung, Cooling-Boost, zwei FWT-Zonen, Feuchte-/CO2-Optionen, GLT-Schnittstellen sowie T300 mit Boost/PV.
- Die [Bedienungsanleitung FWT/T300 2.1](https://www.zimmermann-lueftung.de/fileadmin/user_upload/downloads/2023/20230919-ZIM-13376-Bedienungsanleitung-FWT-Serie-T300_2.1-AK2.pdf) belegt Modi, Lüfterstufen, Zeitprogramme, Nachtabsenkung, Intensivlüftung, Filter-/Fehleranzeige und T300-Funktionen.
- Die [Nutzungsbedingungen der HomeControl-App](https://www.zimmermann-lueftung.de/nutzungsbedingungen-app) nennen Betriebsmodus, Räume, Wärmeelement/Kühlfreigabe, Warmwasser, Lüfterstufe, CO2/Feuchte und Zeitprogramme. Cloud-Verfügbarkeit ist nicht garantiert; Reverse Engineering der App ist untersagt.
- Ziel dieses Projekts ist ausschließlich die dokumentierte lokale Schnittstelle, keine Nachbildung der Hersteller-Cloud.

## IP-Symcon und bestehendes PROXON-Projekt

- Die [offizielle Modbus-Referenz](https://www.symcon.de/de/service/dokumentation/modulreferenz/geraete/modbus-rtu-tcp/) beschreibt Modbus RTU/TCP, native Adressen/Geräte, Datentypen und Funktionscodes. Für schreibende Holding-Einträge des tabellarischen ModBus-Geräts ist FC16 dokumentiert; dies kollidiert mit der FC06-Forderung der Herstellerunterlage.
- [Module](https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/module/), [Datenfluss](https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/datenfluss/) und [Configurator](https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/konfigurationsformulare/configurator/) belegen Instanztypen und Parent-/Child-Struktur.
- Das öffentliche Repository [symcon/Proxon](https://github.com/symcon/Proxon) ist ein aktuelles Produkt-Gate: Bibliotheksversion 1.1, letzter festgestellter Commit 05.08.2026, Module für Proxon 1 Panel sowie Proxon 2 Central, Configurator und Zone. Es kommuniziert direkt per FC03/FC04/FC06 mit dem Parent.
- Im geprüften Repository war keine `LICENSE`-Datei sichtbar. Ohne Klärung werden weder Code, GUIDs noch Prefix übernommen. Zu entscheiden ist offizielle Erweiterung/Mitarbeit oder eine unabhängige Clean-room-Implementierung.
- Festgestellte Lücken des öffentlichen Projekts sind insbesondere T300, explizites Qualitätsmodell, bestätigender Read-back und zentrale Komfortarbitration. Diese Aussage ist eine Code-/Funktionsanalyse zum Recherchestand, keine Zusage über die Roadmap von Symcon.

## Community-Erfahrungen

- [Home Assistant Community](https://community.home-assistant.io/t/add-proxon-heating-system-to-ha-via-modbus/585674): manuelle Registerlisten sowie Adressbasis-, Paritäts-, Schreibfreigabe- und Timeoutprobleme. Erfahrungsquelle, keine Herstellergarantie.
- [steuerlexi/proxon-t300-esphome](https://github.com/steuerlexi/proxon-t300-esphome): T300-Sensoren/Aktionen und Hinweise zu Offset, RS485 und CRC. Fremdcode wird ohne Lizenzprüfung nicht übernommen.
- [zathras777/t300](https://github.com/zathras777/t300): direkte T300/X17-Anbindung; Slave 20 ist nur ein Community-Hinweis.
- [m4dmin/proxon-control](https://github.com/m4dmin/proxon-control): Python/MQTT für FWT/T300 als Funktionsvergleich, nicht als Codequelle.
- [ioBroker Adapter Request](https://github.com/ioBroker/AdapterRequests/issues/223), [FHEM-Forum](https://forum.fhem.de/index.php?topic=56013.15) und [openHAB Modbus Binding](https://www.openhab.org/addons/bindings/modbus/) zeigen, dass generische Integration möglich, aber häufig manuell und diagnosearm ist.
- [myGEKKO PROXON](https://wiki.my-gekko.com/de/media/manual_proxon_fwt_serie_2.0_pdf_original.pdf) dient als Praxisreferenz für Räume, Modi und Luftqualität; daraus entsteht keine Registerfreigabe.

## Produktpositionierung

Der mögliche Mehrwert liegt nicht in einer weiteren Rohregisterliste, sondern in lokaler Einrichtung, serien-/firmwarebezogenen Profilen, sicherem FC06-Schreibpfad, Bestandsmapping, Datenqualität, T300-Abdeckung, erklärbarer Diagnose und zentraler Komfortarbitration. Überschneidet sich dies mit der Roadmap von `symcon/Proxon`, ist Kooperation fachlich vorzuziehen.

## Recht und Veröffentlichung

- Keine Herstellerlogos, App-Screenshots oder vollständigen Originaltabellen ohne Freigabe.
- Interoperabilitätsdaten eigenständig normalisieren und Beschreibungen neu formulieren.
- PROXON/Zimmermann als Marken behandeln und ohne Kooperation den inoffiziellen Charakter nennen.
- Fremdcode ohne eindeutige Lizenz nicht kopieren.
- Original-PDF/XLSX sind Entwicklungsquellen und nicht automatisch Releasebestandteil.

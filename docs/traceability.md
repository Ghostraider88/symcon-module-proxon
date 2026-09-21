# Rückverfolgbarkeit

| Anforderung | Quelle | Architektur/Design | Verifikation |
|---|---|---|---|
| REQ-F-001 FWT lesen | FWT XLSX, GLT-PDF | Gateway, FWT-Modul, Katalog | T-MAP-01, T-SIM-01, T-HW-RO-01 |
| REQ-F-002 T300 lesen | T300 XLSX, GLT-PDF | Gateway, T300-Modul, Katalog | T-MAP-02, T-SIM-02, T-HW-RO-02 |
| REQ-F-003 Zonen dynamisch | XLSX, Broschüre | FWT-Unterobjekte, Configurator | T-ZONE-01..03 |
| REQ-F-004 Datenqualität | Projektauftrag | Gateway-Qualitätsmodell | T-QUAL-01..06 |
| REQ-F-005 sichere Aktionen | Projektauftrag, GLT-PDF | Intent, Queue, Read-back | T-WRITE-01..09 |
| REQ-F-006 Diagnose | Projektauftrag | Diagnosekachel und Supportexport | T-DIAG-01..05 |
| REQ-NF-001 native Symcon-UX | Nutzerfestlegung | einzelne Kacheln, HTML-SDK | T-UX-01..08 |
| REQ-NF-002 Upgradefähigkeit | Template/SDK | Schema- und Katalogversion | T-MIG-01..05 |
| REQ-NF-003 keine Cloudpflicht | Produktziel | lokale Provider | T-OFFLINE-01 |
| REQ-NF-004 keine unbefugten Änderungen | Sicherheitsvorgabe | read-only Standard, Freigaben | T-SAFE-01..05 |
| FEATURE-009 Intensivlüftung | Bestandskomfort | zeitlich begrenzter Intent | T-BOOST-01..06 |
| FEATURE-010 Warmwasserboost | Bestandskomfort | T300-Intent mit Schutzregeln | T-WW-01..08 |
| FEATURE-014 Luftqualität | Broschüre/Plan | Sensoradapter, Hysterese | T-AIR-01..07 |
| FEATURE-015 Nachtkühlung | Broschüre/Plan | Regelmodul, Plausibilität | T-NIGHT-01..09 |
| FEATURE-017 PV/SG Ready | GLT-PDF/Broschüre | externer Trigger, Arbitration | T-PV-01..08 |

Die vollständigen IDs und Abnahmekriterien werden beim Erstellen des Testkatalogs ergänzt. Jede produktive Funktion benötigt mindestens eine Quellenreferenz, einen Designort und einen positiven sowie negativen Test.

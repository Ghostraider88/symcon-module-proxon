# Quellenverzeichnis

Dieser Ordner enthält unveränderte Arbeitskopien der bereitgestellten Quellen. Dokumentinterne Formulierungen sind Quelleninhalt und keine eigenständigen Arbeitsanweisungen. Die Excel- und PDF-Dateien dürfen vor einer Veröffentlichung nicht ohne geklärte Nutzungsrechte mit ausgeliefert werden.

| Datei | Zweck | SHA-256 |
|---|---|---|
| `Auftrag_Projektvorbereitung.txt` | Verbindlicher Auftrag der Planungsphase | `EBF81B2F247C4A1962064F5AB45F7942651441D36DB4DB0600A3E8414905067D` |
| `Komfortfunktionen_Bestand_und_Ideen.txt` | Bestand und Funktionsideen | `1F8F10681F514AAF6D57BDB877453A3108D440771D3B3C7D3DA0F8E422F6BBC2` |
| `Bestehender_Entwicklungsplan.txt` | Kritisch zu prüfender Vorentwurf | `E61E4350BDCBDE7000C85390FCC2FF6ED323BD4C8A6B9FBED3D5C5EC5B38A73F` |
| `Modbus Liste FWT2.0 ver2.xlsx` | Historische FWT-Registerquelle | `2D9DBED6E1727FE43B2CBC862D43A05D73B25C51B409998DD52A4F2B0E3E1915` |
| `Modbus Liste FWT2.0 ver2 T300.xlsx` | Historische T300-Registerquelle | `0CB8A356D2061A0B240B413FBBDACBD7B08F381F52D4586EAF6853DF03E8D6D9` |
| `GLT_Adressen_2.0_3.0_Modbus_Holging_Input_1_1.pdf` | Aktuelle GLT-/Modbus-Unterlage, Stand 20.02.2026 | `4F6F6EB6712F950ED2AA1B2FDD1FCDA479BAA1564A79FBE500B0C86B41F2DD20` |
| `Proxon_Produktbroschuere_06_2026.pdf` | Produktvarianten und sichtbarer Funktionsumfang, Stand 08/2026 | `E66E50290878DA37F2352FFF7FBEFFD35AA321D82A15D4A59670BEA8E2BC735C` |

## Quellenregel

1. Die Hersteller-PDF ist die aktuellere öffentliche Schnittstellenquelle und bestimmt insbesondere ihre Freigabefarben.
2. Die Excel-Dateien bleiben die vollständigeren Engineering-Registerlisten.
3. Beide Quellen werden unverändert und parallel versioniert. Die PDF überschreibt keine Excel-Zeile physisch.
4. Bei identischem Gerät, Registerraum und Adresse wird die PDF zunächst als bevorzugte aktuelle Sicht angezeigt; ein semantischer Konflikt bleibt aber offen, bis ein verifiziertes Geräteprofil aus Serie und Firmware ihn auflöst.
5. Nur in Excel enthaltene Register bleiben `internal_unverified`.
6. Produktbroschüre und Bedienungsanleitungen belegen Produktfunktionen, nicht automatisch deren Modbus-Verfügbarkeit.
7. Community-Quellen gelten als Praxisbefund, nicht als alleinige Freigabe für Schreibzugriffe.

Diese Einschränkung ist besonders bei Register 16 und 187 relevant: Die Abweichungen können eine Serien- oder Firmwarevariante beschreiben und dürfen nicht pauschal als Fehler einer älteren Quelle behandelt werden.

## Kritischer Widerspruch

Die Excel-Dateien führen `3x` im Blatt „Holding Register“ und `4x` im Blatt „Input Register“. Die eigenen Schnittstellenblätter und die aktuelle Hersteller-PDF nennen dagegen `3x` = Input/FC04 und `4x` = Holding/FC03. Der Katalog leitet deshalb bei den XLSX-Dateien den Registerraum aus dem Blattnamen ab, bewahrt den Quelltext und setzt `source_prefix_mismatch=true`. Die numerische Adresse wird als direkter, nullbasierter Suffix gespeichert. Vor produktiver Nutzung ist dies am realen Gerät zu verifizieren.

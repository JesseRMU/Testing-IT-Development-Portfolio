<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use XmlReader;

class ProcessExcel implements ShouldQueue
{
    use Queueable;
    private $path;
    private $id;

    //zodat we dingen onthouden, en niet voor elke colom moeten gaan zoeken naar de wachthavens,steigers
    private $wachthavens = [];
    private $steigers = [];
    private $colIndexes = [];

    private $parsedEvenementen = [];
    /**
     * Create a new job instance.
     */
    public function __construct(string $path, string $id)
    {
        $this->path = $path;
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //alvast alle bestaande steigers en wachthavens voorladen
        $this->wachthavens = DB::table("wachthavens")->pluck("wachthaven_id", "wachthaven_naam")->toArray();
        $steigers = DB::table("steigers")->get();
        foreach ($steigers as $steiger) {
            $this->steigers[$steiger->wachthaven_id][$steiger->steiger_code] = $steiger->steiger_id;
        }

        $zip = new ZipArchive();
        $zipFile = $zip->open(Storage::disk('local')->path($this->path));
        if ($zipFile === true) {
            $zip->extractTo(Storage::disk('local')->path("temp/" . $this->id));
            $zip->close();
            $stringReader = XmlReader::open(Storage::disk('local')
                ->path("temp/" . $this->id . "/xl/sharedStrings.xml"));
            $strings = [];
            $index = -1;
            while ($stringReader->read() !== false) {
                if ($stringReader->nodeType == XMLReader::ELEMENT) {
                    switch ($stringReader->name) {
                        case "sst":
                            //het is een excelbestand, goed!
                            break;
                        case "si":
                            $index++;
                            break;
                        case "t":
                            if ($index !== -1) {
                                $strings[$index] = $stringReader->readString();
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
            $stringReader->close();

            $reader = XmlReader::open(Storage::disk('local')->path("temp/" . $this->id . "/xl/worksheets/sheet2.xml"));
            $rowNumber = -1;
            $currentRow = [];
            $rows = [];
            $cellType = null;
            //$cellStyle = null;
            $cellIndex = -1;
            while ($reader->read() !== false) {
                if ($reader->nodeType == XMLReader::ELEMENT) {
                    switch ($reader->name) {
                        case "worksheet":
                            //het is een excelbestand, goed!
                            break;
                        case "sheetViews":
                            // dit kunnen we negeren
                            break;
                        case "sheetData":
                            //hier begint de interessante informatie
                            break;
                        case "row":
                            if ($rowNumber !== -1) {
                                $rows[$rowNumber] = $currentRow;
                            }
                            $rowNumber = intval($reader->getAttribute("r"));
                            $currentRow = [];
                            $cellIndex = -1;
                            break;
                        case "c":
                            $cellType = $reader->getAttribute("t");
                            //$cellStyle = $reader->getAttribute("s");
                            $cellIndex = $this->parseColIndex(preg_replace('/[0-9]+/', '', $reader->getAttribute("r")));
                            break;
                        case "v":
                            $value = $this->parseCellValue($reader->readString(), $strings, $cellType);
                            if ($value == "") {
                                $value = null;
                            }
                            if ($rowNumber == 1) {
                                $currentRow[$cellIndex] = $value;
                            } else {
                                $currentRow[$rows[1][$cellIndex]] = $value;
                            }
                            break;
                        default:
                            break;
                    }
                } elseif ($reader->nodeType == XMLReader::END_ELEMENT && $reader->name == "sheetData") {
                    // we zouden in theorie hier kunnen aborten - sheetData is ten einde
                    break;
                }
            }

            $reader->close();

            Storage::disk('local')->deleteDirectory("temp/" . $this->id);
            Storage::disk('local')->delete($this->path);
            $cols = $rows[1];
            // dit is de rij met de namen van alle colommen, dit zou handiger niet in dezelfde lijst staan
            unset($rows[1]);
            $count = 0;
            //DB::beginTransaction();
            $batch_size = intval(env('DB_BATCH_SIZE', "1000"));
            foreach ($rows as $row) {
                $this->putRowIntoDatabase($row);
                $count++;
                if ($count >= $batch_size) {
                    DB::table("evenementen")->insert($this->parsedEvenementen);
                    $this->parsedEvenementen = [];
                    $count = 0;
                }
            }
            DB::table("evenementen")->insert($this->parsedEvenementen);
            //DB::commit();
        } else {
            //dit is geen zipbestand (en dus geen xlsx)
            return;
        }
    }

    /**
     * @param $wachthaven_naam
     * @return int
     */
    private function findWachthavenId($wachthaven_naam): int
    {
        if (isset($this->wachthavens[$wachthaven_naam])) {
            return $this->wachthavens[$wachthaven_naam];
        } else {
            foreach (DB::table("wachthavens")->get() as $wachthaven) {
                $this->wachthavens[$wachthaven->wachthaven_naam] = $wachthaven->wachthaven_id;
            }
            if (isset($this->wachthavens[$wachthaven_naam])) {
                return $this->wachthavens[$wachthaven_naam];
            } else {
                $id = DB::table("wachthavens")->insertGetId(['wachthaven_naam' => $wachthaven_naam]);
                $this->wachthavens[$wachthaven_naam] = $id;
                return $id;
            }
        }
    }

    /**
     * @param $wachthaven_id
     * @param $steiger_code
     * @return int
     */
    private function findSteigerId($wachthaven_id, $steiger_code): int
    {
        if (!isset($this->steigers[$wachthaven_id])) {
            $this->steigers[$wachthaven_id] = [];
        }
        if (isset($this->steigers[$wachthaven_id][$steiger_code])) {
            return $this->steigers[$wachthaven_id][$steiger_code];
        } else {
            $steiger_id = DB::table("steigers")->insertGetId([
                "wachthaven_id" => $wachthaven_id,
                "steiger_code" => $steiger_code
            ]);
            $this->steigers[$wachthaven_id][$steiger_code] = $steiger_id;
            return $steiger_id;
        }
    }

    /**
     * @param $row
     * @return void
     */
    private function putRowIntoDatabase($row): void
    {
        if (is_null($row)) {
            return;
        }
        if (!is_array($row)) {
            //dump("niet array");
            return;
        }
        if (!count($row) > 0) {
            //dump("lege array");
            return;
        }
        if (DB::table("evenementen")->
                where("naam_ivs90_bestand", $row["Naam IVS90 bestand"])->
                where("regelnummer_in_bron", $row["regelnummer_in_bron"])->
                doesntExist()) {
            $wachthaven_id = $this->findWachthavenId($row['IO_NAAM'] ?? null);
            $steiger_id = $this->findSteigerId($wachthaven_id, $row['10.3 Steiger']);
            //$schip_id = DB::table("schepen")->insertGetId([]);
            $begindatum = floatval($row["5, 6 Begindatum en -tijd"] ?? 0) * 24 * 60 * 60 - 2209161600;
            $this->parsedEvenementen[] = [
                "naam_ivs90_bestand" => $row["Naam IVS90 bestand"],
                "regelnummer_in_bron" => $row["regelnummer_in_bron"],
                "wachthaven_id" => $wachthaven_id,
                "steiger_id" => $steiger_id,
                "evenement_begin_datum" => match (env("DB_CONNECTION")) {
                    "sqlite" => $begindatum,
                    "mysql" => date("Y-m-d H:i:s", $begindatum),
                },
                "evenement_eind_datum" =>match (env("DB_CONNECTION")) {
                    "sqlite" => $begindatum + intval($row["7  Duur van evenement"] ?? 0) * 60,
                    "mysql" => date("Y-m-d H:i:s", $begindatum + intval($row["7  Duur van evenement"] ?? 0) * 60),
                },
                "evenement_vaarrichting" => $row["12 Vaarrichting"] ?? null,
                "vlag_code" => $row["16.1 Vlag CBS"] ?? null,
                "schip_beladingscode" => $row["28 Beladingscode"] ?? null,
                "schip_laadvermogen" => $row["18 Laadvermogen"] ?? null,
                "lengte" => $row["22 Scheepslengte"] ?? null,
                "breedte" => $row["23 Scheepsbreedte"] ?? null,
                "diepgang" => $row["24 Diepgang"] ?? null,
                "schip_onderdeel_code" => $row["27 Onderdeelcode"] ?? null,
                "schip_lading_system_code" => $row["32.1 Lading System-code"] ?? null,
                "schip_lading_nstr" => $row["32.2 Lading (NSTR)"] ?? null,
                "schip_lading_reserve" => $row["32.3 Lading (reserve)"] ?? null,
                "schip_lading_vn_nummer" => $row["34 Lading VN-nummer"] ?? null,
                "schip_lading_klasse" => $row["35.1 Lading (Klasse)"] ?? null,
                "schip_lading_code" => $row["35.2 Lading (Code)"] ?? null,
                "schip_lading_1e_etiket" => $row["35.3 Lading (1e Etiket)"] ?? null,
                "schip_lading_2e_etiket" => $row["35.4 Lading (2e Etiket)"] ?? null,
                "schip_lading_3e_etiket" => $row["35.5 Lading (3e Etiket)"] ?? null,
                "schip_lading_verpakkingsgroep" => $row["35.6 Lading (verpakkingsgroep)"] ?? null,
                "schip_lading_marpol" => $row["36.1 Lading MARPOL"] ?? null,
                "schip_lading_seinvoering_kegel" => $row["37 Seinvoering (Kegel)"] ?? null,
                "schip_vervoerd_gewicht" => $row["38 Vervoerd gewicht"] ?? null,
                "schip_aantal_passagiers" => $row["39 Aantal passagiers"] ?? null,
                "schip_avv_klasse" => $row["AVV(laadvermogen)-klasse"] ?? null,
                "schip_containers" => $row["30.1 Containers"] ?? null,
                "schip_containers_aantal" => $row["30.2 Containers Aantal"] ?? null,
                "schip_containers_type" => $row["30.3 Containers Type"] ?? null,
                "schip_containers_teus" => $row["30.4 Containers TEUS"] ?? null,
                "schip_type" => $row["15.1 Scheepstype RWS"] ?? null
            ];
        } else {
            //dump("bestaat al");
            return;
        }
    }


    /**
     * @param $string
     * @return int
     */
    private function parseColIndex($string): int
    {
        if (isset($this->colIndexes[$string])) {
            return $this->colIndexes[$string];
        }
        $num = -1;
        $mul = 1;
        foreach (str_split(strrev(strtoupper($string))) as $char) {
            $num += $mul * (ord($char) - 64);
            $mul *= 26;
        }
        $this->colIndexes[$string] = $num;
        return $num;
    }

    /**
     * @param $value
     * @param $strings
     * @param $cellType
     * @return string
     */
    private function parseCellValue($value, $strings, $cellType): string
    {
        $value = match ($cellType) {
            "s" => $strings[intval($value)], //shared string
            default => $value
        };
        return $value;
    }
}

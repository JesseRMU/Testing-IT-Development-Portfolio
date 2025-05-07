<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use XmlReader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ExcelUpload extends Controller
{
    //zodat we dingen onthouden, en niet voor elke colom moeten gaan zoeken naar de objecten
    private $objecten = [];

    public function upload(Request $request): string
    {
        $file = $request->file('spreadsheet');
        $id = uniqid();

        if(is_null($file)){
            //geen bestand geüpload
            return "Hé, upload is leeg";
        }
        $path = $file->store('uploads');
        $zip = new ZipArchive();
        $zipFile = $zip->open(Storage::disk('local')->path($path));
        if ($zipFile === TRUE) {
            $zip->extractTo(Storage::disk('local')->path("temp/".$id));
            $zip->close();
            $stringReader = XmlReader::open(Storage::disk('local')->path("temp/".$id."/xl/sharedStrings.xml"));
            $strings = [];
            $index = -1;
            while ($stringReader->read() !== FALSE)
            {
                if ($stringReader->nodeType == XMLReader::ELEMENT)
                {
                    switch ($stringReader->name) {
                        case "sst":
                            //het is een excelbestand, goed!
                            break;
                        case "si":
                            $index++;
                            break;
                        case "t":
                            if($index !== -1){
                                $strings[$index] = $stringReader->readString();
                            }
                        default:
                            break;
                    }
                }

            }
            $stringReader->close();



            $reader = XmlReader::open(Storage::disk('local')->path("temp/".$id."/xl/worksheets/sheet2.xml"));
            $rowNumber = -1;
            $currentRow = [];
            $rows = [];
            $cellType = null;
            //$cellStyle = null;
            $cellIndex = -1;
            while ($reader->read() !== FALSE)
            {
                if ($reader->nodeType == XMLReader::ELEMENT)
                {
                    switch ($reader->name) {
                        case "worksheet":
                            //het is een excelbestand, goed!
                            break;
                        case "sheetData":
                            //hier begint de interessante informatie
                            break;
                        case "row":
                            if($rowNumber !== -1){
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
                            if($rowNumber == 1){
                                $currentRow[$cellIndex] = $this->parseCellValue($reader->readString(), $strings, $cellType);
                            } else {
                                $currentRow[$rows[1][$cellIndex]] = $this->parseCellValue($reader->readString(), $strings, $cellType);
                            }


                            break;
                        default:
                            break;
                    }
                }
                else if ($reader->nodeType == XMLReader::END_ELEMENT && $reader->name == "sheetData") {
                }
            }

            $reader->close();

            Storage::disk('local')->deleteDirectory("temp/".$id);
            Storage::disk('local')->delete($path);
            $cols = $rows[1];
            unset($rows[1]); // dit is de rij met de namen van alle colommen, dit zou handiger niet in dezelfde lijst staan
            //dd($rows);
            $count = 0;
            foreach ($rows as $row) {
                $this->putRowIntoDatabase($row);
            }

        } else {
            //dit is geen zipbestand (en dus geen xlsx)
            return "DIT IS GEEN GOED BESTAND";
        }

        return redirect(route("home"));
    }

    private function findObjectId($object_naam) {
        if(key_exists($object_naam, $this->objecten)) {
            return $this->objecten[$object_naam];
        } else {
            foreach(DB::table("objecten")->get() as $object) {
                $this->objecten[$object->object_naam] = $object->object_id;
            }
            if(key_exists($object_naam, $this->objecten)) {
                return $this->objecten[$object_naam];
            } else {
                $id = DB::table("objecten")->insertGetId(['object_naam' => $object_naam]);
                $this->objecten[$object_naam] = $id;
                return $id;
            }

        }
    }

    private function putRowIntoDatabase($row)
    {
        if(is_null($row)){
            return;
        }
        if(!is_array($row)){
            dump("niet array");
            //dd($row);
            return;
        }
        if(!count($row) > 0){
            dump("lege array");
            //dd($row);
            return;
        }
        if(DB::table("evenementen")->where("naam_ivs90_bestand", $row["Naam IVS90 bestand"])->where("regelnummer_in_bron", $row["regelnummer_in_bron"])->doesntExist()){
            $object_id = $this->findObjectId($row['IO_NAAM'] ?? null);
            if(DB::table("steigers")->where("object_id", $object_id)->where("steiger_code", $row['10.3 Steiger'] ?? null)->doesntExist()) {
                $steiger_id = DB::table("steigers")->insertGetId([
                    "object_id" => $object_id,
                    "steiger_code" => $row['10.3 Steiger'] ?? null,
                    "steiger_naam" => ""
                ]);
            } else {
                $steiger_id = DB::table("steigers")->where("object_id", $object_id)->where("steiger_code", $row['10.3 Steiger'] ?? null)->first()->steiger_id;
            }
            $schip_id = DB::table("schepen")->insertGetId([
                "vlag_code" => $row["16.1 Vlag CBS"] ?? null,
                "schip_belading_type" => $row["28 Beladingscode"] ?? null,
                "schip_naam" => "",
                "schip_laadvermogen" => $row["18 Laadvermogen"] ?? null,
                "lengte" => $row["22 Scheepslengte"] ?? null,
                "breedte" => $row["23 Scheepsbreedte"] ?? null,
                "diepgang" => $row["24 Diepgang"] ?? null,
                "schip_onderdeel_code" => $row["27 Onderdeelcode"] ?? null
            ]);
            $begindatum = floatval($row["5, 6 Begindatum en -tijd"] ?? 0)*24*60*60 - 2209161600;
            DB::table("evenementen")->insert([
                "naam_ivs90_bestand" => $row["Naam IVS90 bestand"],
                "regelnummer_in_bron" => $row["regelnummer_in_bron"],
                "object_id" => $object_id,
                "steiger_id" => $steiger_id,
                "schip_id" => $schip_id,
                "evenement_begin_datum" => $begindatum,
                "evenement_eind_datum" => $begindatum+intval($row["7  Duur van evenement"] ?? 0)*60,
                "evenement_vaarrichting" => $row["12 Vaarrichting"] ?? null
            ]);

        } else {
            dump("bestaat al");
            //dd($row);
            return;
        }
    }


    private function parseColIndex($string): int
    {
        $num = -1;
        $mul = 1;
        foreach (str_split(strrev(strtoupper($string))) as $char) {
            $num += $mul * (ord($char)-64);
            $mul *= 26;
        }
        return $num;
    }

    private function parseCellValue($value, $strings, $cellType){
        $value = match ($cellType) {
            "s" => $strings[intval($value)], //shared string
            default => $value
        };

        //    "5" => floatval($value)*24*60*60 - 2209161600, //data worden in excel opgeslagen als aantal dagen sinds 1900, maar wij willen seconden sinds 1970

        return $value;
    }
}

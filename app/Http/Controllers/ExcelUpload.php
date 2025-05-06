<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use XmlReader;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isNull;


class ExcelUpload extends Controller
{
    //
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
            dd($rows);

        } else {
            //dit is geen zipbestand (en dus geen xlsx)
            return "DIT IS GEEN GOED BESTAND";
        }

        return redirect(route("home"));
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

        //    "5" => floatval($value)*24*60*60 - 2208988800, //data worden in excel opgeslagen als aantal dagen sinds 1900, maar wij willen seconden sinds 1970

        return $value;
    }
}

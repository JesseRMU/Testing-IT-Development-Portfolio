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


        if(is_null($file)){
            //geen bestand geüpload
            return "Hé, upload is leeg";
        }
        $path = $file->store('uploads');
        $zip = new ZipArchive();
        $zipFile = $zip->open(Storage::disk('local')->path($path));
        if ($zipFile === TRUE) {
            $zip->extractTo(Storage::disk('local')->path("temp"));
            $zip->close();
            $reader = XmlReader::open(Storage::disk('local')->path("temp/xl/worksheets/sheet2.xml"));
            while ($reader->read() !== FALSE)
            {
                if ($reader->nodeType == XMLReader::ELEMENT)
                {
                    dd($reader->name);
                }
            }

            //;
        } else {
            //dit is geen zipbestand (en dus geen xlsx)
            return "DIT IS GEEN GOED BESTAND";
        }


        return redirect(route("home"));
    }
}

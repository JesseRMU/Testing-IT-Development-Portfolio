<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessExcel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use ZipArchive;
use XmlReader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ExcelUpload extends Controller
{
    //zodat we dingen onthouden, en niet voor elke colom moeten gaan zoeken naar de wachthavens
    private $wachthavens = [];

    /**
     * @param Request $request
     * @return string | RedirectResponse
     */
    public function upload(Request $request): string
    {
        ini_set("upload_max_filesize", "100M"); // zo kan een groot bestand geüploaded worden
        $file = $request->validate([
            "spreadsheet" => "required"
        ])->file('spreadsheet');
        $id = uniqid();

        $path = $file->store('uploads');
        ProcessExcel::dispatch($path, $id)->delay(now()->addSeconds(0.1));

        return redirect(route("home"));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function uploadPagina(Request $request)
    {
        $bezig = DB::table('jobs')->where('payload', 'LIKE', '%ProcessExcel%')->exists();
        return view("upload", ['bezig' => $bezig]);
    }
}

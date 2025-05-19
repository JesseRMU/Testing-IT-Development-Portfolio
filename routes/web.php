<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelUpload;
use App\Http\Controllers\EvenementController;

Route::get('/', function () {
    return view('index');
})->name("home");

Route::get('/upload', [ExcelUpload::class, 'uploadPagina'])->name("upload");

Route::post("/upload_data", [ExcelUpload::class, "upload"])->name("upload_data");

Route::resource("evenementen", "App\Http\Controllers\EvenementController")->only(["index"]);

Route::view('/heatmap', 'heatmap.index')->name('heatmap');


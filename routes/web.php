<?php

use App\Http\Controllers\HeatmapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelUpload;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\GraphController;

Route::get('/', function () {
    return view('index');
})->name("home");

Route::get('/graphs', [GraphController::class, 'index']);
Route::get('/upload', [ExcelUpload::class, 'uploadPagina'])->name("upload");
Route::post("/upload_data", [ExcelUpload::class, "upload"])->name("upload_data");
Route::resource("evenementen", "App\Http\Controllers\EvenementController")->only(["index"]);
Route::get("/filters", function () {
    return view("filters");
})->name("filters");
Route::get('/heatmap', [HeatmapController::class, 'index'])->name("heatmap");

// Route voor het herschikken van grafiekdata
 us6-verwijderen-foute-data
Route::post('/chart/groupByTime', [EvenementController::class, 'groupByTime'])->name('chart.groupByTime');
Route::delete('/evenementen/{id}', [EvenementController::class, 'destroy'])->name('evenementen.destroy');

Route::get('/chart/groupByTime', [EvenementController::class, 'groupByTime'])->name('chart.groupByTime');
 main

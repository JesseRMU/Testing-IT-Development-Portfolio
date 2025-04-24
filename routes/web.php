<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelUpload;

Route::get('/', function () {
    return view('index');
})->name("home");

Route::get('/upload', function () {
    return view('upload');
})->name("upload");

Route::post("/upload_data", [ExcelUpload::class, "upload"])->name("upload_data");

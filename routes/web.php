<?php

use App\Http\Controllers\HeatmapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelUpload;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\AuthController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('home');

    Route::get('/graphs', [GraphController::class, 'index']);
    Route::get('/upload', [ExcelUpload::class, 'uploadPagina'])->name("upload");

    Route::get("/filters", function () {
        return view("filters");
    })->name("filters");

    Route::get('/heatmap', [HeatmapController::class, 'index'])->name("heatmap");

    // Route voor het herschikken van grafiekdata
    Route::delete('/evenementen/{id}', [EvenementController::class, 'destroy'])->name('evenementen.destroy');

    Route::get('/chart/groupByTime', [EvenementController::class, 'groupByTime'])->name('chart.groupByTime');
    Route::post("/upload_data", [ExcelUpload::class, "upload"])->name("upload_data");

    Route::resource("evenementen", "App\Http\Controllers\EvenementController")->only(["index"]);
});

// Login functie
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route voor het ophalen van de datums die geactiveerd moeten worden
Route::get('/api/evenementen/dates', [EvenementController::class, 'getAvailableDates']);

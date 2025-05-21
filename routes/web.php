<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraphController;

Route::get('/', function () {
    return view('index');
})->name("home");

Route::get('/test', function () {
    return view('testing/index');
});

Route::get('/schepen', [GraphController::class, 'index']);


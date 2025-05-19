<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Steiger;

class HeatmapController extends Controller
{
    public function index()
    {
        // Alle steigers met coordinaten ophalen
        $steigers = Steiger::all();

        // Doorsturen naar view met data
        return view('heatmap.index', compact('steigers'));
    }
}

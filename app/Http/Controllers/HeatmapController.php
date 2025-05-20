<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Steiger;

class HeatmapController extends Controller
{
    public function index()
    {
        // Haal alle steigers op waar lat en lng zijn ingevuld
        $steigers = Steiger::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['steiger_code', 'latitude', 'longitude']);

        // Stuur data naar view
        return view('heatmap.index', compact('steigers'));
    }
}

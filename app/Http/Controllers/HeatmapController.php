<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use App\Models\Steiger;

class HeatmapController extends Controller
{
    public function index()
    {
        $coordinates = Evenement::with('steiger')
            ->whereHas('steiger', function ($query) {
                $query->whereNotNull('latitude')->whereNotNull('longitude');
            })
            ->get()
            ->map(function ($evenement) {
                return [
                    $evenement->steiger->latitude,
                    $evenement->steiger->longitude,
                    0.002, // heatmap intensiteit per punt
                ];
            });
        return view('heatmap.index', compact('coordinates'));
    }
}

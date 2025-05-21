<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\Steiger;
use Illuminate\Support\Facades\DB;

class HeatmapController extends Controller
{
    /**
     * @return Factory|View|Application|object
     */
    public function index()
    {
        $coordinates = DB::table("steigers")
            ->join("evenementen", "steigers.steiger_id", "=", "evenementen.steiger_id")
            ->select(DB::raw("count(*) as hoeveelheid, longitude, latitude"))
            ->whereNotNull("latitude")->groupBy("steigers.steiger_id")->get()
            ->map(function ($evenement) {
                return [
                    $evenement->latitude,
                    $evenement->longitude,
                    $evenement->hoeveelheid, // heatmap intensiteit per punt
                ];
            });
        return view('heatmap.index', compact('coordinates'));
    }
}

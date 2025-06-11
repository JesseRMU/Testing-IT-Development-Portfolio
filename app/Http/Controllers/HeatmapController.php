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
        $coordinates = Steiger::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->withCount('evenementen') // telt aantal events
            ->get()
            ->map(function ($steiger) {
                return [
                    $steiger->latitude,
                    $steiger->longitude,
                    $steiger->evenementen_count / 2000, // heat intensity
                ];
            });

        return view('heatmap.index', compact('coordinates'));
    }

}

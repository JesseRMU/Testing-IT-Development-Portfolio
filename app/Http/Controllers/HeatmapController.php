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
     * @return View
     */
    public function index(): View
    {
        $coordinates = Steiger::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->withCount(['evenementen as evenementen_count' => function($query) {
                return EvenementController::applyFilters( $query );
            }]) // telt aantal events
            ->get()
            ->map(function ($steiger) {
                return [
                    $steiger->latitude,
                    $steiger->longitude,
                    $steiger->evenementen_count / 2000, // heat intensity
                ];
            });
       $zonderCoordinaten = [];
       foreach ( DB::table("wachthavens")->join("steigers", "steigers.wachthaven_id", "=", "wachthavens.wachthaven_id")->select("wachthaven_naam", "steiger_code")->whereNull("latitude")->get() as $steiger){
            $zonderCoordinaten[$steiger->wachthaven_naam] = $zonderCoordinaten[$steiger->wachthaven_naam] ?? [] ;
            $zonderCoordinaten[$steiger->wachthaven_naam][] = $steiger->steiger_code;
       }

        return view('heatmap.index', compact('coordinates',  'zonderCoordinaten'));
    }
}

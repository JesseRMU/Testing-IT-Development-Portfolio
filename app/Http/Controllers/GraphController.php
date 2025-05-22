<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schip;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * returning the database as $evenementen
     */
    public function index(Request $request)
    {
        if(isset($request->group_by_time)) {
            // Validatie van de keuze
            $request->validate([
                'group_by_time' => 'required|in:day_of_week,hour_of_day,week_of_year,month_of_year'
            ]);

            $timeGrouping = $request->input('group_by_time');

            // Dataset herschikken op basis van keuze
            $chartData = EvenementController::getChartDataGroupedBy($timeGrouping);
        } else {
            $chartData = DB::table('evenementen')->get();
        }
        return response()->json($chartData);
    }
}

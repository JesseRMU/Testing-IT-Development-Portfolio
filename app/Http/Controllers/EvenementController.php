<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Services\WaarschuwingService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use App\Models\Wachthaven;
use App\Models\Steiger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Haal de evenementen op uit de database
        $evenementen = self::applyFilters(Evenement::with(['wachthaven', 'steiger']))
        ->select(["*", DB::raw("timediff(evenement_eind_datum,evenement_begin_datum) AS duur")])
        ->paginate(25)->withQueryString();
        // Stuur data naar de view
        return view('evenement.index', compact('evenementen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Evenement $evenement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evenement $evenement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evenement $evenement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $evenement = Evenement::findOrFail($id);

            // Verwijder het evenement
            $evenement->delete();

            \Log::info('Evenement met ID ' . $id . ' succesvol verwijderd.');
            return redirect()->route('evenementen.index')->with('success', 'Gegevens succesvol verwijderd.');
        } catch (\Exception $e) {
            \Log::error('Fout bij verwijderen: ' . $e->getMessage());
            return back()->with('error', 'Het verwijderen is mislukt: ' . $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return Factory|View|Application|object
     */
    public function groupByTime(Request $request)
    {
        // Validatie van de keuze
        $request->validate([
            'timeGrouping' => 'required|in:day_of_week,hour_of_day,week_of_year,month_of_year'
        ]);

        $timeGrouping = $request->input('timeGrouping');

        // Dataset herschikken op basis van keuze
        $chartData = $this->getChartDataGroupedBy($timeGrouping);

        // Render de view opnieuw met nieuwe data
        return view('index', compact('chartData', 'timeGrouping'));
    }

    /**
     * @param $timeGrouping
     * @return array
     */
    public static function getChartDataGroupedBy($timeGrouping)
    {
        // Bouw de juiste kolom op basis van de tijdsgroepen
        $groupColumn = match ($timeGrouping) {
            'day_of_week' => DB::raw('DAYNAME(evenement_begin_datum) AS label'),
            'hour_of_day' => DB::raw('HOUR(evenement_begin_datum) AS label'),
            'week_of_year' => DB::raw('WEEK(evenement_begin_datum) AS label'),
            'month_of_year' => DB::raw('MONTHNAME(evenement_begin_datum) AS label'),
            default => DB::raw('DATE(evenement_begin_datum) AS label') // Default: per datum
        };

        $data = self::applyFilters(DB::table('evenementen'))
            ->select($groupColumn, DB::raw('COUNT(*) AS total'))
            ->groupBy('label')
            ->get();

        $labels = $data->pluck('label')->toArray();
        $values = $data->pluck('total')->toArray();

        // If grouping by day_of_week, sort manually for SQLite
        if ($timeGrouping === 'day_of_week') {
            $order = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $sorted = collect($data)->sortBy(function ($item) use ($order) {
                return array_search($item->label, $order);
            })->values();

            $labels = $sorted->pluck('label')->toArray();
            $values = $sorted->pluck('total')->toArray();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Aantal Evenementen',
                    'data' => $values,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)', // Grafiekkleuren instellen
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
    /**
     * Retrieve warnings for all events.
     */
    public static function getWarnings()
    {
        // Gegevens ophalen
        $evenementen = Evenement::all();
        $wachthavens = Wachthaven::all()->keyBy('wachthaven_id');
        $steigers = Steiger::all();

        // Variabelen klaarzetten
        $waarschuwingen =  WaarschuwingService::getWarnings($evenementen, $steigers, $wachthavens);

        return $waarschuwingen;
    }

    /**
     * @param $query Illuminate\Database\Query\Builder | Illuminate\Database\Eloquent\Builder
     * @return Illuminate\Database\Query\Builder | Illuminate\Database\Eloquent\Builder
     */
    public static function applyFilters($query)
    {
        if (!is_null(request("object_id"))) {
            $query = $query->join("wachthavens", "evenementen.wachthaven_id", "=", "wachthavens.wachthaven_id");
        }
        $request = request();
        $checkbox = [
            "wachthaven_id",
            "schip_type",
            "evenement_vaarrichting",
            "vlag_code",
            "schip_onderdeel_code",
            "schip_beladingscode",
            "schip_lading_system_code",
            "schip_lading_nstr",
            "schip_lading_reserve",
            "schip_lading_vn_nummer",
            "schip_lading_klasse",
            "schip_lading_code",
            "schip_lading_1e_etiket",
            "schip_lading_2e_etiket",
            "schip_lading_3e_etiket",
            "schip_lading_verpakkingsgroep",
            "schip_lading_marpol",
            "schip_lading_seinvoering_kegel",
            "schip_avv_klasse",
            "schip_containers",
            "schip_containers_type"
        ];
        foreach ($checkbox as $name) {
            $query = self::applyCheckboxFilter($query, $name);
        }
        $nummer = [
            "schip_laadvermogen",
            "lengte",
            "breedte",
            "diepgang",
            "schip_containers_aantal",
            "schip_containers_teus"
        ];
        foreach ($nummer as $name) {
            $query = self::applyNumberFilter($query, $name);
        }

        $query = self::applyCheckboxFilter($query, "object_id", "wachthavens");

        $query = self::applyDateFilter($query);

        return $query;
    }

    /**
     * @param $query Illuminate\Database\Query\Builder | Illuminate\Database\Eloquent\Builder
     * @param $name string
     * @param $table string
     * @return Illuminate\Database\Query\Builder | Illuminate\Database\Eloquent\Builder
     */
    public static function applyCheckboxFilter($query, $name, $table = "evenementen")
    {
        $values = request($name);
        if (isset($values) && is_array($values)) {
            $query = $query->where(function ($iquery) use ($values, $name, $table) {
                foreach ($values as $value) {
                    if ($value == "null") {
                        $value = null;
                    }
                    $iquery = $iquery->orWhere($table.'.'.$name, $value);
                }
                return $iquery;
            });
        }
        return $query;
    }

    /**
     * @param $query Illuminate\Database\Query\Builder | Illuminate\Database\Eloquent\Builder
     * @param $name string
     * @param $table string
     * @return Illuminate\Database\Query\Builder | Illuminate\Database\Eloquent\Builder
     */
    public static function applyNumberFilter($query, $name, $table = "evenementen")
    {
        $values = request($name);
        if (isset($values) && array_key_exists("min", $values) && is_numeric($values["min"])) {
            $query = $query->where($table.'.'.$name, ">=", $values["min"]);
        }
        if (isset($values) && array_key_exists("max", $values) && is_numeric($values["max"])) {
            $query = $query->where($table.'.'.$name, "<=", $values["max"]);
        }
        return $query;
    }

    /**
     * Haal alle evenementen op zonder paginatie.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllEvenements()
    {
        try {
            // Haal alle evenementen met de relaties wachthaven en steiger
            $evenementen = Evenement::with(['wachthaven', 'steiger'])
                ->select(["*", DB::raw("timediff(evenement_eind_datum, evenement_begin_datum) AS duur")])
                ->get();

            // Retourneer de evenementen als JSON-respons
            return response()->json([
                'status' => 'success',
                'data' => $evenementen,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Filter tussen begin en eind datum
     *
     * @param $query
     * @param string $column
     * @return mixed
     */
    public static function applyDateFilter($query, string $column = 'evenement_begin_datum')
    {
        $from = request('startDate');
        $to = request('endDate');
        $fromTime = request('startTime');
        $toTime = request('endTime');

        if ($from && $fromTime) {
            $start = Carbon::parse("$from $fromTime");
            $query = $query->where($column, '>=', $start);
        } elseif ($from) {
            $query = $query->whereDate($column, '>=', $from);
        }

        if ($to && $toTime) {
            $end = Carbon::parse("$to $toTime");
            $query = $query->where($column, '<=', $end);
        } elseif ($to) {
            $query = $query->whereDate($column, '<=', $to);
        }

        if (($weekday = request('weekday')) !== null) {
            $mysqlWeekday = ($weekday + 6) % 7;
            $query = $query->whereRaw("WEEKDAY(evenement_begin_datum) = ?", [$mysqlWeekday]);
        }



        return $query;
    }

    /**
     * Haalt een lijst op van alle beschikbare datums waarop evenementen plaatsvinden
     *
     * @param Request $request HTTP-request met (mogelijk) filters
     * @return JsonResponse Een JSON-array met alle beschikbare datums
     */
    public function getAvailableDates(Request $request)
    {
        $query = self::applyFilters(Evenement::query());

        $dates = $query->selectRaw('DATE(evenement_begin_datum) as date')
            ->distinct()
            ->pluck('date');

        // Return als JSON
        return response()->json($dates);
    }


}

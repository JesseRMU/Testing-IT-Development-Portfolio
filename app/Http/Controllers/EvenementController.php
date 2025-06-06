<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Services\WaarschuwingService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use App\Models\Wachthaven;
use App\Models\Steiger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Haal de evenementen op uit de database
        $evenementen = self::applyFilters( Evenement::with(['wachthaven', 'steiger']) )->paginate(10);
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
    public function destroy(Evenement $evenement)
    {
        //
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

        // Query evenementen, groeperen en tellen
        $data = DB::table('evenementen')
            ->select($groupColumn, DB::raw('COUNT(*) AS total')) // Selecteer label en total
            ->groupBy('label') // Groep op label
            ->orderByRaw("
            CASE
                WHEN '$timeGrouping' = 'day_of_week' THEN FIELD(label, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
                WHEN '$timeGrouping' = 'month_of_year' THEN FIELD(label, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')
                ELSE label
            END
        ") // Optionele logische sortering
            ->get();

        // Verwerk de data voor de grafiek (labels en datasets)
        $labels = $data->pluck('label')->toArray();
        $values = $data->pluck('total')->toArray();

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
    public static function applyFilters($query){
        // zo nodig een inner join doen op wachthavens, zodat we kunnen filteren op object_id
        if(!is_null(request("object_id"))){
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
            "schip_containers_type",
            "schip_containers_teus"
        ];
        foreach ($checkbox as $name){
            $query = self::applyCheckboxFilter($query, $name);
        }
        $nummer = [
            "schip_laadvermogen",
            "lengte",
            "breedte",
            "diepgang",
            "schip_containers_aantal"
        ];
        foreach ($nummer as $name){
            $query = self::applyNumberFilter($query, $name);
        }

        $query = self::applyCheckboxFilter($query, "object_id", "wachthavens");
        return $query;
    }

    /**
     * @param $query Illuminate\Database\Query\Builder | Illuminate\Database\Eloquent\Builder
     * @param $name string
     * @param $table string
     * @return Illuminate\Database\Query\Builder | Illuminate\Database\Eloquent\Builder
     */
    public static function applyCheckboxFilter($query, $name, $table = "evenementen"){
        $values = request($name);
        if(isset($values) && is_array($values)){
            $query = $query->where(function ($iquery) use ($values, $name, $table) {
                foreach ($values as $value) {
                    if($value == "null") {
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
    public static function applyNumberFilter($query, $name, $table = "evenementen"){
        $values = request($name);
        if(isset($values) && !is_null($values["min"])){
            $query = $query->where($table.'.'.$name, ">=", $values["min"]);
        }
        if(isset($values) && !is_null($values["max"])){
            $query = $query->where($table.'.'.$name, "<=", $values["max"]);
        }
        return $query;
    }

}

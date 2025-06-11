<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
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
        $evenementen = Evenement::with(['wachthaven', 'steiger'])->paginate(10);

        // Check of deze data wordt opgehaald
        if ($evenementen->isEmpty()) {
            dd('Geen evenementen gevonden in de database.');
        }
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
        $steigers = $steigers->groupBy('wachthaven_id');

        // Variabelen klaarzetten
        $waarschuwingen = collect();
        $steigeraantalperlocatie = collect();

        // Steigers groeperen per dag
        $evenementenperdag = $evenementen->groupBy([function ($item) {
            return \Carbon\Carbon::parse($item->evenement_begin_datum)->format('Y-m-d');
        }, 'wachthaven_id']);

        // Steiger aantal per wachthaven
        foreach ($steigers as $steiger) {
            $steigeraantalperlocatie->put($steiger[0]->wachthaven_id, count($steiger));
        }

        // Steigers per dag naar waarschuwingen converteren
        foreach ($evenementenperdag as $datum => $evenementenperwachthaven) {
            $wachthavenid = $evenementenperwachthaven->keys()->first();
            foreach ($evenementenperwachthaven as $evenementen) {
                if ($evenementen->count() > $steigeraantalperlocatie->get($wachthavenid)) {
                    $waarschuwingen->push(['locatie' => $wachthavens->get($wachthavenid)->wachthaven_naam,
                    'datum' => $datum,
                    'percentage' => round(($evenementen->count() / $steigeraantalperlocatie->get($wachthavenid))
                    * 100),
                    'evenementen' => $evenementen->count(),
                    'steigers' => $steigeraantalperlocatie->get($wachthavenid)]);
                }
            }
        }

        return $waarschuwingen;
    }
}

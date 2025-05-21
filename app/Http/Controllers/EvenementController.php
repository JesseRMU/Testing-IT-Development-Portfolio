<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;

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
        return view('index', compact('chartData'));
    }

    private function getChartDataGroupedBy($timeGrouping)
    {
        // Hier wordt de dataset aangepast (dummy data voor voorbeeld)
        $data = [
            'day_of_week' => ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'],
            'hour_of_day' => range(0, 23),
            'week_of_year' => range(1, 52),
            'month_of_year' => ['Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec']
        ];

        // Dummy random waarden voor grafiek (gebruik echt data in de praktijk)
        $values = array_map(fn () => rand(10, 100), $data[$timeGrouping]);

        return [
            'labels' => $data[$timeGrouping],
            'datasets' => [
                [
                    'label' => 'Data',
                    'data' => $values,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }
}

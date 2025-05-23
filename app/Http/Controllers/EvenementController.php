<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Wachthaven;
use App\Models\Steiger;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evenementen = Evenement::paginate(100);
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
     * Retrieve warnings for all events.
     */
    public static function getWarnings () {
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
        foreach ($evenementenperdag as $datum=>$evenementenperwachthaven) {
            $wachthavenid = $evenementenperwachthaven->keys()->first();
            foreach ($evenementenperwachthaven as $evenementen) {
                if ($evenementen->count() > $steigeraantalperlocatie->get($wachthavenid)) {
                    $waarschuwingen->push(['locatie' => $wachthavens->get($wachthavenid)->wachthaven_naam, 'datum' => $datum, 'percentage' => round(($evenementen->count() / $steigeraantalperlocatie->get($wachthavenid)) * 100), 'evenementen' => $evenementen->count(), 'steigers' => $steigeraantalperlocatie->get($wachthavenid)]);
                }
            }
        }

        return $waarschuwingen;
    }
}

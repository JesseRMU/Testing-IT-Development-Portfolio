<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Carbon\Carbon;

class WaarschuwingService
{
    /**
     * @param Collection $evenementen   Collection van evenementen
     * @param Collection $steigers      Collection van steigers
     * @param Collection $wachthavens   Collection van wachthavens
     * @return Collection
     */
    public static function getWarnings(
            Collection $evenementen,
            Collection $steigers,
            Collection $wachthavens
        ): Collection {
        $steigers = $steigers->groupBy('wachthaven_id');
        $steigeraantalperlocatie = collect();

        foreach ($steigers as $wachthavenId => $steigerGroup) {
            $steigeraantalperlocatie->put($wachthavenId, count($steigerGroup));
        }

        // Group evenementen by date and then by wachthaven
        $evenementenPerDag = collect($evenementen)->groupBy(function ($item) {
            return Carbon::parse($item['evenement_begin_datum'])->format('Y-m-d');
        })->map(function ($items) {
            return collect($items)->groupBy('wachthaven_id');
        });

        $waarschuwingen = collect();

        foreach ($evenementenPerDag as $datum => $perWachthaven) {
            foreach ($perWachthaven as $wachthavenId => $items) {
                $aantalSteigers = $steigeraantalperlocatie->get($wachthavenId, 0);
                if ($items->count() > $aantalSteigers) {
                    $waarschuwingen->push([
                        'locatie' => $wachthavens[$wachthavenId]['wachthaven_naam'] ?? 'Onbekend',
                        'datum' => $datum,
                        'percentage' => round(($items->count() / max($aantalSteigers, 1)) * 100),
                        'evenementen' => $items->count(),
                        'steigers' => $aantalSteigers,
                    ]);
                }
            }
        }

        return $waarschuwingen;
    }
}

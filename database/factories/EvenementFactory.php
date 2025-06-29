<?php

namespace Database\Factories;

use App\Models\Evenement;
use App\Models\Wachthaven;
use App\Models\Steiger;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvenementFactory extends Factory
{
    protected $model = Evenement::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('2024-01-01', '2024-12-31');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 7) . ' days');

        return [
            'wachthaven_id' => Wachthaven::factory(),
            'steiger_id' => Steiger::factory(),
            'evenement_begin_datum' => $startDate->format('Y-m-d H:i:s'),
            'evenement_eind_datum' => $endDate->format('Y-m-d H:i:s'),
            'evenement_vaarrichting' => $this->faker->randomElement(['aankomst', 'vertrek', 'doorvaart']),
            'naam_ivs90_bestand' => $this->faker->word . '.txt',
            'regelnummer_in_bron' => $this->faker->unique()->numberBetween(1, 10000),
            'datum_inlezen' => $this->faker->dateTimeThisYear(),
            'vlag_code' => $this->faker->numberBetween(100, 999),
            'schip_laadvermogen' => $this->faker->numberBetween(100, 50000),
            'lengte' => $this->faker->numberBetween(20, 400),
            'breedte' => $this->faker->numberBetween(5, 60),
            'diepgang' => $this->faker->numberBetween(1, 20),
            'schip_onderdeel_code' => $this->faker->randomElement(['M', 'D', 'B']),
            'schip_beladingscode' => $this->faker->numberBetween(1, 9),
            'schip_type' => $this->faker->numberBetween(1, 99),
        ];
    }
}

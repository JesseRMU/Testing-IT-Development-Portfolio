<?php

namespace Database\Factories;

use App\Models\Evenement;
use App\Models\Wachthaven;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvenementFactory extends Factory
{
    protected $model = Evenement::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 week');
        $endDate = (clone $startDate)->modify('+1 day');

        return [
            'wachthaven_id' => Wachthaven::factory(),
            'evenement_begin_datum' => $startDate->format('Y-m-d'),
            'evenement_eind_datum' => $endDate->format('Y-m-d'),
            'naam_ivs90_bestand' => "Test",
            'regelnummer_in_bron' => $this->faker->unique()->numberBetween(1, 1000),
        ];
    }
}

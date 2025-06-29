<?php

namespace Database\Factories;

use App\Models\Evenement;
use App\Models\Wachthaven;
use App\Models\Steiger;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EvenementFactory extends Factory
{
    protected $model = Evenement::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('2024-01-01', '2024-12-31');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 7) . ' days');

        // Create wachthaven first, then steiger that belongs to it
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        return [
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
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

    /**
     * Create test data specifically for 2024 date range testing
     */
    public function year2024(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = $this->faker->dateTimeBetween('2024-01-01', '2024-12-31');
            $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 7) . ' days');

            return [
                'evenement_begin_datum' => $startDate->format('Y-m-d H:i:s'),
                'evenement_eind_datum' => $endDate->format('Y-m-d H:i:s'),
            ];
        });
    }

    /**
     * Create test data outside 2024 for edge case testing
     */
    public function outsideYear2024(): static
    {
        return $this->state(function (array $attributes) {
            $year = $this->faker->randomElement([2023, 2025]);
            $startDate = $this->faker->dateTimeBetween("{$year}-01-01", "{$year}-12-31");
            $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 7) . ' days');

            return [
                'evenement_begin_datum' => $startDate->format('Y-m-d H:i:s'),
                'evenement_eind_datum' => $endDate->format('Y-m-d H:i:s'),
            ];
        });
    }

    /**
     * Create events for a specific date range
     */
    public function betweenDates(string $startDate, string $endDate): static
    {
        return $this->state(function (array $attributes) use ($startDate, $endDate) {
            $beginDate = $this->faker->dateTimeBetween($startDate, $endDate);
            $eindDate = (clone $beginDate)->modify('+' . $this->faker->numberBetween(1, 3) . ' days');

            return [
                'evenement_begin_datum' => $beginDate->format('Y-m-d H:i:s'),
                'evenement_eind_datum' => $eindDate->format('Y-m-d H:i:s'),
            ];
        });
    }
}

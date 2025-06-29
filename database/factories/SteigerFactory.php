<?php

namespace Database\Factories;

use App\Models\Steiger;
use App\Models\Wachthaven;
use Illuminate\Database\Eloquent\Factories\Factory;

class SteigerFactory extends Factory
{
    protected $model = Steiger::class;

    public function definition()
    {
        return [
            'wachthaven_id' => Wachthaven::factory(),
            'steiger_code' => 'ST-' . $this->faker->unique()->numberBetween(1000, 9999),
            'latitude' => $this->faker->latitude(51.0, 53.0), // Nederland coordinaten
            'longitude' => $this->faker->longitude(3.0, 7.0),
        ];
    }

    // Steiger met geldige coördinaten
    public function withValidCoordinates(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'latitude' => $this->faker->latitude(51.0, 53.0),
                'longitude' => $this->faker->longitude(3.0, 7.0),
            ];
        });
    }

    // Steiger zonder coördinaten
    public function withoutCoordinates(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'latitude' => null,
                'longitude' => null,
            ];
        });
    }
}

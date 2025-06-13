<?php

namespace Database\Factories;

use App\Models\Wachthaven;
use Illuminate\Database\Eloquent\Factories\Factory;

class WachthavenFactory extends Factory
{
    protected $model = Wachthaven::class;

    public function definition()
    {
        return [
            'wachthaven_id' => $this->faker->unique()->numberBetween(1, 1000),
            'wachthaven_naam' => $this->faker->company . ' Test Haven',
            // Add any additional fields here if your schema has them
        ];
    }
}

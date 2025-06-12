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
            'steiger_naam' => $this->faker->word . ' Steiger',
            'steiger_code' => $this->faker->unique()->numberBetween(1, 100),
        ];
    }
}

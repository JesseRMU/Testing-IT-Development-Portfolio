<?php

namespace Database\Seeders;

use App\Models\Steiger;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SteigerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $steigers = json_decode(file_get_contents(database_path('seeders/export steigers table.json')), false);
        foreach ($steigers->rows as $steiger) {
            $a = ["steiger_id" => $steiger[0],
                "wachthaven_id" => $steiger[1],
                "steiger_code" => $steiger[2],
                "latitude" => $steiger[4],
                "longitude" => $steiger[5],
                ];
            $s = new Steiger($a);
            $s->save();
        }
    }
}

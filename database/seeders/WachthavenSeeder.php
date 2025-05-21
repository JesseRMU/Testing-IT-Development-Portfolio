<?php

namespace Database\Seeders;

use App\Models\Wachthaven;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WachthavenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('wachthavens')->insert([
            ["wachthaven_id"=>5, "wachthaven_naam"=>"Wachthaven Krammersluizen Oost", "object_id"=> 0],
            ["wachthaven_id"=>4, "wachthaven_naam"=>"Wachthaven Krammersluizen West", "object_id"=> 0],
            ["wachthaven_id"=>6, "wachthaven_naam"=>"Wachthaven Kreekraksluizen Noord", "object_id"=> 1],
            ["wachthaven_id"=>7, "wachthaven_naam"=>"Wachthaven Kreekraksluizen Zuid", "object_id"=> 1],
            ["wachthaven_id"=>1, "wachthaven_naam"=>"Wachthaven Sluis Hansweert Zuid", "object_id"=> 2],
            ["wachthaven_id"=>3, "wachthaven_naam"=>"Wachthaven Tholen", "object_id"=> 3],
            ["wachthaven_id"=>2, "wachthaven_naam"=>"Wachthaven Werf Reimerswaal", "object_id"=> 4]
        ]);
    }
}

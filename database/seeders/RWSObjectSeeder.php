<?php

namespace Database\Seeders;

use App\Models\Wachthaven;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RWSObjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rws_objecten')->insert([
            ["object_id"=>0, "object_naam"=>"Krammersluizen"],
            ["object_id"=>1, "object_naam"=>"Kreekraksluizen"],
            ["object_id"=>2, "object_naam"=>"Sluis Hansweert"],
            ["object_id"=>3, "object_naam"=>"Wachthaven Tholen"],
            ["object_id"=>4, "object_naam"=>"Werf Reimerswaal"]
        ]);
    }
}

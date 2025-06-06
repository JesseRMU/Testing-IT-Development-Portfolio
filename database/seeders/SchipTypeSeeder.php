<?php

namespace Database\Seeders;

use App\Models\Steiger;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schip_types')->insert(
            [
                ["schip_type" => 0, "schip_type_naam" => "Vaartuigtype onbekend"],
                ["schip_type" => 1, "schip_type_naam" => "Motorvrachtschip"],
                ["schip_type" => 2, "schip_type_naam" => "Motortankschip"],
                ["schip_type" => 3, "schip_type_naam" => "Containerschip"],
                ["schip_type" => 4, "schip_type_naam" => "Gas-tankschip"],
                ["schip_type" => 5, "schip_type_naam" => "Slepend motorvrachtschip"],
                ["schip_type" => 6, "schip_type_naam" => "Slepend motortankschip"],
                ["schip_type" => 7, "schip_type_naam" => "Breed samenstel, motorvrachtschip"],
                ["schip_type" => 8, "schip_type_naam" => "Breed samenstel, minimaal 1 motortankschip"],
                ["schip_type" => 9, "schip_type_naam" => "Lang samenstel, motorvrachtschip"],
                ["schip_type" => 10, "schip_type_naam" => "Lang samenstel, minimaal 1 motortankschip"],
                ["schip_type" => 11, "schip_type_naam" => "Sleep-vrachtschip"],
                ["schip_type" => 12, "schip_type_naam" => "Sleep-tankschip"],
                ["schip_type" => 13, "schip_type_naam" => "Gekoppelde sleep-vrachtschepen"],
                ["schip_type" => 14, "schip_type_naam" => "Gekoppelde sleep- vrachtschepen, minimaal 1 slepend motortankschip"],
                ["schip_type" => 15, "schip_type_naam" => "Vrachtduwbak"],
                ["schip_type" => 16, "schip_type_naam" => "Tankduwbak"],
                ["schip_type" => 17, "schip_type_naam" => "Vrachtduwbak met containers"],
                ["schip_type" => 18, "schip_type_naam" => "Gas-tankduwbak"],
                ["schip_type" => 21, "schip_type_naam" => "Duwboot met 1 vrachtduwbak"],
                ["schip_type" => 22, "schip_type_naam" => "Duwboot met 2 vrachtduwbakken"],
                ["schip_type" => 23, "schip_type_naam" => "Duwboot met 3 vrachtduwbakken"],
                ["schip_type" => 24, "schip_type_naam" => "Duwboot met 4 vrachtduwbakken"],
                ["schip_type" => 25, "schip_type_naam" => "Duwboot met 5 vrachtduwbakken"],
                ["schip_type" => 26, "schip_type_naam" => "Duwboot met 6 vrachtduwbakken"],
                ["schip_type" => 27, "schip_type_naam" => "Duwboot met 7 vrachtduwbakken"],
                ["schip_type" => 28, "schip_type_naam" => "Duwboot met 8 vrachtduwbakken"],
                ["schip_type" => 29, "schip_type_naam" => "Duwboot >8 vrachtduwbakken"],
                ["schip_type" => 31, "schip_type_naam" => "Duwboot 1 gas-tankduwbak"],
                ["schip_type" => 32, "schip_type_naam" => "Duwboot 2 duwbakken waarvan 1 gas-tankduwbak"],
                ["schip_type" => 33, "schip_type_naam" => "Duwboot 3 duwbakken waarvan minstens 1 gas-tankduwbak"],
                ["schip_type" => 34, "schip_type_naam" => "Duwboot 4 duwbakken waarvan minstens 1 gas-tankduwbak"],
                ["schip_type" => 35, "schip_type_naam" => "Duwboot 5 duwbakken waarvan minstens 1 gas-tankduwbak"],
                ["schip_type" => 36, "schip_type_naam" => "Duwboot 6 duwbakken waarvan minstens 1 gas-tankduwbak"],
                ["schip_type" => 37, "schip_type_naam" => "Duwboot 7 duwbakken waarvan minstens 1 gas-tankduwbak"],
                ["schip_type" => 38, "schip_type_naam" => "Duwboot 8 duwbakken waarvan minstens 1 gas-tankduwbak"],
                ["schip_type" => 39, "schip_type_naam" => "Duwboot >8 duwbakken waarvan minstens 1 gas-tankduwbak"],
                ["schip_type" => 40, "schip_type_naam" => "Sleepboot losvarend"],
                ["schip_type" => 41, "schip_type_naam" => "Sleepboot sleepschepen"],
                ["schip_type" => 42, "schip_type_naam" => "Sleepboot assisterend"],
                ["schip_type" => 43, "schip_type_naam" => "Duwboot losvarend"],
                ["schip_type" => 44, "schip_type_naam" => "Passagiersschip"],
                ["schip_type" => 45, "schip_type_naam" => "Dienstvaartuig"],
                ["schip_type" => 46, "schip_type_naam" => "Werkvaartuig"],
                ["schip_type" => 47, "schip_type_naam" => "Gesleept object"],
                ["schip_type" => 48, "schip_type_naam" => "Vissersvaartuig binnenvaart"],
                ["schip_type" => 49, "schip_type_naam" => "Overige binnenvaart/bijzonder transport"],
                ["schip_type" => 50, "schip_type_naam" => "Zee-vrachtschip"],
                ["schip_type" => 51, "schip_type_naam" => "Zee-containerschip"],
                ["schip_type" => 52, "schip_type_naam" => "Zee-bulkcarrier"],
                ["schip_type" => 53, "schip_type_naam" => "Zee-tanker (geen gas)"],
                ["schip_type" => 54, "schip_type_naam" => "Zee-gastanker"],
                ["schip_type" => 60, "schip_type_naam" => "Zeesleepboot, bevoorradingsschip losvarend"],
                ["schip_type" => 61, "schip_type_naam" => "Zeesleepboot, bevoorradingsschip slepend"],
                ["schip_type" => 62, "schip_type_naam" => "Vissersvaartuig"],
                ["schip_type" => 63, "schip_type_naam" => "Veerboot, ro-ro schip niet uitsluitend vrachtvervoerend"],
                ["schip_type" => 64, "schip_type_naam" => "Zeegaand passagiersschip"],
                ["schip_type" => 65, "schip_type_naam" => "Zeegaand dienstvaartuig"],
                ["schip_type" => 66, "schip_type_naam" => "Zeegaand werkvaartuig"],
                ["schip_type" => 67, "schip_type_naam" => "Gesleept zeegaand object"],
                ["schip_type" => 68, "schip_type_naam" => "Marinevaartuig"],
                ["schip_type" => 69, "schip_type_naam" => "Overige zeegaande vaartuigen en drijvende objecten"],
                ["schip_type" => 80, "schip_type_naam" => "Motorjacht"],
                ["schip_type" => 81, "schip_type_naam" => "Speedboot"],
                ["schip_type" => 82, "schip_type_naam" => "Zeiljacht varend op hulpmotor"],
                ["schip_type" => 83, "schip_type_naam" => "Zeilend jacht"],
                ["schip_type" => 84, "schip_type_naam" => "Vaartuig voor sportvissers"],
                ["schip_type" => 85, "schip_type_naam" => "Grote recreatievaart >20m"],
                ["schip_type" => 89, "schip_type_naam" => "Overige recreatievaartuigen (roeiboot, kano, rubberboot, zeilplank etc.)"],
                ["schip_type" => 90, "schip_type_naam" => "Snel schip"]
            ]
        );
    }
}

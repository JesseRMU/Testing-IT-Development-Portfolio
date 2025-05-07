<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evenementen', function (Blueprint $table) {
            $table->text('naam_ivs90_bestand');
            $table->integer('regelnummer_in_bron');
            //deze twee velden zijn samen uniek. (vgm)

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenementen', function (Blueprint $table) {
            $table->dropColumn('naam_ivs90_bestand');
            $table->dropColumn('regelnummer_in_bron');
        });
    }
};

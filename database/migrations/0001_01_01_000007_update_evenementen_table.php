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
            $table->index(['naam_ivs90_bestand', 'regelnummer_in_bron'], "unieke_index");
            //deze twee velden zijn samen uniek. (vgm)
            $table->datetime('datum_inlezen')->nullable();
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
            $table->dropIndex('evenementen_naam_regel_index');
            $table->dropColumn('datum_inlezen');
        });
    }
};

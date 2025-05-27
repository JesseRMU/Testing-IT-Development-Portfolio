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
        Schema::create('evenementen', function (Blueprint $table) {
            $table->id('evenement_id');
            //$table->foreignId('schip_id');
            $table->foreignId('wachthaven_id')->nullable();
            $table->foreignId('steiger_id')->nullable();
            $table->datetime('evenement_begin_datum')->nullable();
            $table->datetime('evenement_eind_datum')->nullable();
            $table->string('evenement_vaarrichting')->nullable();

            $table->string('naam_ivs90_bestand');
            $table->integer('regelnummer_in_bron');
            $table->index(['naam_ivs90_bestand', 'regelnummer_in_bron'], "unieke_index");
            //deze twee velden zijn samen uniek. (vgm)
            $table->datetime('datum_inlezen')->nullable();

            $table->integer('vlag_code')->unsigned()->nullable();
            //$table->string('schip_naam');
            $table->integer('schip_laadvermogen')->unsigned()->nullable();
            $table->integer('lengte')->unsigned()->nullable();
            $table->integer('breedte')->unsigned()->nullable();
            $table->integer('diepgang')->unsigned()->nullable();
            $table->string('schip_onderdeel_code')->nullable();;
            $table->integer( 'schip_beladingscode')->unsigned()->nullable();
            $table->integer('schip_lading_system_code')->unsigned()->nullable();
            $table->integer('schip_lading_nstr')->unsigned()->nullable();
            $table->integer('schip_lading_reserve')->unsigned()->nullable();
            $table->integer('schip_lading_vn_nummer')->unsigned()->nullable();
            $table->double('schip_lading_klasse')->unsigned()->nullable();
            $table->string('schip_lading_code')->nullable();
            $table->string('schip_lading_1e_etiket')->nullable();
            $table->string('schip_lading_2e_etiket')->nullable();
            $table->string('schip_lading_3e_etiket')->nullable();
            $table->integer('schip_lading_verpakkingsgroep')->unsigned()->nullable();
            $table->integer('schip_lading_marpol')->unsigned()->nullable();
            $table->integer('schip_lading_seinvoering_kegel')->unsigned()->nullable();
            $table->integer('schip_vervoerd_gewicht')->unsigned()->nullable();
            $table->integer('schip_aantal_passagiers')->unsigned()->nullable();
            $table->string('schip_avv_klasse')->nullable();

            $table->integer('schip_containers')->unsigned()->nullable();
            $table->integer('schip_containers_aantal')->unsigned()->nullable();
            $table->integer('schip_containers_type')->unsigned()->nullable();
            $table->integer('schip_containers_teus')->unsigned()->nullable();

            $table->integer('schip_type')->unsigned()->nullable();

            //$table->foreign('schip_id')->references('schip_id')->on('schepen');
            $table->foreign('wachthaven_id')->references('wachthaven_id')->on('wachthavens');
            $table->foreign('steiger_id')->references('steiger_id')->on('steigers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenementen');
    }
};

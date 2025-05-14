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
            $table->string('schip_id')->nullable()->change();
            $table->string('vlag_code')->nullable();
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
            $table->integer('schip_lading_klasse')->unsigned()->nullable();
            $table->string('schip_lading_code')->unsigned()->nullable();
            $table->integer('schip_lading_1e_etiket')->unsigned()->nullable();
            $table->integer('schip_lading_2e_etiket')->unsigned()->nullable();
            $table->integer('schip_lading_3e_etiket')->unsigned()->nullable();
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

            //$table->dropForeign('schip_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenementen', function (Blueprint $table) {
            $table->drip('schip_belading_type');
            $table->dropColumn('schip_lading_system_code');
            $table->dropColumn('schip_lading_nstr');
            $table->dropColumn('schip_lading_reserve');
            $table->dropColumn('schip_lading_vn_nummer');
            $table->dropColumn('schip_lading_klasse');
            $table->dropColumn('schip_lading_code');
            $table->dropColumn('schip_lading_1e_etiket');
            $table->dropColumn('schip_lading_2e_etiket');
            $table->dropColumn('schip_lading_3e_etiket');
            $table->dropColumn('schip_lading_verpakkingsgroep');
            $table->dropColumn('schip_lading_marpol');
            $table->dropColumn('schip_lading_seinvoering_kegel');
            $table->dropColumn('schip_vervoerd_gewicht');
            $table->dropColumn('schip_aantal_passagiers');
            $table->dropColumn('schip_avv_klasse');

            $table->dropColumn('schip_containers');
            $table->dropColumn('schip_containers_aantal');
            $table->dropColumn('schip_containers_type');
            $table->dropColumn('schip_containers_teus');
            //$table->foreign('schip_id')->references('schip_id')->on('schepen');
        });
    }
};

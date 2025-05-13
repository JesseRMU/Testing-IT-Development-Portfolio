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
        Schema::table('schepen', function (Blueprint $table) {
            $table->renameColumn('schip_belading_type', 'schip_beladingscode');
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schepen', function (Blueprint $table) {
            $table->renameColumn('schip_beladingscode', 'schip_belading_type');
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
        });
    }
};

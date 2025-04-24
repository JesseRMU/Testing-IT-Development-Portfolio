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
        Schema::create('schepen', function (Blueprint $table) {
            $table->id('schip_id');
            $table->string('vlag_code');
            $table->integer('schip_belading_type');
            $table->string('schip_naam');
            $table->integer('schip_laadvermogen');
            $table->integer('lengte');
            $table->integer('breedte');
            $table->integer('diepgang');
            $table->string('schip_onderdeel_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schepen');
    }
};

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
            $table->foreignId('schip_id');
            $table->foreignId('wachthaven_id')->nullable();
            $table->foreignId('steiger_id')->nullable();
            $table->datetime('evenement_begin_datum')->nullable();
            $table->datetime('evenement_eind_datum')->nullable();
            $table->string('evenement_vaarrichting')->nullable();

            $table->foreign('schip_id')->references('schip_id')->on('schepen');
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

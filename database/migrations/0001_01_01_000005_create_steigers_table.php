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
        Schema::create('steigers', function (Blueprint $table) {
            $table->id('steiger_id');
            $table->foreignId('wachthaven_id');
            $table->string('steiger_code');
            $table->string('steiger_naam');

            $table->foreign('wachthaven_id')->references('wachthaven_id')->on('wachthavens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steigers');
    }
};

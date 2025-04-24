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
            $table->foreignId('object_id');
            $table->string('steiger_code');
            $table->string('steiger_naam');

            $table->foreign('object_id')->references('object_id')->on('objecten');
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

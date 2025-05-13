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
            $table->string('vlag_code')->nullable()->change();
            $table->integer('schip_belading_type')->nullable()->change();
            $table->string('schip_naam')->nullable()->change();
            $table->integer('schip_laadvermogen')->nullable()->change();
            $table->integer('lengte')->nullable()->change();
            $table->integer('breedte')->nullable()->change();
            $table->integer('diepgang')->nullable()->change();
            $table->string('schip_onderdeel_code')->nullable()->change();
        });
        Schema::table('steigers', function (Blueprint $table) {
            $table->string('steiger_naam')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schepen', function (Blueprint $table) {
        });
    }
};

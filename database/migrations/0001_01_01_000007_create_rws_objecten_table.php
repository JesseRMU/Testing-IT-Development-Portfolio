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
        Schema::create('rws_objecten', function (Blueprint $table) {
            $table->id('object_id');
            $table->string('object_naam');
        });
        Schema::table("wachthavens", function (Blueprint $table) {
            $table->foreignId('object_id')->nullable();
            $table->foreign('object_id')->references('object_id')->on('rws_objecten');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("wachthavens", function (Blueprint $table) {
            $table->dropForeign('wachthavens_object_id_foreign');
        });
        Schema::dropIfExists('rws_objecten');
    }
};

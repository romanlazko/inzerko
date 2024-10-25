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
        Schema::table('geo', function (Blueprint $table) {
            $table->renameColumn('lat', 'latitude');
            $table->renameColumn('long', 'longitude');
        });

        Schema::table('geo', function (Blueprint $table) {
            $table->double('latitude')->change();
            $table->double('longitude')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('geo', function (Blueprint $table) {
            $table->renameColumn('latitude', 'lat');
            $table->renameColumn('longitude', 'long');
        });
    }
};

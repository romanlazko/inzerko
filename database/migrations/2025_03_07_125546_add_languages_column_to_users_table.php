<?php

use App\Models\User;
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
        Schema::table('users', function (Blueprint $table) {
            $table->json('languages')->after('locale')->nullable();
        });

        User::all()->each(function ($user) {
            $languages = [];

            foreach ($user->communication_settings->languages ?? [] as $value) {
                $languages[] = $value;
            }

            $user->update(['languages' => $languages]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('languages');
        });
    }
};

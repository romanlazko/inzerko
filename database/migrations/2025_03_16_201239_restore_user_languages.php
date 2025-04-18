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
        User::all()->each(function ($user) {
            $languages = [];

            foreach ($user->communication_settings?->languages ?? [] as $value) {
                $languages[] = $value;
            }

            $user->update(['languages' => $languages]);
        });
    }

    public function down(): void
    {
        User::all()->each(function ($user) {
            $user->update(['languages' => null]);
        });
    }
};

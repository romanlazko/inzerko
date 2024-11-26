<?php

use App\Enums\Status;
use App\Models\Category;
use App\Models\User;
use Igaster\LaravelCities\Geo;
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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->nullable();

            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(Category::class)->nullable();
            $table->foreignIdFor(Geo::class)->nullable();

            $table->integer('current_status')->default(Status::created)->nullable();
            $table->boolean('is_active')->default(true)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('current_status');
            $table->index('category_id');
            $table->index(['deleted_at', 'is_active', 'current_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

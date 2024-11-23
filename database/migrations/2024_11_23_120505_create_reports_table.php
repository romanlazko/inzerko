<?php

use App\Models\ReportOption;
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
        Schema::create('report_options', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->json('alternames')->nullable();
            $table->boolean('is_active')->nullable();
            $table->integer('order_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->morphs('reportable');
            $table->foreignIdFor(User::class, 'reporter_id');
            $table->foreignIdFor(ReportOption::class);
            $table->text('description')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

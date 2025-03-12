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
        Schema::create('attribute_sections', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->json('alternames')->nullable();
            $table->string('type')->nullable();
            $table->integer('order_number')->nullable();
            $table->boolean('is_active')->default(false)->nullable();
            $table->boolean('has_label')->default(true)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_sections');
    }
};

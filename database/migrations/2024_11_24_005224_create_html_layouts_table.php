<?php

use App\Models\HtmlLayout;
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
        Schema::create('html_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->text('blade')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('html_layoutables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HtmlLayout::class);
            $table->morphs('html_layoutable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('html_layouts');
        Schema::dropIfExists('html_layoutables');
    }
};

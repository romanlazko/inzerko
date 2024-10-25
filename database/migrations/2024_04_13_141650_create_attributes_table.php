<?php

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\AttributeSection;
use App\Models\Category;
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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->json('alterlabels')->nullable();
            $table->json('altersuffixes')->nullable();
            $table->json('visible')->nullable();
            $table->json('hidden')->nullable();
            $table->json('create_layout')->nullable();
            $table->json('filter_layout')->nullable();
            $table->json('show_layout')->nullable();
            $table->json('group_layout')->nullable();
            $table->string('default')->nullable();
            $table->boolean('is_translatable')->default(false);
            $table->boolean('is_feature')->default(false);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_always_required')->default(false);
            $table->boolean('is_readonly')->default(false);
            $table->boolean('is_sortable')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('attribute_category', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Attribute::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_category');
    }
};

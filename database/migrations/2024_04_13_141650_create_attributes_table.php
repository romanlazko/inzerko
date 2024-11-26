<?php

use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeOption;
use App\Models\Attribute\AttributeSection;
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
            $table->json('alterprefixes')->nullable();
            $table->json('altersuffixes')->nullable();

            $table->json('visible')->nullable();
            $table->json('hidden')->nullable();

            $table->json('create_layout')->nullable();
            $table->json('filter_layout')->nullable();
            $table->json('show_layout')->nullable();
            $table->json('group_layout')->nullable();

            $table->boolean('is_translatable')->default(false)->nullable();
            $table->boolean('is_feature')->default(false)->nullable();
            $table->boolean('is_required')->default(false)->nullable();
            $table->boolean('is_always_required')->default(false)->nullable();
            $table->boolean('is_active')->default(false)->nullable();

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

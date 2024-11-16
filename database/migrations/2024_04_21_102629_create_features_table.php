<?php

use App\Models\Announcement;
use App\Models\Attribute;
use App\Models\AttributeOption;
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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Announcement::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(Attribute::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(AttributeOption::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->text('translated_value')->fulltext()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('announcement_id');
            $table->index('attribute_id');
            $table->index(['announcement_id', 'attribute_id', 'deleted_at']);
            $table->index(['attribute_id', 'attribute_option_id']);
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Announcement;
use App\Models\TelegramChat;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcement_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Announcement::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(TelegramChat::class)->constrained()->onDelete('cascade');
            $table->integer('current_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_channels');
    }
};
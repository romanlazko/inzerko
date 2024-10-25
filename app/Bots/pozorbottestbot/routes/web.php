<?php

use App\Bots\pozorbottestbot\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->name('pozorbottestbot.')->prefix('pozorbottestbot')->group(function () {
    Route::name('announcement.')->prefix('announcement')->group(function () {
        Route::get('/create', [AnnouncementController::class, 'create'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('create');
    });
});
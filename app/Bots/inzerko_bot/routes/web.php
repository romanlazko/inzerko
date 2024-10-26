<?php

use Illuminate\Support\Facades\Route;
use App\Bots\inzerko_bot\Http\Controllers\AnnouncementController;
use App\Bots\inzerko_bot\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware(['web'])->name('inzerko_bot.')->prefix('inzerko_bot')->group(function () {
    Route::get('auth', [AuthenticatedSessionController::class, 'auth'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('auth');

    Route::middleware(['auth'])->name('announcement.')->prefix('announcement')->group(function () {
        Route::get('/create', [AnnouncementController::class, 'create'])
            ->name('create');
    });
});
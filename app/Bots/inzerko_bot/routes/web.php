<?php

use Illuminate\Support\Facades\Route;
use App\Bots\inzerko_bot\Http\Controllers\AnnouncementController;
use App\Bots\inzerko_bot\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Bots\inzerko_bot\Http\Controllers\Auth\ConnectTelegramController;
use App\Bots\inzerko_bot\Http\Controllers\Auth\VerifyEmailController;

Route::middleware(['web'])->name('inzerko_bot.')->prefix('inzerko_bot')->group(function () {
    Route::get('verify-email', [VerifyEmailController::class, 'veryfyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verify');

    Route::get('auth', [AuthenticatedSessionController::class, 'auth'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('auth');

    Route::middleware(['auth'])->group(function () {
        Route::get('announcement/create', [AnnouncementController::class, 'create'])
            ->name('announcement.create');

        Route::get('telegram-connect', [ConnectTelegramController::class, 'connectTelegram'])
            ->name('telegram.connect');

        Route::post('telegram-disconnect', [ConnectTelegramController::class, 'disconnectTelegram'])
            ->name('telegram.disconnect');
        
        Route::get('verify-telegram-connection/{telegram_chat_id}/{telegram_token}', [ConnectTelegramController::class, 'verifyTelegramConnection'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verify.telegram.connection');
    });
});
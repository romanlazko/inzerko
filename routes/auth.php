<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\ConnectTelegramController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\VerifyTelegramController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('verify-email-telegram', [VerifyEmailController::class, 'veryfyEmailTelegram'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify-telegram');

Route::middleware(['guest'])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])
                ->name('login');

    Route::post('login', [LoginController::class, 'store'])
                ->name('login.store');

    Route::get('auth', [AuthenticatedSessionController::class, 'create'])
                ->name('auth');

    Route::post('auth', [AuthenticatedSessionController::class, 'store'])
                ->middleware(['honey-recaptcha'])
                ->name('auth.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
    
});

Route::middleware('auth')->group(function () {

    Route::name('verification.')->group(function () {
        Route::get('verify-email', [VerifyEmailController::class, 'notice'])
            ->name('notice');

        Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, 'veryfyEmail'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verify');

        Route::post('email/verification-notification', [VerifyEmailController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('send');
    });

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

    Route::post('telegram-connect', [ConnectTelegramController::class, 'connectTelegram'])
                ->name('telegram.connect');

    Route::get('verify-telegram-connection/{telegram_chat_id}/{telegram_token}', [ConnectTelegramController::class, 'verifyTelegramConnection'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verify.telegram.connection');
});

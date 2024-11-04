<?php

use App\Bots\pozor_baraholka_bot\Http\Controllers\AnnouncementController;
use App\Bots\pozor_baraholka_bot\Http\Controllers\BaraholkaAnnouncementController;
use App\Bots\pozor_baraholka_bot\Http\Controllers\BaraholkaCategoryController;
use App\Bots\pozor_baraholka_bot\Http\Controllers\BaraholkaSubcategoryController;
use Illuminate\Support\Facades\Route;
use Romanlazko\Telegram\Http\Controllers\ChatController;
use Romanlazko\Telegram\Http\Controllers\GetContactController;
use Romanlazko\Telegram\Http\Controllers\MessageController;

Route::middleware(['web'])->prefix('admin/pozorbottestbot')->name('pozor_baraholka_bot.')->group(function () {
    Route::middleware(['web', 'auth', 'telegram:pozorbottestbot'])->group(function () {
        Route::resource('announcement', AnnouncementController::class);
        Route::resource('category', BaraholkaCategoryController::class);
        Route::resource('subcategory', BaraholkaSubcategoryController::class);

        Route::resource('chat', ChatController::class);

        Route::prefix('chat/{chat}')->group(function(){
            Route::get('/get-contact', GetContactController::class)->name('get-contact');
            Route::resource('message', MessageController::class);
        });
        
        // Route::resource('advertisement', AdvertisementController::class);
    });
});

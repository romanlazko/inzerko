<?php

use Illuminate\Support\Facades\Route;
use App\Bots\inzerko_bot\Http\Controllers\AnnouncementController;

// Route::middleware(['web'])->prefix('inzerko_bot')->name('inzerko_bot.')->group(function () {
//     Route::get('/page', function(){
//         return view('inzerko_bot::page');
//     })->name('page');

//     Route::name('announcement.')->prefix('announcement')->group(function () {
//         Route::get('/create', [AnnouncementController::class, 'create'])
//             ->middleware(['signed', 'throttle:6,1'])
//             ->name('create');
//     });
// });

Route::middleware(['web'])->name('inzerko_bot.')->prefix('inzerko_bot')->group(function () {
    Route::name('announcement.')->prefix('announcement')->group(function () {
        Route::get('/create', [AnnouncementController::class, 'create'])
            // ->middleware(['signed', 'throttle:6,1'])
            ->name('create');
    });
});
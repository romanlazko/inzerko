<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'telegram:intelegenttg_bot'])->name('intelegenttg_bot.')->group(function () {
    Route::get('/page', function(){
        return view('intelegenttg_bot::page');
    })->name('page');
});
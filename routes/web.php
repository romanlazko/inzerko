<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Profile\AnnouncementController as ProfileAnnouncementController;
use App\Http\Controllers\Profile\NotificationController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\SecurityController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/locale', [Controller::class, 'locale'])->name('locale');

Route::get('/', [Controller::class, 'home'])->name('home');

Route::get('page/{page:slug}', [PageController::class, 'show'])->name('page');

Route::name('announcement.')
    ->group(function () {
        Route::get('/all/{category:slug?}', [AnnouncementController::class, 'index'])->name('index');
        Route::get('/search/{category:slug?}', [AnnouncementController::class, 'search'])->name('search');
        Route::get('/show/{announcement:slug}', [AnnouncementController::class, 'show'])->name('show');
    });

Route::name('profile.')
    ->prefix('profile')
    ->group(function () {
        Route::get('show/{user:slug}', [ProfileController::class, 'show'])->name('show');

        Route::middleware(['auth'])
            ->group(function () {
                Route::get('/', [ProfileController::class, 'edit'])->name('edit');
                Route::view('banned', 'profile.banned')->middleware(['banned'])->name('banned');
                Route::view('security', 'profile.security')->name('security');
                Route::view('notification', 'profile.notification')->name('notification');

                Route::name('announcement.')
                    ->group(function () {
                        Route::get('index', [ProfileAnnouncementController::class, 'index'])->name('index');
                        Route::get('create', [ProfileAnnouncementController::class, 'create'])->middleware(['profile_filled', 'banned', 'verified'])->name('create');
                        Route::get('wishlist', [ProfileAnnouncementController::class, 'wishlist'])->name('wishlist');
                    });
            });
});

Route::get('run-schedule', function () {
    Artisan::call('schedule:run');
    return true;
});

require __DIR__.'/auth.php';



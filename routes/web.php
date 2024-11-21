<?php

use App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\SearchRequest;
use App\Models\Page;
use App\View\Models\HomeViewModel;
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

// Route::get('/map', OpsMap::class)->name('map');

Route::get('/locale', [Controller::class, 'locale'])->name('locale');

Route::get('/', function (SearchRequest $request) {
    session()->forget('filters');
    session()->forget('search');
    session()->forget('sort');

    $viewModel = new HomeViewModel();

    return response()->view('home', [
        'announcements' => $viewModel->getAnnouncements(),
        'categories' => $viewModel->getCategories(),
        'request' => $request,
    ])
    ->header('Cache-Control', 'private, max-age=0, must-revalidate');
})->name('home');

Route::get('page/{page:slug}', function (Page $page) {
    if (! $page->is_active) {
        abort(404);
    }

    return view('page', [
        'page' => $page
    ]);
})->name('page');

Route::get('user/{user}', [ProfileController::class, 'show'])->name('profile.show');

Route::controller(AnnouncementController::class)
    ->name('announcement.')
    ->group(function () {
        Route::get('/all/{category:slug?}', 'index')->name('index');
        Route::get('/search/{category:slug?}', 'search')->name('search');
        Route::get('/show/{announcement:slug}', 'show')->name('show');
        Route::get('/create', 'create')->middleware(['auth', 'verified', 'profile_filled'])->name('create');
    });

Route::middleware(['auth'])->name('profile.')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('update', [ProfileController::class, 'update'])->name('update');
    Route::patch('update-avatar', [ProfileController::class, 'updateAvatar'])->name('update-avatar');
    Route::patch('update-communication', [ProfileController::class, 'updateCommunication'])->name('update-communication');

    Route::get('security', [ProfileController::class, 'security'])->name('security');
    Route::put('update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    Route::delete('destroy', [ProfileController::class, 'destroy'])->name('destroy');

    Route::get('notifications', [ProfileController::class, 'notifications'])->name('notifications');
    Route::patch('update-notifications', [ProfileController::class, 'updateNotifications'])->name('update-notifications');

    Route::get('wishlist', [ProfileController::class, 'wishlist'])->name('wishlist');
    Route::get('my-announcements', [ProfileController::class, 'my_announcements'])->name('my-announcements');
});

Route::get('cron', function () {
    Artisan::call('queue:work --stop-when-empty --tries=2 --max-time=60');
    return true;
});

require __DIR__.'/auth.php';

Route::get('/test', function () {
    dump(BaraholkaAnnouncement::with('chat')->latest()->limit(10)->get());
});



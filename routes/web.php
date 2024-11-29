<?php

use App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Profile\AnnouncementController as ProfileAnnouncementController;
use App\Http\Controllers\Profile\NotificationController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\SecurityController;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\SearchRequest;
use App\Models\Page;
use App\Models\User;
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

Route::name('announcement.')
    ->group(function () {
        Route::get('/all/{category:slug?}', [AnnouncementController::class, 'index'])->name('index');
        Route::get('/search/{category:slug?}', [AnnouncementController::class, 'search'])->name('search');
        Route::get('/show/{announcement:slug}', [AnnouncementController::class, 'show'])->name('show');
    });

Route::name('profile.')->prefix('profile')->group(function () {
    Route::get('show/{user:slug}', [ProfileController::class, 'show'])->name('show');

    Route::middleware(['auth'])->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('update', [ProfileController::class, 'update'])->name('update');
        Route::patch('update-avatar', [ProfileController::class, 'updateAvatar'])->name('update-avatar');
        Route::patch('update-communication', [ProfileController::class, 'updateCommunication'])->name('update-communication');

        Route::name('security.')->prefix('security')->group(function () {
            Route::get('/', [SecurityController::class, 'edit'])->name('edit');
            Route::patch('update', [SecurityController::class, 'update'])->name('update');
            Route::delete('destroy', [SecurityController::class, 'destroy'])->name('destroy');
        });

        Route::name('notification.')->prefix('notification')->group(function () {
            Route::get('/', [NotificationController::class, 'edit'])->name('edit');
            Route::patch('update', [NotificationController::class, 'update'])->name('update');
        });

        Route::name('announcement.')->prefix('announcement')->group(function () {
            Route::get('index', [ProfileAnnouncementController::class, 'index'])->name('index');
            Route::get('create', [ProfileAnnouncementController::class, 'create'])->name('create');
            Route::get('wishlist', [ProfileAnnouncementController::class, 'wishlist'])->name('wishlist');
        });
    });
});

Route::get('run-schedule', function () {
    Artisan::call('schedule:run');
    return true;
});

require __DIR__.'/auth.php';

// Route::get('/test', function () {
//     dump(BaraholkaAnnouncement::with('chat')->latest()->limit(10)->get());
// });



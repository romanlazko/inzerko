<?php

use App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;
use App\Http\Requests\SearchRequest;
use App\Livewire\Pages\Admin\Announcement\Announcements;
use App\Livewire\Pages\Admin\Announcement\EditAnnouncement;
use App\Livewire\Pages\Admin\Announcement\Moderation;
use App\Livewire\Pages\Admin\CMS\Pages;
use App\Livewire\Pages\Admin\Settings\Attributes;
use App\Livewire\Pages\Admin\Settings\Categories;
use App\Livewire\Pages\Admin\Settings\Sections;
use App\Livewire\Pages\Admin\Settings\Sortings;
use App\Livewire\Pages\Admin\Telegram\Bots;
use App\Livewire\Pages\Admin\Telegram\Channels;
use App\Livewire\Pages\Admin\Telegram\Chats;
use App\Livewire\Pages\Admin\Telegram\Logs;
use App\Livewire\Pages\Admin\User\Users;
use App\Models\Page;
use App\View\Models\HomeViewModel;
use Illuminate\Http\Request;
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

Route::post('/locale', function (Request $request){
    if ($user = auth()->user()) {
        $user->update([
            'locale' => $request->locale
        ]);
    }

    session(['locale' => $request->locale]);

    return back();
})->name('locale');

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

Route::middleware(['auth', 'role:super-duper-admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::name('telegram.')->prefix('telegram')->group(function () {
        Route::get('bots', Bots::class)->name('bots');
        Route::get('{telegram_bot}/chats', Chats::class)->name('chats');
        Route::get('{telegram_bot}/channels', Channels::class)->name('channels');
        Route::get('{telegram_bot}/logs', Logs::class)->name('logs');
    });

    Route::name('announcement.')->prefix('announcement')->group(function () {
        Route::get('announcements', Announcements::class)->name('announcements');
        Route::get('moderation', Moderation::class)->name('moderation');
        Route::get('edit/{announcement}', EditAnnouncement::class)->name('edit');
    });

    Route::name('setting.')->prefix('setting')->group(function () {
        Route::get('categories/{category?}', Categories::class)->name('categories');
        Route::get('attributes', Attributes::class)->name('attributes');
        Route::get('sections', Sections::class)->name('sections');
        Route::get('sortings', Sortings::class)->name('sortings');
    });

    Route::name('users.')->prefix('users')->group(function () {
        Route::get('users', Users::class)->name('users');
    });

    Route::get('pages', Pages::class)->name('pages');
    
    Route::get('logs', fn () => redirect('admin/logs'))->name('logs');
});

Route::controller(AnnouncementController::class)->name('announcement.')->group(function () {
    Route::get('/all/{category:slug?}', 'index')->name('index');
    Route::get('/search/{category:slug?}', 'search')->name('search');
    Route::get('/show/{announcement:slug}', 'show')->name('show');
    Route::get('/create', 'create')->middleware(['auth', 'verified', 'profile_filled'])->name('create');
});

Route::middleware(['auth'])->name('profile.')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/update', [ProfileController::class, 'update'])->name('update');
    Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('destroy');
    Route::patch('/updateAvatar', [ProfileController::class, 'updateAvatar'])->name('updateAvatar');
    Route::get('/wishlist', [ProfileController::class, 'wishlist'])->name('wishlist');
    Route::get('/my-announcements', [ProfileController::class, 'my_announcements'])->name('my-announcements');
});

Route::get('cron', function () {
    Artisan::call('queue:work --stop-when-empty --tries=2 --max-time=60');
    return true;
});

require __DIR__.'/auth.php';

Route::get('/test', function () {
    dump(BaraholkaAnnouncement::with('chat')->latest()->limit(10)->get());
});



<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Admin\Dashboard;
use App\Livewire\Pages\Admin\User\Permissions;
use App\Livewire\Pages\Admin\User\Roles;
use App\Livewire\Pages\Admin\Announcement\Announcements;
use App\Livewire\Pages\Admin\Announcement\Archive as AnnouncementArchive;
use App\Livewire\Pages\Admin\Announcement\Edit;
use App\Livewire\Pages\Admin\Announcement\Moderation;
use App\Livewire\Pages\Admin\Announcement\Reports;
use App\Livewire\Pages\Admin\CMS\Pages;
use App\Livewire\Pages\Admin\Settings\Attributes;
use App\Livewire\Pages\Admin\Settings\Categories;
use App\Livewire\Pages\Admin\Settings\HtmlLayouts;
use App\Livewire\Pages\Admin\Settings\ReportOptions;
use App\Livewire\Pages\Admin\Settings\Sections;
use App\Livewire\Pages\Admin\Settings\Sortings;
use App\Livewire\Pages\Admin\Telegram\Bots;
use App\Livewire\Pages\Admin\Telegram\Channels;
use App\Livewire\Pages\Admin\Telegram\Chats;
use App\Livewire\Pages\Admin\Telegram\Logs;
use App\Livewire\Pages\Admin\User\Archive as UserArchive;
use App\Livewire\Pages\Admin\User\Users;


Route::get('/', Dashboard::class)->name('dashboard');

Route::name('telegram.')
    ->prefix('telegram')
    ->middleware('has_permission_to:view|manage,telegram')
    ->group(function () {
        Route::get('bots', Bots::class)->name('bots');
        Route::get('{telegram_bot}/chats', Chats::class)->name('chats');
        Route::get('{telegram_bot}/channels', Channels::class)->name('channels');
        Route::get('{telegram_bot}/logs', Logs::class)->name('logs');
    });

Route::name('announcement.')
    ->prefix('announcement')
    ->middleware('has_permission_to:view|manage,announcement')
    ->group(function () {
        Route::get('announcements', Announcements::class)
            ->middleware('has_permission_to:manage,announcement')
            ->name('announcements');
        Route::get('archive', AnnouncementArchive::class)
            ->middleware('has_permission_to:manage,announcement')
            ->name('archive');
        Route::get('moderation', Moderation::class)
            ->middleware('has_permission_to:view|moderate|manage,announcement')
            ->name('moderation');
        Route::get('edit/{announcement}', Edit::class)
            ->middleware('has_permission_to:update|manage,announcement')
            ->name('edit');
        Route::get('reports/{announcement?}', Reports::class)
            ->middleware('has_permission_to:moderate|manage,announcement')
            ->name('reports');
    });

Route::name('setting.')
    ->prefix('setting')
    ->middleware('has_permission_to:view|manage,setting')
    ->group(function () {
        Route::get('categories/{category?}', Categories::class)->name('categories');
        Route::get('attributes', Attributes::class)->name('attributes');
        Route::get('sections', Sections::class)->name('sections');
        Route::get('sortings', Sortings::class)->name('sortings');
        Route::get('report_options', ReportOptions::class)->name('report_options');
        Route::get('html_layouts', HtmlLayouts::class)->name('html_layouts');
    });

Route::name('users.')
    ->prefix('users')
    ->middleware('has_permission_to:view|manage,user')
    ->group(function () {
        Route::get('users', Users::class)
            ->middleware('has_permission_to:view|manage,user')
            ->name('users');

        Route::get('archive', UserArchive::class)
            ->middleware('has_permission_to:view|manage,user')
            ->name('archive');

        Route::get('roles', Roles::class)
            ->middleware('has_permission_to:view|manage,role')
            ->name('roles');
            
        Route::get('permissions', Permissions::class)
            ->middleware('has_permission_to:view|manage,permission')
            ->name('permissions');
    });

Route::get('pages', Pages::class)->name('pages');

Route::name('log.')
    ->prefix('log')
    ->middleware('has_permission_to:view|manage,log')
    ->group(function () {
        Route::get('telescope', fn () => redirect('telescope'))->name('telescope');
        Route::get('log-viewer', fn () => redirect('log-viewer'))->name('log-viewer');
    });


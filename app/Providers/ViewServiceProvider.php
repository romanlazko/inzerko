<?php

namespace App\Providers;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $pages = Cache::remember('pages', config('cache.ttl'), function () {
                return Page::where([
                    'is_active' => true,
                ])->get();
            });

            $view->with([
                'header_pages' => $pages->where('is_header', true),
                'footer_pages' => $pages->where('is_footer', true),
            ]);
        });
    }
}

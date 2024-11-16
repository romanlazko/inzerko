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
        // View::composer('*', function ($view) {
        //     $locale = session('locale', config('app.locale'));
        //     URL::defaults(['locale' => $locale]);
        // });
        // View::composer('announcement.show', function ($view) {
        //     $announcement = $view->getData()['announcement'];

        //     $title = $announcement?->getFeatureByName('title')?->value. " - " . $announcement?->getFeatureByName('current_price')?->value . " " . $announcement?->getFeatureByName('currency')?->value;
        //     $categories = $announcement?->categories->pluck('name')->implode(' | ');
        //     $description = $announcement?->getFeatureByName('description')?->value;

        //     $view->with('meta_tag_data', [
        //         'title' => $announcement?->getFeatureByName('title')?->value,
        //         'meta_title' => implode(" | ", [
        //             $title,
        //             $categories,
        //         ]),
        //         'description' => implode(" | ", [
        //             $title,
        //             $categories,
        //             $description,
        //         ]),
        //         'image_url' => $announcement?->getFirstMediaUrl('announcement'),
        //         'image_alt' => implode(" | ", [
        //             $title,
        //             $categories,
        //         ]),
        //         'price' => $announcement?->getFeatureByName('current_price')?->value,
        //         'currency' => $announcement?->getFeatureByName('currency')?->value,
        //         'category' => $categories,
        //         'author' => $announcement?->user->name,
        //         'date' => $announcement?->create_at,
        //     ]);
        // });
    }
}

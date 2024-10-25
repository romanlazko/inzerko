<?php

namespace App\Providers;

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
            $view->with('unreadMessagesCount', $this->getUnreadMessagesCount());
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

    private function getUnreadMessagesCount(): int
    {
        if (auth()->check()) {
            $unreadMessagesCount = Cookie::get('unreadMessagesCount');

            if (!$unreadMessagesCount) {
                $unreadMessagesCount = auth()->user()?->unreadMessagesCount;

                Cookie::queue('unreadMessagesCount', $unreadMessagesCount, 2);
            }
        }

        return $unreadMessagesCount ?? 0;
    }
}

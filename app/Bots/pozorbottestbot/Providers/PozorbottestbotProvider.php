<?php

namespace App\Bots\pozorbottestbot\Providers;

use Illuminate\Support\ServiceProvider;

class PozorbottestbotProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'pozorbottestbot');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    // Add the following line to config/app.php in the providers array: 
    // App\Bots\pozorbottestbot\Providers\PozorbottestbotProvider::class,
}

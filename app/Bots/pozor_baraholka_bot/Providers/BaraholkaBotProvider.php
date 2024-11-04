<?php

namespace App\Bots\pozor_baraholka_bot\Providers;

use Illuminate\Support\ServiceProvider;

class BaraholkaBotProvider extends ServiceProvider
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
    }

    // Add the following line to config/app.php in the providers array: 
    // App\Bots\pozorbottestbot\Providers\PozorbottestbotProvider
}

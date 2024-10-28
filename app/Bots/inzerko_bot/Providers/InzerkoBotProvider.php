<?php

namespace App\Bots\inzerko_bot\Providers;

use App\Models\TelegramBot;
use Illuminate\Support\ServiceProvider;
use Romanlazko\Telegram\App\Bot;

class InzerkoBotProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('inzerko', function () {
            return new Bot(TelegramBot::firstWhere('username', 'inzerko_bot')->token);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'inzerko_bot');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    // Add the following line to config/app.php in the providers array: 
    // App\Bots\inzerko_bot\Providers\InzerkoBotProvider::class,
}

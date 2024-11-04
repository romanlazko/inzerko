<?php

namespace App\Bots\pozorbottestbot\Providers;

use App\Models\TelegramBot;
use Illuminate\Support\ServiceProvider;
use Romanlazko\Telegram\App\Bot;

class PozorbottestbotProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('pozorbottestbot', function () {
            return new Bot(TelegramBot::firstWhere('username', 'pozorbottestbot')?->token);
        });
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

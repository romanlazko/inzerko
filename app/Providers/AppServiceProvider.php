<?php

namespace App\Providers;

use App\Services\Translators\DeepTranslate\DeepTranslate;
use App\Services\Translators\NlpTranslation\NlpTranslation;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Assets\Js;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\Models\TelegramBot;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('rapid-api-translator', function () {
            return new DeepTranslate(env('RAPID_API_KEY'));
        });

        $this->app->bind('bot', function () {
            return new Bot(env('TELEGRAM_BOT_TOKEN', TelegramBot::first()->token));
        });

        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind('htmlpurifier', function ($app) {
            $config = HTMLPurifier_Config::createDefault();
            return new HTMLPurifier($config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Indigo,
            'success' => Color::Green,
            'warning' => Color::Amber,
            'neutral' => Color::Neutral,
            'white' => Color::hex('#ffffff'),
        ]);

        FilamentAsset::register([
            Js::make('ops-map', __DIR__ . '../../../resources/js/ops-map.js'),
            Css::make('ops-map', __DIR__.'../../../resources/css/ops-map.css'),
        ]);

        LogViewer::auth(function ($request) {
            return true;
        });
    }
}

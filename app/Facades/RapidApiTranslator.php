<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RapidApiTranslator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rapid-api-translator';
    }

    public static function translateToMultipleLanguages(string $text)
    {
        $instance = static::getFacadeRoot();

        foreach (config('translate.languages') as $key => $locale) {
            $translated[$key] = $instance->text($text)->to($key)->translate()->getTranslatedText();
        }
        
        return $translated;
    }
}

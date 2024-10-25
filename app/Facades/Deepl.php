<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Deepl extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'deepl';
    }

    public static function translate(string $text)
    {
        $instance = static::getFacadeRoot();

        foreach (config('translate.languages') as $key => $locale) {
            $translated[$key] = $instance->translateText($text, null, $locale)->text;
        }
        
        return $translated;
    }
}

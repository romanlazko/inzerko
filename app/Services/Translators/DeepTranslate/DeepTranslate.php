<?php

namespace App\Services\Translators\DeepTranslate;

use App\Services\Translators\Contracts\RapidApiTranslatorContract;
use App\Services\Translators\Contracts\RapidApiTranslatorResponseContract;
use App\Services\Translators\RapidApiTranslator;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DeepTranslate extends RapidApiTranslator implements RapidApiTranslatorContract
{
    const HOST = 'deep-translate1.p.rapidapi.com';

    protected $url = 'https://deep-translate1.p.rapidapi.com/language/translate/v2';

    public function text($sourceText): self
    {
        $this->options['q'] = $sourceText;

        return $this;
    }

    public function from($sourceLang): self
    {
        $this->options['source'] = $sourceLang;

        return $this;
    }

    public function to($targetLang): self
    {
        $this->options['target'] = $targetLang;

        return $this;
    }

    public function translate(): RapidApiTranslatorResponseContract
    {
        $response = $this->send();

        return DeepResponse::fromResponse($response);
    }

    protected function getClient($authKey): PendingRequest
    {
        return Http::withHeaders([
            'x-rapidapi-host' => self::HOST,
            'x-rapidapi-key' => $authKey,
            'Content-Type' => 'application/json',
        ]);
    }
}
<?php

namespace App\Services\Translators\NlpTranslation;

use App\Services\Translators\Contracts\RapidApiTranslatorContract;
use App\Services\Translators\Contracts\RapidApiTranslatorResponseContract;
use App\Services\Translators\RapidApiTranslator;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class NlpTranslation extends RapidApiTranslator implements RapidApiTranslatorContract
{
    const HOST = 'nlp-translation.p.rapidapi.com';

    protected $url = 'https://nlp-translation.p.rapidapi.com/v1/translate';

    public function text($sourceText): self
    {
        $this->options['text'] = $sourceText;

        return $this;
    }

    public function from($sourceLang): self
    {
        $this->options['from'] = $sourceLang;

        return $this;
    }

    public function to($targetLang): self
    {
        $this->options['to'] = $targetLang;

        return $this;
    }

    public function translate(): RapidApiTranslatorResponseContract
    {
        $response = $this->send();

        return NlpResponse::fromResponse($response);
    }

    protected function getClient($authKey): PendingRequest
    {
        return Http::withHeaders([
            'x-rapidapi-host' => self::HOST,
            'x-rapidapi-key' => $authKey,
        ])->asMultipart();
    }
}
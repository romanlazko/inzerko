<?php 

namespace App\Services\Translators\DeepTranslate;

use App\Services\Translators\Contracts\RapidApiTranslatorResponseContract;

class DeepResponse implements RapidApiTranslatorResponseContract
{
    public function __construct(protected \Illuminate\Http\Client\Response $response)
    {
    }

    public static function fromResponse(\Illuminate\Http\Client\Response $response): self
    {
        return new self($response);
    }

    public function getTranslatedText(): ?string
    {
        return data_get($this->response, 'data.translations.translatedText');
    }
}
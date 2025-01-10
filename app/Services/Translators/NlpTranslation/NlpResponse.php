<?php 

namespace App\Services\Translators\NlpTranslation;

use App\Services\Translators\Contracts\RapidApiTranslatorResponseContract;

class NlpResponse implements RapidApiTranslatorResponseContract
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
        return data_get($this->response, 'translated_text.to');
    }
}
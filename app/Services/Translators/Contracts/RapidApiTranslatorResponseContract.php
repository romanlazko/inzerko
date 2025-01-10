<?php 

namespace App\Services\Translators\Contracts;

interface RapidApiTranslatorResponseContract
{
    public function __construct(\Illuminate\Http\Client\Response $response);

    public static function fromResponse(\Illuminate\Http\Client\Response $response): self;

    public function getTranslatedText(): ?string;
}
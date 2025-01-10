<?php 

namespace App\Services\Translators\Contracts;

interface RapidApiTranslatorContract
{
    public function __construct(string $authKey);
    public function text($sourceText): self;
    public function from($sourceLang): self;
    public function to($targetLang): self;
    public function translate(): RapidApiTranslatorResponseContract;
}
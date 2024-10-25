<?php

namespace App\Services\Translators;

use App\Services\Translators\Contracts\RapidApiTranslatorContract;
use Illuminate\Http\Client\PendingRequest;

abstract class RapidApiTranslator implements RapidApiTranslatorContract
{
    protected $client = null;

    protected $options = null;

    protected $url = null;

    public function __construct(string $authKey)
    {
        $this->client = $this->getClient($authKey);
    }

    protected function send(): Response
    {
        $this->validate();

        $response = $this->client->post($this->url, $this->options);

        return $this->executeResponse($response);
    }

    protected function validate()
    {
        if (!$this->client) {
            throw new \Exception('Client not set');
        }
        
        if (!$this->url) {
            throw new \Exception('Url not set');
        }

        if (!$this->options) {
            throw new \Exception('Options not set');
        }
    }

    protected function executeResponse($response): Response
    {
        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        return Response::fromResponse($response->json());
    }

    public abstract function text($sourceText): self;

    public abstract function from($sourceLang): self;

    public abstract function to($targetLang): self;

    public abstract function translate(): string;

    protected abstract function getClient($authKey): PendingRequest;
}
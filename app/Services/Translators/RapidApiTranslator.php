<?php

namespace App\Services\Translators;

use App\Services\Translators\Contracts\RapidApiTranslatorContract;
use App\Services\Translators\Contracts\RapidApiTranslatorResponseContract;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

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

        $this->validateResponse($response);

        return $response;
    }

    protected function validate(): void
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

    protected function validateResponse(Response $response): void
    {
        if ($response->failed()) {
            $response->throw();
            // throw new \Exception('Translation failed');
        }

    }

    protected abstract function getClient($authKey): PendingRequest;
}
<?php

namespace Omarsaiouf\Integrations\Http;

use Omarsaiouf\Integrations\Contracts\Http\HttpClient;
use Omarsaiouf\Integrations\Exceptions\HttpFailedException;

class HttpClientFactory
{
    public static function make(string $provider): HttpClient
    {
        return match ($provider) {
            "Http" => new LaravelHttpClient(),

            default => new LaravelHttpClient()
        };
    }
}
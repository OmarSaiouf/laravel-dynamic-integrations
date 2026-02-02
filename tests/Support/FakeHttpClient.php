<?php

namespace Omarsaiouf\Integrations\Tests\Support;

use Omarsaiouf\Integrations\Contracts\Http\HttpClient;
use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;

class FakeHttpClient implements HttpClient
{
    public function __construct(private HttpResponse $response)
    {
    }

    public function send(BuiltRequest $request): HttpResponse
    {
        return $this->response;
    }

    public function pool(array $requests): array
    {
        $out = [];
        foreach ($requests as $name => $_request) {
            $out[$name] = $this->response;
        }
        return $out;
    }
}

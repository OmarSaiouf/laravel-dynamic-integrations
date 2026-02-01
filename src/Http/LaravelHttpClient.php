<?php

namespace Omarsaiouf\Integrations\Http;

use Illuminate\Support\Facades\Http;
use Omarsaiouf\Integrations\Contracts\Http\HttpClient;
use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\Exceptions\HttpFailedException;

class LaravelHttpClient implements HttpClient
{
    public function send(BuiltRequest $request): HttpResponse
    {
        $pending = Http::withHeaders($request->headers)
            ->timeout(config('integrations.http.timeout', 15));

        $response = match ($request->method->value ?? $request->method) {
            'GET' => $pending->get($request->url, $request->query),
            'POST' => $pending->post($request->url, $request->body),
            default => throw new HttpFailedException("Unsupported HTTP method"),
        };

        if ($response->failed()) {
            throw new HttpFailedException(
                $response->body(),
                $response->status()
            );
        }

        return new HttpResponse(
            $response->status(),
            $response->headers(),
            $response->json(),
            $response->body(),
            in_array($response->status(), [200, 201])
        );


    }
}

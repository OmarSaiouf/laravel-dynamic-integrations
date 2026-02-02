<?php

namespace Omarsaiouf\Integrations\Contracts\Http;

use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;

/**
 * Low-level HTTP client used by the integration manager.
 *
 * Example:
 * $response = $client->send($builtRequest);
 * $responses = $client->pool(['users' => $req1, 'posts' => $req2]);
 */
interface HttpClient
{
    /**
     * Send a single request.
     *
     * Example:
     * $response = $client->send($builtRequest);
     * 
     * @param BuiltRequest $request
     * @return HttpResponse
     */
    public function send(BuiltRequest $request): HttpResponse;

    /**
     * Send multiple requests in parallel.
     *
     * Example:
     * $responses = $client->pool([
     *   'users' => $req1,
     *   'posts' => $req2,
     * ]);
     *
     * @param array<string, BuiltRequest> $requests
     * @return array<string, HttpResponse>
     */
    public function pool(array $requests): array;
}

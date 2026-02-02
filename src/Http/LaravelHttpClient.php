<?php

namespace Omarsaiouf\Integrations\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Omarsaiouf\Integrations\Contracts\Http\HttpClient;
use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\Exceptions\HttpFailedException;

class LaravelHttpClient implements HttpClient
{
    public function send(BuiltRequest $request): HttpResponse
    {
        $pending = $this->buildPendingRequest($request);

        $options = [];
        if (!empty($request->query)) {
            $options['query'] = $request->query;
        }
        if (!empty($request->body)) {
            $options['json'] = $request->body;
        }
        $method = strtoupper(is_string($request->method) ? $request->method : $request->method->value);

        /** @var Response $response */
        $response = $pending->send($method, $request->url, $options);

        if ($response->failed()) {
            throw new HttpFailedException($response->body(), $response->status());
        }

        return new HttpResponse(
            $response->status(),
            $response->headers(),
            $response->json(),
            $response->body(),
            $response->successful()
        );
    }

    private function buildPendingRequest(BuiltRequest $request): PendingRequest
    {
        $timeout = (int) config('integrations.base.http.timeout', 15);
        $retries = (int) config('integrations.base.http.retry.times', 0);
        $retrySleepMs = (int) config('integrations.base.http.retry.sleep_ms', 200);

        $pending = Http::withHeaders($request->headers)
            ->acceptJson()
            ->timeout($timeout);

        if ($retries > 0) {
            $pending = $pending->retry(
                $retries,
                $retrySleepMs,
                function (\Throwable $exception, ?Response $response) {
                    if ($exception) {
                        return true;
                    }
                    if (!$response) {
                        return true;
                    }
                    $status = $response->status();
                    return $status === 429 || ($status >= 500 && $status <= 599);
                },
                throw: false
            );
        }

        return $pending;
    }
}

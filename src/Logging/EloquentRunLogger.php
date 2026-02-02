<?php

namespace Omarsaiouf\Integrations\Logging;

use Omarsaiouf\Integrations\Contracts\Logging\RunLogger;
use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\DTOs\MappedResult;
use Omarsaiouf\Integrations\Enums\RunStatus;
use Omarsaiouf\Integrations\Models\Run;
use Throwable;

class EloquentRunLogger implements RunLogger
{
    public function success(
        array $provider,
        array $endpoint,
        BuiltRequest $request,
        HttpResponse $response,
        MappedResult $mappedResult,
        int $durationMs
    ): void {
        Run::create([
            'provider_key' => $provider['key'],
            'endpoint_key' => $endpoint['key'],
            'status' => RunStatus::SUCCESS->value,
            'http_status' => $response->status,
            'duration_ms' => $durationMs,
            'request' => $this->requestSnapshot($request),
            'response' => $this->responseSnapshot($response),
            'mapped' => $this->safeJson($mappedResult),
            'error' => null,
        ]);
    }

    public function failed(
        array $provider,
        array $endpoint,
        ?BuiltRequest $request,
        ?HttpResponse $response,
        Throwable $e,
        int $durationMs
    ): void {
        Run::create([
            'provider_key' => $provider['key'],
            'endpoint_key' => $endpoint['key'],
            'status' => RunStatus::FAILED->value,
            'http_status' => $response?->status,
            'duration_ms' => $durationMs,
            'request' => $request ? $this->requestSnapshot($request) : null,
            'response' => $response ? $this->responseSnapshot($response) : null,
            'mapped' => null,
            'error' => $this->formatError($e),
        ]);
    }

    private function requestSnapshot(BuiltRequest $request): array
    {
        $headers = $this->redactHeaders($request->headers ?? []);

        return [
            'method' => is_object($request->method) ? $request->method->value : $request->method,
            'url' => $request->url,
            'headers' => $headers,
            'query' => $request->query ?? [],
            'body' => $request->body ?? [],
        ];
    }

    private function responseSnapshot(HttpResponse $response): array
    {
        $storeBody = config('integrations.base.logging.store_response_body', true);
        $maxLen = (int) config('integrations.base.logging.max_raw_length', 2000);

        return [
            'status' => $response->status,
            'headers' => $response->headers,
            'body' => $storeBody ? $response->json() : null,
            'raw' => $storeBody ? mb_substr($response->raw, 0, $maxLen) : null,
            'ok' => $response->ok,
        ];
    }

    private function redactHeaders(array $headers): array
    {
        $sensitive = [
            'authorization',
            'x-api-key',
            'api-key',
            'x-auth-token',
            'x-access-token',
        ];

        $out = $headers;

        foreach ($out as $k => $v) {
            $key = strtolower((string) $k);
            if (in_array($key, $sensitive, true)) {
                $out[$k] = '******';
            }
        }

        return $out;
    }

    private function formatError(Throwable $e): string
    {
        return get_class($e) . ': ' . $e->getMessage();
    }

    private function safeJson(mixed $value): mixed
    {

        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }
        if (is_array($value))
            return $value;

        return is_scalar($value) || $value === null ? $value : json_decode(json_encode($value), true);
    }
}

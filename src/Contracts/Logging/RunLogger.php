<?php

namespace Omarsaiouf\Integrations\Contracts\Logging;

use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\DTOs\MappedResult;
use Throwable;

/**
 * Persists or records integration runs.
 *
 * Example:
 * $logger->success($provider, $endpoint, $req, $res, $mapped, 120);
 * $logger->failed($provider, $endpoint, $req, $res, $e, 120);
 */
interface RunLogger
{
    /**
     * Called when a run finishes successfully.
     *
     * Example:
     * $logger->success($provider, $endpoint, $req, $res, $mapped, 120);
     */
    public function success(
        array $provider,
        array $endpoint,
        BuiltRequest $request,
        HttpResponse $response,
        MappedResult $mappedResult,
        int $durationMs
    ): void;

    /**
     * Called when a run fails.
     *
     * Example:
     * $logger->failed($provider, $endpoint, $req, $res, $e, 120);
     */
    public function failed(
        array $provider,
        array $endpoint,
        ?BuiltRequest $request,
        ?HttpResponse $response,
        Throwable $e,
        int $durationMs
    ): void;
}

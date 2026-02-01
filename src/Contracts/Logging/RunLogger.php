<?php

namespace Omarsaiouf\Integrations\Contracts\Logging;

use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\DTOs\MappedResult;
use Throwable;

interface RunLogger
{

    public function success(
        array $provider,
        array $endpoint,
        BuiltRequest $request,
        HttpResponse $response,
        MappedResult $mappedResult,
        int $durationMs
    ): void;

    public function failed(
        array $provider,
        array $endpoint,
        ?BuiltRequest $request,
        ?HttpResponse $response,
        Throwable $e,
        int $durationMs
    ): void;
}
<?php

namespace Omarsaiouf\Integrations\Tests\Support;

use Omarsaiouf\Integrations\Contracts\Logging\RunLogger;
use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\DTOs\MappedResult;
use Throwable;

class FakeRunLogger implements RunLogger
{
    public function success(
        array $provider,
        array $endpoint,
        BuiltRequest $request,
        HttpResponse $response,
        MappedResult $mappedResult,
        int $durationMs
    ): void {
        // no-op
    }

    public function failed(
        array $provider,
        array $endpoint,
        ?BuiltRequest $request,
        ?HttpResponse $response,
        Throwable $e,
        int $durationMs
    ): void {
        // no-op
    }
}

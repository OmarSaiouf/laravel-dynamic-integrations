<?php

namespace Omarsaiouf\Integrations\Contracts\Mapping;

use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\DTOs\MappedResult;

/**
 * Converts raw API responses into mapped output.
 *
 * Example:
 * $mapped = $mapper->map(['id' => 'id', 'name' => 'title'], $response);
 */
interface ResponseMapper
{
    /**
     * Map response using rules array.
     *
     * Example:
     * $mapped = $mapper->map(['id' => 'id'], $response);
     */
    public function map(array $endpoint, HttpResponse $response): MappedResult;
}

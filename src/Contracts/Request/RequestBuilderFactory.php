<?php

namespace Omarsaiouf\Integrations\Contracts\Request;

use Omarsaiouf\Integrations\DTOs\BuiltRequest;

/**
 * Builds a ready-to-send HTTP request from provider/endpoint rules.
 *
 * Example:
 * $built = $builder->make($provider, $endpoint, ['id' => 1]);
 */
interface RequestBuilderFactory
{
    /**
     * Create a BuiltRequest for the given provider/endpoint and inputs.
     *
     * Example:
     * $built = $builder->make($provider, $endpoint, ['userId' => 10]);
     */
    public function make(array $provider = [], array $endpoint = [], array $inputs = []): BuiltRequest;
}

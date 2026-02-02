<?php

namespace Omarsaiouf\Integrations\Contracts\Request;

use Omarsaiouf\Integrations\DTOs\BuiltRequest;

interface RequestBuilderFactory
{
    public function make(array $provider = [], array $endpoint = [], array $inputs = []): BuiltRequest;
}
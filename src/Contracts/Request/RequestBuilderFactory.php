<?php

namespace Omarsaiouf\Integrations\Contracts\Request;

use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\Models\Provider;
use Omarsaiouf\Integrations\Models\Endpoint;

interface RequestBuilderFactory
{
    public function make(Provider $provider, Endpoint $endpoint, array $inputs = []): BuiltRequest;
}
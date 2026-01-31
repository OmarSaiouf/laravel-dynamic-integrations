<?php

namespace Omarsaiouf\Integrations\Integration;

use Omarsaiouf\Integrations\Contracts\Request\RequestBuilderFactory;
use Omarsaiouf\Integrations\Models\Endpoint;
use Omarsaiouf\Integrations\Models\Provider;

class IntegrationManager
{
    public function __construct(
        private RequestBuilderFactory $requestBuilder
    ) {

    }
    public function run(string $providerKey, string $endpointKey, array $inputs)
    {
        $provider = Provider::where('key', $providerKey)->firstOrFail();
        $endpoint = Endpoint::where('key', $endpointKey)->firstOrFail();

        $requestBuilder = $this->requestBuilder->make($provider, $endpoint, $inputs);

        dd($requestBuilder);
    }
}
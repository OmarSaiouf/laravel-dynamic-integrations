<?php

namespace Omarsaiouf\Integrations\Integration;

use Omarsaiouf\Integrations\Contracts\Http\HttpClient;
use Omarsaiouf\Integrations\Contracts\Logging\RunLogger;
use Omarsaiouf\Integrations\Contracts\Mapping\ResponseMapper;
use Omarsaiouf\Integrations\Contracts\Request\RequestBuilderFactory;
use Omarsaiouf\Integrations\Models\Endpoint;
use Omarsaiouf\Integrations\Models\Provider;

class IntegrationManager
{
    public function __construct(
        private RequestBuilderFactory $requestBuilder,
        private HttpClient $httpClient,
        private ResponseMapper $responseMapper,
        private RunLogger $runLogger
    ) {

    }
    public function run(string $providerKey, string $endpointKey, array $inputs)
    {
        $start = microtime(true);

        try {
            $provider = Provider::with(['endpoints'])->where('key', $providerKey)->firstOrFail();
            $endpoint = Endpoint::with('mapping')->where('key', $endpointKey)->firstOrFail();
            // dd($endpoint->mapping->rules);
            $requestBuilder = $this->requestBuilder->make($provider, $endpoint, $inputs);
            $response = $this->httpClient->send($requestBuilder);

            if (!$response->isSuccess()) {
                throw new \RuntimeException("HTTP failed with status {$response->status}");
            }

            $mappedResult = $this->responseMapper->map($endpoint->mapping->rules, $endpoint->mapping->type, $response);
            $duration = (int) ((microtime(true) - $start) * 1000);
            $this->runLogger->success($provider->toArray(), $endpoint->toArray(), $requestBuilder, $response, $mappedResult, $duration);

            return $mappedResult->toArray();
        } catch (\Throwable $e) {
            $duration = (int) ((microtime(true) - $start) * 1000);

            // request/response قد يكونوا مش موجودين لو فشل قبلهم
            $this->runLogger->failed($provider, $endpoint, $request ?? null, $response ?? null, $e, $duration);

            throw $e;
        }

    }
}
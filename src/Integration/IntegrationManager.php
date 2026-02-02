<?php

namespace Omarsaiouf\Integrations\Integration;

use Omarsaiouf\Integrations\Contracts\Http\HttpClient;
use Omarsaiouf\Integrations\Contracts\Logging\RunLogger;
use Omarsaiouf\Integrations\Contracts\Mapping\ResponseMapper;
use Omarsaiouf\Integrations\Contracts\Request\RequestBuilderFactory;
use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\Enums\Type;
use Omarsaiouf\Integrations\Exceptions\IntegrationException;
use Omarsaiouf\Integrations\Exceptions\NotFoundException;
use Omarsaiouf\Integrations\Models\Endpoint;
use Omarsaiouf\Integrations\Models\Provider;

class IntegrationManager
{
    public function __construct(
        private RequestBuilderFactory $baseRequestBuilder,
        private HttpClient $baseHttpClient,
        private ResponseMapper $baseResponseMapper,
        private RunLogger $runLogger
    ) {

    }
    public function run(string $providerKey, string $endpointKey, array $inputs)
    {
        $start = microtime(true);

        try {
            $provider = $this->provider($providerKey);
            $endpoint = $this->endpoint($endpointKey);
            $requestBuilder = $this->requestBuilder($provider, $endpoint, $inputs);

            $response = $this->httpClient($requestBuilder);
            if (!$response->isSuccess()) {
                throw new \RuntimeException("HTTP failed with status {$response->status}");
            }
            $mappedResult = $this->responseMapper($endpoint['mapping'], $response);

            $duration = (int) ((microtime(true) - $start) * 1000);
            $this->runLogger->success($provider, $endpoint, $requestBuilder, $response, $mappedResult, $duration);

            return $mappedResult->toArray();
        } catch (\Throwable $e) {
            $duration = (int) ((microtime(true) - $start) * 1000);
            $this->runLogger->failed($provider, $endpoint, $requestBuilder ?? null, $response ?? null, $e, $duration);
            throw $e;
        }
    }

    public function responseMapper(array $mapping, HttpResponse $response)
    {
        return $this->baseResponseMapper->map($mapping['rules'], $response);
    }
    public function httpClient(BuiltRequest $builtRequest): HttpResponse
    {
        return $this->baseHttpClient->send($builtRequest);
    }
    public function requestBuilder(array $provider, array $endpoint, array $inputs): BuiltRequest
    {
        return $this->baseRequestBuilder->make($provider, $endpoint, $inputs);
    }

    public function provider(string $providerKey): array
    {
        $type = config('integrations.base.type');
        return match ($type) {
            Type::DATABASE => $this->getProviderFromDatabase($providerKey),
            Type::ARRAY => $this->getProviderFromArray($providerKey),
            Type::FILE => $this->getProviderFromFile($providerKey),
            default => throw new NotFoundException('Provider key not found !')
        };
    }
    private function getProviderFromFile(string $providerKey)
    {
        $providerPath = config('integrations.base.file_path');

        if (!$this->checkFileMem($providerPath)) {
            throw new IntegrationException('the file is not json');
        }

        if (!file_exists($providerPath)) {
            throw new NotFoundException('provider file not found!');
        }

        $provider = json_decode(file_get_contents($providerPath), 1)['providers'][$providerKey];

        if ($provider == null || empty($provider)) {
            throw new IntegrationException('Provider key not found !');
        }
        $provider['key'] = $providerKey;
        return $provider;
    }
    private function getProviderFromArray(string $providerKey)
    {
        $provider = config("integrations.rules.providers.$providerKey");

        if ($provider == null || empty($provider)) {
            throw new IntegrationException('Provider key not found !');
        }
        $provider['key'] = $providerKey;
        return $provider;
    }
    private function getProviderFromDatabase(string $providerKey)
    {
        return Provider::with(['endpoints'])->where('key', $providerKey)->firstOrFail()->toArray();
    }
    public function endpoint(string $endpointKey)
    {
        $type = config('integrations.base.type');
        return match ($type) {
            Type::DATABASE => $this->getEndPointFromDatabase($endpointKey),
            Type::ARRAY => $this->getEndpointFromArray($endpointKey),
            Type::FILE => $this->getEndpointFromFile($endpointKey),
            default => throw new NotFoundException('Endpoint key not found !')
        };
    }
    private function getEndPointFromDatabase(string $endpointKey)
    {
        return Endpoint::with('mapping')->where('key', $endpointKey)->firstOrFail()->toArray();
    }
    private function getEndpointFromArray(string $endpointKey)
    {
        $endpoint = config("integrations.rules.endpoints.$endpointKey");
        if ($endpoint == null || empty($endpoint)) {
            throw new IntegrationException('Endpoint key not found !');
        }
        $endpoint['key'] = $endpointKey;
        return $endpoint;
    }
    private function getEndpointFromFile(string $endpointKey)
    {
        $providerPath = config('integrations.base.file_path');

        if (!$this->checkFileMem($providerPath)) {
            throw new IntegrationException('the file is not json');
        }

        if (!file_exists($providerPath)) {
            throw new NotFoundException('Endpoint file not found!');
        }
        $endpoint = json_decode(file_get_contents($providerPath), 1)['endpoints'][$endpointKey];

        if ($endpoint == null || empty($endpoint)) {
            throw new IntegrationException('Endpoint key not found !');
        }
        $endpoint['key'] = $endpointKey;
        return $endpoint;
    }
    private function checkFileMem(string $fileName)
    {
        return in_array(end(explode('.', $fileName)), ['json']);
    }
}
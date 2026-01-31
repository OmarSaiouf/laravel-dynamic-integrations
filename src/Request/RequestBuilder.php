<?php

namespace Omarsaiouf\Integrations\Request;

use Omarsaiouf\Integrations\Contracts\Request\RequestBuilderFactory;
use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\Enums\AuthType;
use Omarsaiouf\Integrations\Models\Endpoint;
use Omarsaiouf\Integrations\Models\Provider;

class RequestBuilder implements RequestBuilderFactory
{
    public function __construct(
        private Provider $provider,
        private Endpoint $endpoint,
        private array $inputs = [],
    ) {
    }

    public function make(Provider $provider, Endpoint $endpoint, array $inputs = []): BuiltRequest
    {
        return (new self($provider, $endpoint, $inputs))->build();
    }

    public function build(): BuiltRequest
    {
        $method = $this->endpoint->method;
        $url = rtrim($this->provider->url, '/') . '/' . ltrim($this->endpoint->path, '/');

        $headers = (array) ($this->endpoint->headers ?? []);
        $query = $this->interpolateArray((array) ($this->endpoint->query ?? []));
        $body = $this->interpolateArray((array) ($this->endpoint->body ?? []));

        $this->auth($headers, $query);

        return new BuiltRequest(
            method: $method,
            url: $url,
            headers: $headers,
            query: $query,
            body: $body,
        );
    }

    private function auth(array &$headers, array &$query): void
    {
        $type = $this->provider->auth_type ?? AuthType::NONE;
        
        $type->applier()
            ->apply($this->provider, $headers, $query);
    }


    private function interpolateArray(array $data): array
    {
        array_walk_recursive($data, function (&$value) {
            if (!is_string($value))
                return;

            $value = preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($m) {
                $key = $m[1];
                return array_key_exists($key, $this->inputs) ? (string) $this->inputs[$key] : $m[0];
            }, $value);
        });

        return $data;
    }
}

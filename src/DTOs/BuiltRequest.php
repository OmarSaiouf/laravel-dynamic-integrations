<?php

namespace Omarsaiouf\Integrations\DTOs;

use Omarsaiouf\Integrations\Enums\HttpMethod;

class BuiltRequest
{
    public function __construct(
        public HttpMethod $method,
        public string $url,
        public array $headers = [],
        public array $query = [],
        public array $body = []
    ) {

    }

    public function toArray(): array
    {
        return [
            'method' => $this->method,
            'url' => $this->url,
            'headers' => $this->headers,
            'query' => $this->query,
            'body' => $this->body,
        ];
    }

}
<?php

namespace Omarsaiouf\Integrations\DTOs;

class HttpResponse
{
    public function __construct(
        public int $status,
        public array $headers,
        public array|string|null $body,
        public string $raw,
        public bool $ok
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->ok;
    }

    public function json(): array
    {
        return is_array($this->body) ? $this->body : [];
    }
}

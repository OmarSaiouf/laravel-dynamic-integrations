<?php

namespace Omarsaiouf\Integrations\Enums;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
    case HEAD = 'HEAD';
    case OPTIONS = 'OPTIONS';

    public static function getAllValue(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(?string $lang = null): string
    {
        $key = "http.method.{$this->value}";
        $label = __($key, [], $lang);

        if ($label === $key) {
            $label = __($key, [], 'en');
        }

        return $label === $key ? $this->value : $label;
    }

    public static function fromString(string $method): self
    {
        $method = strtoupper(trim($method));

        return self::tryFrom($method)
            ?? throw new \InvalidArgumentException("Invalid HTTP method: {$method}");
    }

}
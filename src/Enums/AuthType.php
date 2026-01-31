<?php

namespace Omarsaiouf\Integrations\Enums;


enum AuthType: string
{
    case NONE = 'none';
    case BEARER_TOKEN = 'bearer_token';
    case OAUTH = 'oauth';

    public static function getAllValue(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(?string $lang = null): string
    {
        $key = "auth.type.{$this->value}";
        $label = __($key, [], $lang);

        if ($label === $key) {
            $label = __($key, [], 'en');
        }

        return $label === $key ? $this->value : $label;
    }
}
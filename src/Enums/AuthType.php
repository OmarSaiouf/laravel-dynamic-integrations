<?php

namespace Omarsaiouf\Integrations\Enums;

use Omarsaiouf\Integrations\Auth\NoAuth;
use Omarsaiouf\Integrations\Auth\BearerTokenAuth;
use Omarsaiouf\Integrations\Auth\ApiKeyAuth;
use Omarsaiouf\Integrations\Auth\BasicAuth;
use Omarsaiouf\Integrations\Contracts\Auth\AuthApplier;

enum AuthType: string
{
    case NONE = 'none';
    case BEARER_TOKEN = 'bearer_token';
    case API_KEY = 'api_key';
    case BASIC = 'basic';
    case OAUTH = 'oauth';

    /* =========================
     * Helpers
     * ========================= */

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

    /* =========================
     * Factory 
     * ========================= */

    public function applier(): AuthApplier
    {
        return match ($this) {
            self::NONE => app(NoAuth::class),
            self::BEARER_TOKEN => app(BearerTokenAuth::class),
            self::API_KEY => app(ApiKeyAuth::class),
            self::BASIC => app(BasicAuth::class),
            self::OAUTH => app(NoAuth::class),
        };
    }
}

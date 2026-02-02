<?php

namespace Omarsaiouf\Integrations\Enums;

use Omarsaiouf\Integrations\Auth\NoAuth;
use Omarsaiouf\Integrations\Auth\BearerTokenAuth;
use Omarsaiouf\Integrations\Auth\ApiKeyAuth;
use Omarsaiouf\Integrations\Auth\BasicAuth;
use Omarsaiouf\Integrations\Contracts\Auth\AuthApplier;

enum Type: string
{
    case DATABASE = 'database';
    case ARRAY = 'array';
    case FILE = 'file';


    /* =========================
     * Helpers
     * ========================= */

    public static function getAllValue(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(?string $lang = null): string
    {
        $key = "type.{$this->value}";
        $label = __($key, [], $lang);

        if ($label === $key) {
            $label = __($key, [], 'en');
        }

        return $label === $key ? $this->value : $label;
    }


}

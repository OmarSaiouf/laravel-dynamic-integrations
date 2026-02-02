<?php

namespace Omarsaiouf\Integrations\Auth;

use Omarsaiouf\Integrations\Contracts\Auth\AuthApplier;

class ApiKeyAuth implements AuthApplier
{
    public function apply(array $provider, array &$headers, array &$query): void
    {
        $meta = (array) ($provider['auth_meta'] ?? []);

        $name = $meta['name'] ?? null;          // e.g. X-API-KEY or api_key
        $value = $meta['value'] ?? null;        // actual key
        $in = strtolower($meta['in'] ?? 'header'); // header|query

        if (empty($name) || empty($value)) {
            throw new \InvalidArgumentException("API Key auth requires auth_meta: {name, value, in?}");
        }

        if ($in === 'query') {
            $query[$name] = $value;
            return;
        }

        $headers[$name] = $value;
    }
}

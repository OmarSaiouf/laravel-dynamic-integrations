<?php

namespace Omarsaiouf\Integrations\Auth;

use Omarsaiouf\Integrations\Contracts\Auth\AuthApplier;
use Omarsaiouf\Integrations\Models\Provider;

class BearerTokenAuth implements AuthApplier
{
    public function apply(Provider $provider, array &$headers, array &$query): void
    {
        $token = $provider->auth_token ?? ($provider->auth_meta['token'] ?? null);

        if (empty($token)) {
            throw new \InvalidArgumentException("Bearer token is required");
        }

        $headers['Authorization'] = 'Bearer ' . $token;
    }
}

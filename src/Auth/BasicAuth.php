<?php

namespace Omarsaiouf\Integrations\Auth;

use Omarsaiouf\Integrations\Contracts\Auth\AuthApplier;
use Omarsaiouf\Integrations\Models\Provider;

class BasicAuth implements AuthApplier
{
    public function apply(array $provider, array &$headers, array &$query): void
    {
        $meta = (array) ($provider['auth_meta'] ?? []);

        $username = $meta['username'] ?? null;
        $password = $meta['password'] ?? null;

        if ($username === null || $password === null) {
            throw new \InvalidArgumentException("Basic auth requires auth_meta: {username, password}");
        }

        $headers['Authorization'] = 'Basic ' . base64_encode($username . ':' . $password);
    }
}

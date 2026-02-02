<?php

namespace Omarsaiouf\Integrations\Contracts\Auth;


/**
 * Apply authentication to outgoing requests.
 *
 * Example:
 * $auth = new BearerTokenAuth();
 * $headers = [];
 * $query = [];
 * $auth->apply(['auth_token' => 'secret'], $headers, $query);
 */
interface AuthApplier
{
    /**
     * Mutates headers/query to include auth details.
     *
     * Example:
     * $headers = [];
     * $query = [];
     * $applier->apply(['auth_meta' => ['name' => 'X-API-KEY', 'value' => 'k1']], $headers, $query);
     */
    public function apply(array $provider, array &$headers, array &$query): void;
}

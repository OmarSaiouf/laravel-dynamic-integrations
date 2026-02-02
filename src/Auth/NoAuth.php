<?php

namespace Omarsaiouf\Integrations\Auth;

use Omarsaiouf\Integrations\Contracts\Auth\AuthApplier;
use Omarsaiouf\Integrations\Models\Provider;

class NoAuth implements AuthApplier
{
    public function apply(array $provider, array &$headers, array &$query): void
    {
        // nothing
    }
}

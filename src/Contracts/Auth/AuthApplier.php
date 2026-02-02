<?php

namespace Omarsaiouf\Integrations\Contracts\Auth;

use Omarsaiouf\Integrations\Models\Provider;

interface AuthApplier
{
    public function apply(array $provider, array &$headers, array &$query): void;
}

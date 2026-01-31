<?php

namespace Omarsaiouf\Integrations\Contracts\Auth;

use Omarsaiouf\Integrations\Models\Provider;

interface AuthApplier
{
    public function apply(Provider $provider, array &$headers, array &$query): void;
}

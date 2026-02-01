<?php

namespace Omarsaiouf\Integrations\Facades;

use Illuminate\Support\Facades\Facade;

class Integration extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'integration.manager';
    }
}

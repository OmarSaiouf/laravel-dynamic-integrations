<?php

namespace Omarsaiouf\Integrations\Tests;

use Omarsaiouf\Integrations\IntegrationsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            IntegrationsServiceProvider::class,
        ];
    }
}

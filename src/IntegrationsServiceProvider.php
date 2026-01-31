<?php

namespace Omarsaiouf\Integrations;

use Illuminate\Support\ServiceProvider;
use Omarsaiouf\Integrations\Contracts\Request\RequestBuilderFactory;
use Omarsaiouf\Integrations\Integration\IntegrationManager;
use Omarsaiouf\Integrations\Request\RequestBuilder;

class IntegrationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RequestBuilderFactory::class, RequestBuilder::class);

        $this->app->bind('integration.manager', IntegrationManager::class);
        
        $this->mergeConfigFrom(
            __DIR__ . '/../config/integrations.php',
            'integrations-config'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/integrations.php' => config_path('integrations.php'),
        ], 'integrations-publishes');

        $this->publishes(
            [__DIR__ . "/../database/seeders/DemoProvidersSeeder.php" => database_path('seeders/DemoProvidersSeeder.php')],
            'integrations-publishes'
        );
        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'integrations-publishes');

    }
}
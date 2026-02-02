<?php

namespace Omarsaiouf\Integrations;

use Illuminate\Support\ServiceProvider;
use Omarsaiouf\Integrations\Contracts\Http\HttpClient;
use Omarsaiouf\Integrations\Contracts\Logging\RunLogger;
use Omarsaiouf\Integrations\Contracts\Mapping\ResponseMapper;
use Omarsaiouf\Integrations\Contracts\Request\RequestBuilderFactory;
use Omarsaiouf\Integrations\Integration\IntegrationManager;
use Omarsaiouf\Integrations\Request\RequestBuilder;
use Omarsaiouf\Integrations\Http\HttpClientFactory;
use Omarsaiouf\Integrations\Logging\EloquentRunLogger;
use Omarsaiouf\Integrations\Mapping\ResponseMapperFactory;

class IntegrationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/integrations/base.php', 'integrations.base');
        $this->mergeConfigFrom(__DIR__ . '/../config/integrations/rules.php', 'integrations.rules');

        $this->app->singleton(RequestBuilderFactory::class, RequestBuilder::class);

        $this->app->singleton("integration.manager", IntegrationManager::class);

        $this->app->singleton(HttpClient::class, function () {
            return (new HttpClientFactory())->make(config('integrations.base.http.provider', 'Http'));
        });
        $this->app->singleton(ResponseMapper::class, function () {
            return (new ResponseMapperFactory())->make(config('integrations.base.mapper.name', 'default'));
        });
        $this->app->singleton(RunLogger::class, EloquentRunLogger::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/integrations/base.php' => config_path('integrations/base.php'),
            __DIR__ . '/../config/integrations/rules.php' => config_path('integrations/rules.php'),
        ], 'integrations-config');
        
        $this->publishes(
            [__DIR__ . "/../database/seeders/DemoProvidersSeeder.php" => database_path('seeders/DemoProvidersSeeder.php')],
            'integrations-publishes'
        );
        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'integrations-publishes');

    }
}

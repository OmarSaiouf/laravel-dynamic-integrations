<?php

namespace Omarsaiouf\Integrations\Tests\Feature;

use Omarsaiouf\Integrations\Contracts\Http\HttpClient;
use Omarsaiouf\Integrations\Contracts\Logging\RunLogger;
use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\Enums\AuthType;
use Omarsaiouf\Integrations\Enums\HttpMethod;
use Omarsaiouf\Integrations\Enums\Type;
use Omarsaiouf\Integrations\Facades\Integration;
use Omarsaiouf\Integrations\Tests\Support\FakeHttpClient;
use Omarsaiouf\Integrations\Tests\Support\FakeRunLogger;
use Omarsaiouf\Integrations\Tests\TestCase;

class IntegrationManagerArrayTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('integrations.base.type', Type::ARRAY);

        config()->set('integrations.rules.providers', [
            'demo' => [
                'url' => 'https://api.example.com',
                'auth_type' => AuthType::NONE,
                'auth_meta' => null,
            ],
        ]);

        config()->set('integrations.rules.endpoints', [
            'get_user' => [
                'method' => HttpMethod::GET,
                'path' => '/users/{userId}',
                'mapping' => [
                    'rules' => [
                        'id' => 'id',
                        'name' => 'name',
                    ],
                ],
            ],
        ]);

        $response = new HttpResponse(
            200,
            ['content-type' => ['application/json']],
            ['id' => 7, 'name' => 'Mona'],
            '{"id":7,"name":"Mona"}',
            true
        );

        $this->app->singleton(HttpClient::class, fn () => new FakeHttpClient($response));
        $this->app->singleton(RunLogger::class, fn () => new FakeRunLogger());
    }

    public function test_run_returns_mapped_result(): void
    {
        $result = Integration::run('demo', 'get_user', ['userId' => 7]);

        $this->assertSame(['id' => 7, 'name' => 'Mona'], $result['data']);
    }
}

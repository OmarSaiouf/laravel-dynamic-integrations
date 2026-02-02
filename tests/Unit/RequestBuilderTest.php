<?php

namespace Omarsaiouf\Integrations\Tests\Unit;

use Omarsaiouf\Integrations\Enums\AuthType;
use Omarsaiouf\Integrations\Enums\HttpMethod;
use Omarsaiouf\Integrations\Request\RequestBuilder;
use Omarsaiouf\Integrations\Tests\TestCase;

class RequestBuilderTest extends TestCase
{
    public function test_build_interpolates_url_query_body(): void
    {
        $provider = [
            'url' => 'https://api.example.com',
            'auth_type' => AuthType::NONE,
        ];
        $endpoint = [
            'method' => HttpMethod::POST,
            'path' => '/users/{userId}',
            'query' => ['page' => '{page}'],
            'body' => ['name' => '{name}'],
        ];
        $inputs = ['userId' => 15, 'page' => 2, 'name' => 'Omar'];

        $builder = new RequestBuilder();
        $built = $builder->make($provider, $endpoint, $inputs);

        $this->assertSame('https://api.example.com/users/15', $built->url);
        $this->assertSame(['page' => '2'], $built->query);
        $this->assertSame(['name' => 'Omar'], $built->body);
        $this->assertSame(HttpMethod::POST, $built->method);
    }
}

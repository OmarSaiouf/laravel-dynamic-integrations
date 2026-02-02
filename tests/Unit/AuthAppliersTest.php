<?php

namespace Omarsaiouf\Integrations\Tests\Unit;

use Omarsaiouf\Integrations\Auth\ApiKeyAuth;
use Omarsaiouf\Integrations\Auth\BasicAuth;
use Omarsaiouf\Integrations\Auth\BearerTokenAuth;
use Omarsaiouf\Integrations\Auth\NoAuth;
use Omarsaiouf\Integrations\Tests\TestCase;

class AuthAppliersTest extends TestCase
{
    public function test_bearer_token_auth_sets_header(): void
    {
        $auth = new BearerTokenAuth();
        $provider = ['auth_token' => 'secret'];
        $headers = [];
        $query = [];

        $auth->apply($provider, $headers, $query);

        $this->assertSame('Bearer secret', $headers['Authorization']);
    }

    public function test_api_key_auth_header(): void
    {
        $auth = new ApiKeyAuth();
        $provider = ['auth_meta' => ['name' => 'X-API-KEY', 'value' => 'k1', 'in' => 'header']];
        $headers = [];
        $query = [];

        $auth->apply($provider, $headers, $query);

        $this->assertSame('k1', $headers['X-API-KEY']);
    }

    public function test_api_key_auth_query(): void
    {
        $auth = new ApiKeyAuth();
        $provider = ['auth_meta' => ['name' => 'api_key', 'value' => 'k2', 'in' => 'query']];
        $headers = [];
        $query = [];

        $auth->apply($provider, $headers, $query);

        $this->assertSame('k2', $query['api_key']);
    }

    public function test_basic_auth_header(): void
    {
        $auth = new BasicAuth();
        $provider = ['auth_meta' => ['username' => 'u', 'password' => 'p']];
        $headers = [];
        $query = [];

        $auth->apply($provider, $headers, $query);

        $this->assertSame('Basic ' . base64_encode('u:p'), $headers['Authorization']);
    }

    public function test_no_auth_does_not_change_headers(): void
    {
        $auth = new NoAuth();
        $provider = [];
        $headers = ['X' => '1'];
        $query = ['q' => 'v'];

        $auth->apply($provider, $headers, $query);

        $this->assertSame(['X' => '1'], $headers);
        $this->assertSame(['q' => 'v'], $query);
    }
}

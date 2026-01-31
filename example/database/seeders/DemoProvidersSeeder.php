<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Omarsaiouf\Integrations\Models\Provider;
use Omarsaiouf\Integrations\Models\Endpoint;
use Omarsaiouf\Integrations\Models\Mapping;
use Omarsaiouf\Integrations\Enums\AuthType;
use Omarsaiouf\Integrations\Enums\HttpMethod;
use Omarsaiouf\Integrations\Enums\MappingMode;

class DemoProvidersSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Provider
        $provider = Provider::updateOrCreate(
            ['key' => 'demo_post'],
            [
                'key' => 'demo_post',
                'url' => 'https://jsonplaceholder.typicode.com',
                'auth_type' => AuthType::NONE,
                'auth_meta' => null,
            ]
        );

        // 2) Endpoint
        $endpoint = Endpoint::updateOrCreate(
            [
                'provider_id' => $provider->id,
                'key' => 'list_posts',
            ],
            [
                'method' => HttpMethod::GET,
                'path' => '/posts',
                // 'query' => [
                //     'page' => '{page}',
                //     'per_page' => 10,
                // ],
            ]
        );

        // 3) Mapping (Option B)
        Mapping::updateOrCreate(
            ['endpoint_id' => $endpoint->id],
            [
                'type' => MappingMode::LIST ,
                'endpoint_id' => $endpoint->id,
                'rules' => [
                    'user_id' => 'userId',
                    'id' => 'id',
                    'name' => 'title',
                    'content' => 'body'

                ],
            ]
        );
    }
}

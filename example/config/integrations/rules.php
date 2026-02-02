<?php

use Omarsaiouf\Integrations\Enums\AuthType;
use Omarsaiouf\Integrations\Enums\HttpMethod;

return [
    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    | Each provider represents an external API base URL and its auth settings.
    | Supported auth types:
    | - AuthType::NONE
    | - AuthType::BEARER_TOKEN (use auth_token or auth_meta.token)
    | - AuthType::API_KEY     (auth_meta: name, value, in=header|query)
    | - AuthType::BASIC       (auth_meta: username, password)
    */
    'providers' => [
        'jsonplaceholder' => [
            'url' => 'https://jsonplaceholder.typicode.com',
            'auth_type' => AuthType::NONE,
            'auth_meta' => null,
        ],

        'example_api_key' => [
            'url' => 'https://api.example.com',
            'auth_type' => AuthType::API_KEY,
            'auth_meta' => [
                'name' => 'X-API-KEY',
                'value' => env('EXAMPLE_API_KEY'),
                'in' => 'header',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Endpoints
    |--------------------------------------------------------------------------
    | Endpoints define method/path plus optional headers, query, body and mapping.
    | Placeholders like {postId} are replaced from runtime inputs.
    */
    'endpoints' => [
        'list_posts' => [
            'method' => HttpMethod::GET,
            'path' => '/posts',
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => [
                'userId' => '{userId}',
            ],
            'mapping' => [
                'rules' => [
                    '@each' => '.',
                    'map' => [
                        'id' => 'id',
                        'user_id' => 'userId',
                        'title' => 'title',
                        'body' => 'body',
                    ],
                ],
            ],
        ],

        'get_post' => [
            'method' => HttpMethod::GET,
            'path' => '/posts/{postId}',
            'mapping' => [
                'rules' => [
                    'id' => 'id',
                    'user_id' => 'userId',
                    'title' => 'title',
                    'body' => 'body',
                ],
            ],
        ],

        'create_post' => [
            'method' => HttpMethod::POST,
            'path' => '/posts',
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => [
                'title' => '{title}',
                'body' => '{body}',
                'userId' => '{userId}',
            ],
            'mapping' => [
                'rules' => [
                    'id' => 'id',
                    'user_id' => 'userId',
                    'title' => 'title',
                    'body' => 'body',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Mapping Rules (quick reference)
    |--------------------------------------------------------------------------
    | - String value: treated as a dot path (e.g. 'user.id').
    | - Scalar value: returned as-is.
    | - Array/object: resolved recursively.
    | - @each + map: iterate over a list and map each item.
    */
];

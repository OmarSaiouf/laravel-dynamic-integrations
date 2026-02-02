<?php

use Omarsaiouf\Integrations\Enums\AuthType;
use Omarsaiouf\Integrations\Enums\HttpMethod;

return [
    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    | Each provider represents an API base URL and its auth settings.
    |
    | Supported auth types:
    | - AuthType::NONE
    | - AuthType::BEARER_TOKEN  (use auth_token or auth_meta.token)
    | - AuthType::API_KEY       (auth_meta: name, value, in=header|query)
    | - AuthType::BASIC         (auth_meta: username, password)
    |
    | Example:
    | 'providers' => [
    |   'github' => [
    |     'url' => 'https://api.github.com',
    |     'auth_type' => AuthType::BEARER_TOKEN,
    |     'auth_meta' => ['token' => env('GITHUB_TOKEN')],
    |   ],
    | ]
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
    | Define method/path and optional headers/query/body and mapping rules.
    | Placeholders like {postId} are replaced from runtime inputs.
    |
    | Example:
    | 'endpoints' => [
    |   'get_repo' => [
    |     'method' => HttpMethod::GET,
    |     'path' => '/repos/{owner}/{repo}',
    |     'headers' => ['Accept' => 'application/json'],
    |     'mapping' => ['rules' => ['id' => 'id', 'name' => 'name']],
    |   ],
    | ]
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
    |
    | Example:
    | 'rules' => [
    |   'id' => 'id',
    |   'author' => [
    |     'name' => 'user.name',
    |   ],
    | ]
    */
];

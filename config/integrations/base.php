<?php

use Omarsaiouf\Integrations\Enums\Type;

return [
    /*
    |--------------------------------------------------------------------------
    | Storage type
    |--------------------------------------------------------------------------
    | Choose where integrations are loaded from:
    | - Type::ARRAY    => config/integrations/rules.php
    | - Type::FILE     => JSON file defined in file_path
    | - Type::DATABASE => database tables (migrations included)
    |
    | Example:
    | 'type' => Type::ARRAY,
    */
    'type' => Type::DATABASE,

    /*
    |--------------------------------------------------------------------------
    | File path (when type = FILE)
    |--------------------------------------------------------------------------
    | Example:
    | 'file_path' => storage_path('rules.json'),
    */
    'file_path' => storage_path('rules.json'),

    /*
    |--------------------------------------------------------------------------
    | HTTP settings
    |--------------------------------------------------------------------------
    | provider: which HTTP client to use (default: Http)
    | timeout: request timeout in seconds
    | retry: retry policy (times + sleep in ms)
    |
    | Example:
    | 'http' => [
    |   'provider' => 'Http',
    |   'timeout' => 20,
    |   'retry' => ['times' => 2, 'sleep_ms' => 300],
    | ]
    */
    'http' => [
        'provider' => 'Http',
        'timeout' => 30,
        'tetry' => [
            'times' => 1,
            'sleep_ms' => 200,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Mapper
    |--------------------------------------------------------------------------
    | name: mapper key resolved by ResponseMapperFactory
    |
    | Example:
    | 'mapper' => ['name' => 'default']
    */
    'mapper' => [
        'name' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    | store_response_body: whether to store parsed JSON and raw body
    | max_raw_length: max length of stored raw response
    |
    | Example:
    | 'logging' => ['store_response_body' => true, 'max_raw_length' => 1000]
    */
    'logging' => [
        'store_response_body' => true,
        'max_raw_length' => 2000,
    ],
];

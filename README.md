# laravel-dynamic-integrations

Dynamic, configuration-driven API integrations for Laravel. Define providers, endpoints, auth, and response mapping in config (or database/file) and run them with a single call.

[Arabic documentation](docs/README.ar.md)

## Overview

This package lets you define external API integrations declaratively and execute them with a single call. You can store rules in config (ARRAY), JSON file (FILE), or database (DATABASE).

## Features

- Provider + endpoint definitions (array, file, or database-backed)
- Auth helpers (none, bearer token, api key, basic)
- Mapping DSL with `@each` support
- Run logging with Eloquent
- Parallel execution via `pool`

## Installation

```bash
composer require omarsaiouf/integrations
```

## Publish config and demo files

```bash
php artisan vendor:publish --tag=integrations-config
php artisan vendor:publish --tag=integrations-publishes
```

## Quick start

```php
use Omarsaiouf\Integrations\Facades\Integration;

$result = Integration::run('jsonplaceholder', 'list_posts', [
    'userId' => 1,
]);
```

## Configuration

### Base config

File: `config/integrations/base.php`

Key options:

- `type`: ARRAY | FILE | DATABASE
- `file_path`: JSON rules file path (for FILE)
- `http`: provider, timeout, retry
- `mapper`: mapper name
- `logging`: store response body and raw limits

Example:

```php
return [
    'type' => Type::ARRAY,
    'file_path' => storage_path('rules.json'),
    'http' => [
        'provider' => 'Http',
        'timeout' => 20,
        'retry' => ['times' => 2, 'sleep_ms' => 300],
    ],
    'mapper' => ['name' => 'default'],
    'logging' => ['store_response_body' => true, 'max_raw_length' => 1000],
];
```

### Rules (ARRAY mode)

File: `config/integrations/rules.php`

#### Providers

Each provider defines base URL and auth details.

```php
'providers' => [
    'github' => [
        'url' => 'https://api.github.com',
        'auth_type' => AuthType::BEARER_TOKEN,
        'auth_meta' => ['token' => env('GITHUB_TOKEN')],
    ],
],
```

#### Endpoints

Each endpoint defines method/path, optional headers/query/body, and mapping.

```php
'endpoints' => [
    'get_repo' => [
        'method' => HttpMethod::GET,
        'path' => '/repos/{owner}/{repo}',
        'headers' => ['Accept' => 'application/json'],
        'query' => ['per_page' => 50],
        'mapping' => [
            'rules' => [
                'id' => 'id',
                'full_name' => 'full_name',
                'owner' => ['login' => 'owner.login'],
            ],
        ],
    ],
],
```

### Rules JSON (FILE mode)

File: `storage/rules.json` (or custom path)

```json
{
  "providers": {
    "demo": {
      "url": "https://api.example.com",
      "auth_type": "api_key",
      "auth_meta": { "name": "X-API-KEY", "value": "...", "in": "header" }
    }
  },
  "endpoints": {
    "list_items": {
      "method": "GET",
      "path": "/items",
      "mapping": { "rules": { "@each": ".", "map": { "id": "id" } } }
    }
  }
}
```

### Database (DATABASE mode)

Migrations are included. Tables:

- Providers: base URL and auth metadata
- Endpoints: method/path/headers/query/body
- Mappings: JSON rules used by mapper

## Mapping rules (DSL)

- String value => dot path (e.g. `user.id`)
- Scalar value => constant
- Array/object => recursive mapping
- `@each` + `map` => map lists

Example:

```php
'rules' => [
    '@each' => 'data.items',
    'map' => [
        'id' => 'id',
        'title' => 'title',
        'author' => ['name' => 'user.name'],
    ],
],
```

### Array mapping example

When the response is an array of items:

```php
'rules' => [
    '@each' => '.',
    'map' => [
        'id' => 'id',
        'name' => 'title',
        'content' => 'body',
    ],
],
```

### Single object mapping example

When the response is a single object:

```php
'rules' => [
    'id' => 'id',
    'name' => 'title',
    'content' => 'body',
],
```

## Auth types

Supported:

- `NONE`
- `BEARER_TOKEN` (token or auth_meta.token)
- `API_KEY` (auth_meta: name, value, in)
- `BASIC` (auth_meta: username, password)

## Running integrations

```php
$result = Integration::run('provider_key', 'endpoint_key', [
    'userId' => 1,
]);
```

## Parallel execution (pool)

```php
use Omarsaiouf\Integrations\Integration\IntegrationManager;

$result = Integration::pool([
    'list_posts' => [
        'provider' => 'jsonplaceholder',
        'endpoint' => 'list_posts',
        'inputs' => ['userId' => 1],
    ],
    'get_post' => [
        'provider' => 'jsonplaceholder',
        'endpoint' => 'get_post',
        'inputs' => ['postId' => 2],
    ],
]);

```

## Logging

The default logger stores each run (success/failure) with request/response snapshots. Sensitive headers are redacted.

## Testing

```bash
composer update
vendor/bin/phpunit
```

## License

MIT

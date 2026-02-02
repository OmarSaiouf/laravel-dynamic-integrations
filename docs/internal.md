# Internal documentation

This document explains where developers can customize behavior and includes examples.

## 1) Base config
File: `config/integrations/base.php`

Controls how integrations are loaded and how HTTP, mapping, and logging behave.

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

## 2) Rules config (ARRAY mode)
File: `config/integrations/rules.php`

Defines providers and endpoints when `type = Type::ARRAY`.

Example provider:
```php
'providers' => [
    'github' => [
        'url' => 'https://api.github.com',
        'auth_type' => AuthType::BEARER_TOKEN,
        'auth_meta' => ['token' => env('GITHUB_TOKEN')],
    ],
],
```

Example endpoint:
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

## 3) Rules JSON (FILE mode)
File: `storage/rules.json` (or the path in `file_path`)

Same structure as `config/integrations/rules.php` but in JSON.

Example:
```json
{
  "providers": {
    "demo": {
      "url": "https://api.example.com",
      "auth_type": "api_key",
      "auth_meta": {"name": "X-API-KEY", "value": "...", "in": "header"}
    }
  },
  "endpoints": {
    "list_items": {
      "method": "GET",
      "path": "/items",
      "mapping": {"rules": {"@each": ".", "map": {"id": "id"}}}
    }
  }
}
```

## 4) Database (DATABASE mode)
Files: `database/migrations/*_create_di_*.php`

Tables:
- Providers: API base URL + auth metadata
- Endpoints: method/path/headers/query/body
- Mappings: JSON rules used by the mapper

## 5) Seeders (demo data)
File: `database/seeders/DemoProvidersSeeder.php`

Used to insert demo providers/endpoints/mappings.

## 6) Mapping rules (DSL)
File: `src/Mapping/DefaultResponseMapper.php`

Rules reference:
- String value => dot path
- Scalar value => constant
- Array/object => recursive
- `@each` + `map` => array mapping

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

## 7) Auth types
Files: `src/Enums/AuthType.php`, `src/Auth/*`

Supported:
- NONE
- BEARER_TOKEN (token or auth_meta.token)
- API_KEY (auth_meta: name, value, in)
- BASIC (auth_meta: username, password)

## 8) Logging
File: `src/Logging/EloquentRunLogger.php`

Stores run details, redacts sensitive headers, and can limit stored raw bodies.

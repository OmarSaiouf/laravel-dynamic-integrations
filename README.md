# laravel-dynamic-integrations

Dynamic, configuration-driven API integrations for Laravel. Define providers, endpoints, auth, and response mapping in config (or database/file) and run them with a single call.

## Features
- Provider + endpoint definitions (array, file, or database-backed)
- Auth helpers (none, bearer token, api key, basic)
- Simple mapping DSL with `@each` support
- Run logging with Eloquent

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

## Places you can customize
This package is designed to be configured without changing source code. Here are all the places a developer can control behavior:

### 1) Base config
File: `config/integrations/base.php`
- `type` decides where integrations are loaded from:
  - `ARRAY`  => `config/integrations/rules.php`
  - `FILE`   => JSON file defined in `file_path`
  - `DATABASE` => database tables (migrations included)
- `file_path` points to the JSON file used when `type=FILE`.
- `http` allows changing the HTTP provider, timeouts, and retry behavior.
- `mapper` allows swapping the response mapper (default is `default`).
- `logging` controls whether response bodies are stored and how much raw data is kept.

### 2) Rules config (ARRAY mode)
File: `config/integrations/rules.php`
- `providers` define base URLs and auth settings.
- `endpoints` define method/path and optional headers/query/body.
- `mapping.rules` defines the output shape from API responses.

### 3) Rules JSON (FILE mode)
File: `storage/rules.json` (or the path you set in `file_path`)
- Same structure as `config/integrations/rules.php` but in JSON.
- Useful for environments where you want to edit rules without deploying code.

### 4) Database tables (DATABASE mode)
Migrations are published by the package. After publishing, you can edit or extend them as needed.
- Providers table holds API base URL and auth metadata.
- Endpoints table holds method/path/headers/query/body.
- Mappings table holds `rules` as JSON (used by the mapper).

### 5) Seeders (optional demo data)
File: `database/seeders/DemoProvidersSeeder.php`
- Provides sample providers/endpoints/mappings to help you start quickly.
- Safe to modify for your own demo dataset.

### 6) Response mapping rules (DSL)
Implemented in: `src/Mapping/DefaultResponseMapper.php`
- String values are treated as dot paths (e.g. `user.id`).
- Scalars are returned as-is.
- `@each` + `map` iterates over a list and maps each item.

### 7) Auth types
Implemented in: `src/Enums/AuthType.php` and `src/Auth/*`
- `NONE`, `BEARER_TOKEN`, `API_KEY`, `BASIC` are supported.
- You can add your own auth applier if needed.

### 8) Logging
Implemented in: `src/Logging/EloquentRunLogger.php`
- Stores each run with request/response snapshots and mapped output.
- Sensitive headers are redacted.

## Mapping rules (short)
- String values are treated as dot paths (e.g. `user.id`).
- `@each` + `map` iterates over a list and maps each item.

## License
MIT

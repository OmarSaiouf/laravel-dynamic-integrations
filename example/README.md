# Example app for laravel-dynamic-integrations

This is a small Laravel app that demonstrates how to use the package with ARRAY rules.

## Quick start
```bash
cd example
composer install
php artisan serve
```

Then visit:
- http://127.0.0.1:8000/integrations

## Available demo endpoints
- `GET /integrations/posts?userId=1` -> list posts
- `GET /integrations/posts/{postId}` -> get single post
- `POST /integrations/posts` -> create post

Example POST body:
```json
{
  "title": "Hello",
  "body": "This is a demo post",
  "userId": 1
}
```

## Config used by the demo
- `config/integrations/base.php` uses `Type::ARRAY` for simplicity.
- `config/integrations/rules.php` defines providers/endpoints/mapping.

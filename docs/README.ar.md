# توثيق laravel-dynamic-integrations (العربي)

[English Documentation](../README.md)

## نظرة عامة
المكتبة تتيح لك تعريف تكاملات API بشكل قابل للتعديل من خلال الكونفج أو ملف JSON أو قاعدة البيانات، ثم تشغيلها بسطر واحد. مفيدة عندما تريد إدارة عدة مزودين ونهايات دون كتابة كود جديد لكل تكامل.

## المميزات
- تعريف المزودين والنهايات (Config / File / Database)
- دعم المصادقة (بدون/توكن/API Key/Basic)
- لغة تحويل النتائج (Mapping) مع `@each`
- تسجيل التشغيلات (Logging)
- تنفيذ متوازي عبر `pool`

## التثبيت
```bash
composer require omarsaiouf/integrations
```

## نشر ملفات الإعدادات والـmigrations
```bash
php artisan vendor:publish --tag=integrations-config
php artisan vendor:publish --tag=integrations-publishes
```

## بدء سريع
```php
use Omarsaiouf\Integrations\Facades\Integration;

$result = Integration::run('jsonplaceholder', 'list_posts', [
    'userId' => 1,
]);
```

## الإعدادات الأساسية
الملف: `config/integrations/base.php`

### type
يحدد مصدر القواعد:
- `Type::ARRAY` => `config/integrations/rules.php`
- `Type::FILE` => ملف JSON في `file_path`
- `Type::DATABASE` => قاعدة بيانات (migrations مرفقة)

### file_path
مسار ملف JSON عند استخدام `Type::FILE`.

### http
إعدادات الاتصال:
- `provider`: مزود HTTP
- `timeout`: المهلة بالثواني
- `retry`: عدد المحاولات وفاصل الزمن

### mapper
تحديد اسم المابّر المستخدم (افتراضي `default`).

### logging
خيارات تخزين الاستجابة والـraw.

مثال:
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

## القواعد (ARRAY)
الملف: `config/integrations/rules.php`

### المزودون (providers)
يحددون الـbase URL والمصادقة.
```php
'providers' => [
    'github' => [
        'url' => 'https://api.github.com',
        'auth_type' => AuthType::BEARER_TOKEN,
        'auth_meta' => ['token' => env('GITHUB_TOKEN')],
    ],
],
```

### النهايات (endpoints)
تحدد الـmethod والمسار والـheaders والـquery والـbody والمابينغ.
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

## القواعد (FILE)
ملف JSON بنفس بنية rules.php.
الملف الافتراضي: `storage/rules.json`.

مثال:
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

## القواعد (DATABASE)
المكتبة توفر migrations للجداول التالية:
- Providers: URL + بيانات المصادقة
- Endpoints: method/path/headers/query/body
- Mappings: قواعد التحويل بصيغة JSON

## قواعد التحويل (Mapping DSL)
- نص => مسار dot
- قيمة ثابتة => ثابت
- مصفوفة/كائن => تحويل تكراري
- `@each` + `map` => تحويل قوائم

مثال:
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

### مثال ماب لمصفوفة
عند كون الاستجابة قائمة عناصر:
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

### مثال ماب لكائن مفرد
عند كون الاستجابة كائن واحد:
```php
'rules' => [
    'id' => 'id',
    'name' => 'title',
    'content' => 'body',
],
```

## أنواع المصادقة
- `NONE`
- `BEARER_TOKEN` (token أو auth_meta.token)
- `API_KEY` (auth_meta: name, value, in)
- `BASIC` (auth_meta: username, password)

## تشغيل تكامل
```php
$result = Integration::run('provider_key', 'endpoint_key', [
    'userId' => 1,
]);
```

## تنفيذ متوازي (pool)
```php
use Omarsaiouf\Integrations\Integration\IntegrationManager;

/** @var IntegrationManager $manager */
$manager = app('integration.manager');

$result = $manager->pool([
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

## التسجيل (Logging)
التسجيل الافتراضي يحفظ كل تشغيل (نجاح/فشل) مع لقطة من الطلب والاستجابة، ويقوم بإخفاء الهيدرز الحساسة.

## الاختبارات
```bash
composer update
vendor/bin/phpunit
```

## الرخصة
MIT

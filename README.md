# laravel-json-response

[![Latest Version](https://img.shields.io/packagist/v/sanjanarathnyke/laravel-json-response.svg)](https://packagist.org/packages/sanjanarathnyke/laravel-json-response)
[![License](https://img.shields.io/github/license/sanjanarathnyke/laravel-json-response.svg)](LICENSE)

A Laravel package designed to enforce a consistent JSON API response structure across applications, improving maintainability and API clarity.

Table of Contents
- [Why use this package](#why-use-this-package)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick usage](#quick-usage)
- [Response structure](#response-structure)
- [Configuration](#configuration)
- [Customization & extension](#customization--extension)
- [Examples](#examples)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)
- [Credits](#credits)

Why use this package
- Standardizes success, error and paginated JSON responses across your app.
- Removes boilerplate response code from controllers.
- Makes your API predictable and easier to document and consume.

Requirements
- PHP >= 8.0
- Laravel >= 9.x (adjust as necessary for your supported versions)

Installation

1. Install via Composer

```bash
composer require sanjanarathnyke/laravel-json-response
```

2. (Optional) Publish configuration and assets

If the package provides a config file, publish it with:

```bash
php artisan vendor:publish --provider="SanjanaRathnyke\LaravelJsonResponse\ServiceProvider" --tag="config"
```

Note: The package supports Laravel package auto-discovery. If auto-discovery is disabled, register the service provider manually in `config/app.php`:

```php
'providers' => [
    // ...
    SanjanaRathnyke\LaravelJsonResponse\ServiceProvider::class,
];
```

Quick usage

The package exposes simple helpers/facade methods for returning standardized responses. Replace names below with the actual class/facade/helper names if they differ.

Controller — success response:

```php
use Illuminate\Http\JsonResponse;
use SanjanaRathnyke\LaravelJsonResponse\Facades\JsonResponse;

class UserController extends Controller
{
    public function show(User $user): JsonResponse
    {
        return JsonResponse::success($user, 'User retrieved successfully');
    }
}
```

Controller — error response:

```php
return JsonResponse::error('Resource not found', 404);
```

Controller — validation error:

```php
return JsonResponse::errorValidation($validator->errors(), 'Validation failed', 422);
```

Response structure

Success response example:

```json
{
  "success": true,
  "data": { /* resource data */ },
  "message": "Operation successful",
  "meta": {}
}
```

Error response example:

```json
{
  "success": false,
  "error": {
    "code": 422,
    "message": "Validation failed",
    "errors": {
      "email": ["The email field is required."]
    }
  }
}
```

Paginated response example:

```json
{
  "success": true,
  "data": [ /* items */ ],
  "message": "List of items",
  "meta": {
    "pagination": {
      "total": 100,
      "count": 10,
      "per_page": 10,
      "current_page": 1,
      "total_pages": 10
    }
  }
}
```

Configuration

When published, the configuration (example: `config/jsonresponse.php`) may include options such as:

```php
return [
    'keys' => [
        'success' => 'success',
        'data' => 'data',
        'message' => 'message',
        'error' => 'error',
        'meta' => 'meta',
    ],

    'defaults' => [
        'success_message' => 'Operation successful.',
        'error_message' => 'An error occurred.',
        'success_code' => 200,
        'error_code' => 400,
    ],

    'include_debug' => env('APP_DEBUG', false),
];
```

Customization & extension

- Transformers: Use Laravel API Resources or pass transformer callbacks to shape your `data`.
- Global wrapping: Register middleware to wrap all responses automatically.
- Exception handling: In `app/Exceptions/Handler.php` you can call the package's error helper in `render()` or `register()` to ensure all exceptions return the standardized JSON shape.

Examples

Return a paginated collection using a Resource:

```php
$users = UserResource::collection(User::paginate(15));

return JsonResponse::success(
    $users,
    'Users list',
    [
        'pagination' => [
            'total' => $users->resource->total(),
            'count' => $users->resource->count(),
            'per_page' => $users->resource->perPage(),
            'current_page' => $users->resource->currentPage(),
            'total_pages' => $users->resource->lastPage(),
        ]
    ]
);
```

Return a custom error payload:

```php
return JsonResponse::error(
    'Payment required to access this endpoint',
    402,
    ['policy' => 'upgrade_account']
);
```

Testing

Run the package test suite:

```bash
composer install
vendor/bin/phpunit
# or if the project uses Pest
vendor/bin/pest
```

Ensure test environment variables are configured (`.env.testing` or `phpunit.xml`) for database and other services used in tests.

Contributing

Contributions are welcome — thank you!

- Fork the repository
- Create a feature branch: git checkout -b feature/your-feature
- Write tests for your change
- Adhere to PSR-12 coding style
- Open a pull request describing your changes

Please open issues for bug reports or feature requests.

License

This project is open source and available under the MIT License. See the LICENSE file for details.

Credits

- Built and maintained by [sanjanarathnyke](https://github.com/sanjanarathnyke)

Notes / Next steps

- If any class names, facade names, helper functions, publish tags, or the configuration file differ from the examples above, tell me the exact names and I will update this README to match the code in your repository.
- If you want, I can create a PR that replaces the existing README with this version — tell me and provide any additional clarifications (supported Laravel/PHP versions, exact usage examples) and I will prepare the PR.

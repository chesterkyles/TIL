# Laravel Sanctum

## Introduction

Laravel Sanctum provides a featherweight authentication system for SPAs (single page applications), mobile applications, and simple, token based APIs. Sanctum allows each user of your application to generate multiple API tokens for their account. These tokens may be granted abilities / scopes which specify which actions the tokens are allowed to perform.

- Sanctum is a simple package you may use to issue API tokens to your users **without the complication of OAuth**.
  - This feature is inspired by GitHub and other applications which issue **"personal access tokens"**.
- Sanctum exists to offer a **simple way to authenticate single page applications (SPAs)** that need to communicate with a Laravel powered API.
  - Sanctum does not use tokens of any kind. Instead, Sanctum uses **Laravel's built-in cookie based session authentication services**.
  - Sanctum utilizes Laravel's `web` authentication guard to accomplish this. This provides the benefits of CSRF protection, session authentication, as well as protects against leakage of the authentication credentials via XSS.

## Installation

```
composer require laravel/sanctum
```

Publish the Sanctum configuration and migration files:

```
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

To authenticate an SPA, add Sanctum's middleware to your `api` middleware group within your application's `app/Http/Kernel.php file`:

```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

## API Token Authentication

### Issuing API Tokens

When making requests using API tokens, the token should be included in the `Authorization` header as a `Bearer` token.

To begin issuing tokens for users, your User model should use the `Laravel\Sanctum\HasApiTokens` trait:

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

To issue a token, you may use the `createToken` method. The `createToken` method returns a `Laravel\Sanctum\NewAccessToken` instance. API tokens are hashed using SHA-256 hashing before being stored in your database, but you may access the plain-text value of the token using the `plainTextToken` property of the `NewAccessToken` instance. You should display this value to the user immediately after the token has been created:

```php
use Illuminate\Http\Request;

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});
```

### Token Abilities

```php
return $user->createToken('token-name', ['server:update'])->plainTextToken;
```

To determine if the token has a given ability using the `tokenCan` method:

```php
if ($user->tokenCan('server:update')) {
    //
}
```

### Protecting Routes

This guard will ensure that incoming requests are authenticated as either stateful, cookie authenticated requests or contain a valid API token header if the request is from a third party.

Sanctum will attempt to authenticate the request using a token in the request's `Authorization` header. In addition, authenticating all requests using Sanctum ensures that we may always call the `tokenCan` method on the currently authenticated user instance:

```php
use Illuminate\Http\Request;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
```

### Revoking Tokens
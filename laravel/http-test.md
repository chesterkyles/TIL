# HTTP Tests

## Example
 - See example source code from Kaigo Project. [sample_http_test.php](src/sample_http_test.php)

## Getting Started
Laravel provides a very fluent API for making HTTP requests to your application and examining the responses. For example, take a look at the feature test defined below:
```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_basic_request()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
```

The `get` method makes a `GET` request into the application, while the `assertStatus` method asserts that the returned response should have the given HTTP status code. In addition to this simple assertion, Laravel also contains a variety of assertions for inspecting the response headers, content, JSON structure, and more.

## Requests
 - To make a request to your application, you may invoke the `get`, `post`, `put`, `patch`, or `delete` methods within your test. See above example for `get` request.

### Customizing Request Headers
 - Use `withHeaders` method to customize the request's headers before it is sent to the application.
```php
    $response = $this->withHeaders([
        'X-Header' => 'Value',
    ])->post('/user', ['name' => 'Sally']);
```

### Cookie
 - Use `withCookie` or `withCookies` methods to set cookie value before making a request.
```php
    $response = $this->withCookie('color', 'blue')->get('/');

    $response = $this->withCookies([
        'color' => 'blue',
        'name' => 'Taylor',
    ])->get('/');
```

### Session/Authentication
 - Set the session data to a given array using the `withSession` method. This is useful for loading the session with data before issuing a request to the application.
```php
$response = $this->withSession(['banned' => false])->get('/');
```

- Laravel's session is typically used to maintain state for the currently authenticated user. Therefore, the `actingAs` helper method provides a simple way to authenticate a given user as the current user. For example, we may use a [model factory](https://laravel.com/docs/8.x/database-testing#defining-model-factories) to generate and authenticate a user:
```php
    $user = User::factory()->create();

    $response = $this->actingAs($user)
                     ->withSession(['banned' => false])
                     ->get('/');
```

- Also, specify which guard should be used to authenticate the given user by passing the guard name as the second argument to the `actingAs` method
```php
    $this->actingAs($user, 'api')
```

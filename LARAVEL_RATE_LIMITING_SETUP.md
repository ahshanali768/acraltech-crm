# Laravel Rate Limiting Setup for Cloudflare FREE Plan

Since Cloudflare FREE doesn't include rate limiting, implement it in Laravel:

## 1. Update routes/web.php

```php
// Add rate limiting to sensitive routes
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
});

Route::middleware(['auth', 'throttle:30,1'])->prefix('admin')->group(function () {
    // All your admin routes here
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    // ... other admin routes
});

Route::middleware(['throttle:60,1'])->prefix('api')->group(function () {
    // API routes with rate limiting
});
```

## 2. Update app/Http/Kernel.php

```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'throttle:120,1', // Global rate limiting - 120 requests per minute
    ],
];
```

## 3. Custom Rate Limiting Middleware

Create `app/Http/Middleware/CustomRateLimit.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class CustomRateLimit
{
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            throw new ThrottleRequestsException('Too Many Attempts.');
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        
        return $next($request);
    }
    
    protected function resolveRequestSignature($request)
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . $request->ip()
        );
    }
}
```

## 4. Add to Kernel.php

```php
protected $routeMiddleware = [
    // ... existing middleware
    'custom.throttle' => \App\Http\Middleware\CustomRateLimit::class,
];
```

## 5. Usage Examples

```php
// Strict rate limiting for login
Route::middleware(['custom.throttle:3,5'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Moderate rate limiting for admin
Route::middleware(['custom.throttle:100,1'])->group(function () {
    // Admin routes
});
```

This compensates for Cloudflare FREE's lack of rate limiting!

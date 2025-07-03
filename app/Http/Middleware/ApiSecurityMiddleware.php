<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SecuritySetting;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Response;

class ApiSecurityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $settings = SecuritySetting::first();

        if ($settings->api_rate_limiting) {
            $key = 'api:' . ($request->user()?->id ?? $request->ip());
            
            if (RateLimiter::tooManyAttempts($key, $settings->api_rate_limit_per_minute)) {
                return response()->json([
                    'error' => 'Too many requests',
                    'retry_after' => RateLimiter::availableIn($key)
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            RateLimiter::hit($key);
        }

        // Validate API key if required
        if ($request->route()->middleware('api.key')) {
            $apiKey = $request->header('X-API-Key');
            
            if (!$apiKey || !$this->validateApiKey($apiKey)) {
                return response()->json([
                    'error' => 'Invalid or missing API key'
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }

    protected function validateApiKey($key)
    {
        // Implement API key validation logic here
        // You can check against the api_keys table
        return \App\Models\ApiKey::where('key', $key)
            ->where('is_active', true)
            ->exists();
    }
}

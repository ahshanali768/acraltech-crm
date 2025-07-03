<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SecuritySetting;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ApiRateLimiting
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = SecuritySetting::getSettings();
        
        if (!$settings->api_rate_limiting) {
            return $next($request);
        }

        $key = $this->resolveRequestSignature($request);
        $maxAttempts = $settings->api_rate_limit_per_minute;

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            throw new TooManyRequestsHttpException(
                $this->limiter->availableIn($key),
                'Too Many Attempts.',
                null,
                [
                    'X-RateLimit-Limit' => $maxAttempts,
                    'X-RateLimit-Remaining' => 0,
                    'Retry-After' => $this->limiter->availableIn($key)
                ]
            );
        }

        $response = $next($request);

        $this->limiter->hit($key, 60); // Reset after 1 minute

        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $this->limiter->remaining($key, $maxAttempts),
        ]);
    }

    /**
     * Resolve request signature for rate limiting.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return sha1('api|' . $user->id . '|' . $request->ip());
        }

        if ($token = $request->bearerToken()) {
            return sha1('api|' . $token . '|' . $request->ip());
        }

        return sha1('api|' . $request->ip());
    }
}

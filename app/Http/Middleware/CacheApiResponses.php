<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CacheApiResponses
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, int $ttl = 300)
    {
        // Don't cache authenticated user-specific requests
        if (Auth::check()) {
            return $next($request);
        }

        // Only cache GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        // Generate cache key based on request
        $cacheKey = $this->generateCacheKey($request);

        // Check if response is cached
        if (Cache::has($cacheKey)) {
            $cachedResponse = Cache::get($cacheKey);
            
            return response()->json($cachedResponse['data'])
                ->header('X-Cache-Status', 'HIT')
                ->header('X-Cache-Key', $cacheKey);
        }

        $response = $next($request);

        // Cache successful JSON responses
        if ($response->isSuccessful() && 
            $response->headers->get('Content-Type') === 'application/json') {
            
            $data = json_decode($response->getContent(), true);
            
            Cache::put($cacheKey, [
                'data' => $data,
                'cached_at' => now()->toISOString()
            ], $ttl);

            $response->header('X-Cache-Status', 'MISS');
        }

        return $response;
    }

    private function generateCacheKey(Request $request): string
    {
        $uri = $request->getRequestUri();
        $query = $request->getQueryString();
        
        return 'api_cache:' . md5($uri . '|' . $query);
    }
}

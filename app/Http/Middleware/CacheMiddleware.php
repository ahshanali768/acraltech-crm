<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $minutes = 60): Response
    {
        // Only cache GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        $cacheKey = 'api_cache_' . md5($request->fullUrl());
        
        // Check if cached response exists
        if (Cache::has($cacheKey)) {
            $cachedResponse = Cache::get($cacheKey);
            $response = response($cachedResponse['content'], $cachedResponse['status']);
            $response->header('X-Cache-Status', 'HIT');
            return $response;
        }

        // Process request
        $response = $next($request);
        
        // Cache successful responses
        if ($response->getStatusCode() === 200) {
            Cache::put($cacheKey, [
                'content' => $response->getContent(),
                'status' => $response->getStatusCode()
            ], $minutes * 60);
            
            $response->header('X-Cache-Status', 'MISS');
        }

        return $response;
    }
}

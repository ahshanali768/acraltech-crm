<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerformanceOptimization
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        $startQueries = $this->getQueryCount();

        $response = $next($request);

        // Calculate performance metrics
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        $endQueries = $this->getQueryCount();

        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = $endMemory - $startMemory;
        $queryCount = $endQueries - $startQueries;

        // Add performance headers for debugging
        if (config('app.debug')) {
            $response->headers->set('X-Execution-Time', round($executionTime, 2) . 'ms');
            $response->headers->set('X-Memory-Usage', $this->formatBytes($memoryUsage));
            $response->headers->set('X-Query-Count', $queryCount);
        }

        // Log slow requests
        if ($executionTime > 1000) { // Log requests taking more than 1 second
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time_ms' => $executionTime,
                'memory_usage_bytes' => $memoryUsage,
                'query_count' => $queryCount,
                'user_id' => auth()->id(),
            ]);
        }

        // Store performance metrics for analytics
        $this->storePerformanceMetrics($request, $executionTime, $memoryUsage, $queryCount);

        return $response;
    }

    private function getQueryCount(): int
    {
        return collect(DB::getQueryLog())->count();
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function storePerformanceMetrics(Request $request, float $executionTime, int $memoryUsage, int $queryCount): void
    {
        // Only store metrics for important endpoints to avoid performance impact
        $importantEndpoints = [
            '/api/leads',
            '/api/campaigns',
            '/api/metrics/real-time',
            '/admin/analytics',
            '/dashboard',
        ];

        $path = $request->path();
        if (!in_array('/' . $path, $importantEndpoints)) {
            return;
        }

        // Store in cache with TTL of 1 hour
        $cacheKey = 'performance_metrics_' . md5($path) . '_' . date('Y_m_d_H');
        $metrics = Cache::get($cacheKey, []);

        $metrics[] = [
            'timestamp' => now()->toISOString(),
            'execution_time_ms' => $executionTime,
            'memory_usage_bytes' => $memoryUsage,
            'query_count' => $queryCount,
        ];

        // Keep only the last 100 measurements
        if (count($metrics) > 100) {
            $metrics = array_slice($metrics, -100);
        }

        Cache::put($cacheKey, $metrics, 3600); // 1 hour
    }
}

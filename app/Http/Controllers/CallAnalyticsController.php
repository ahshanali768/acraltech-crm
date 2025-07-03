<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CallAnalyticsController extends Controller
{
    /**
     * Get real-time metrics
     */
    public function realTimeMetrics(Request $request): JsonResponse
    {
        // Mock data for now - in a real implementation, this would fetch actual call analytics
        return response()->json([
            'total_calls_today' => rand(50, 200),
            'active_agents' => rand(5, 20),
            'average_call_duration' => rand(120, 300),
            'conversion_rate' => rand(15, 35),
            'revenue_today' => rand(1000, 5000),
        ]);
    }
}

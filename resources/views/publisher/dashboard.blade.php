@extends('layouts.publisher')

@section('title', 'Publisher Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome back, {{ $publisher->name }}!</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Here's your call tracking performance overview</p>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Calls -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <i class="fas fa-phone text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_calls'] }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Total Calls</p>
                </div>
            </div>
        </div>

        <!-- Calls Today -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <i class="fas fa-phone-volume text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['calls_today'] }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Calls Today</p>
                </div>
            </div>
        </div>

        <!-- Conversion Rate -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <i class="fas fa-percentage text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['conversion_rate'] }}%</p>
                    <p class="text-gray-600 dark:text-gray-400">Conversion Rate</p>
                </div>
            </div>
        </div>

        <!-- Monthly Earnings -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <i class="fas fa-dollar-sign text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($stats['earnings_this_month'], 2) }}</p>
                    <p class="text-gray-600 dark:text-gray-400">This Month</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Tracking Information -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Tracking Setup</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Your Tracking DID</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-lg font-mono text-gray-900 dark:text-white">
                                @if(isset($stats['tracking_did']) && $stats['tracking_did'])
                                    {{ $publisher->formatted_tracking_did }}
                                @else
                                    <span class="text-gray-500">Not assigned yet</span>
                                @endif
                            </p>
                        </div>
                        @if(isset($stats['tracking_did']) && $stats['tracking_did'])
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Give this number to your leads</p>
                        @else
                            <p class="text-xs text-red-500 mt-1">Contact admin to get your tracking DID assigned</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Forwards to</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-lg font-mono text-gray-900 dark:text-white">
                                @if(isset($stats['destination_did']) && $stats['destination_did'])
                                    {{ $publisher->formatted_destination_did }}
                                @else
                                    <span class="text-gray-500">Not configured</span>
                                @endif
                            </p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Main buyer line</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Payout Rate</label>
                        <div class="mt-1 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-lg font-semibold text-green-700 dark:text-green-400">
                                ${{ number_format($stats['payout_rate'], 2) }}
                                @if($stats['payout_type'] === 'per_call')
                                    per call
                                @else
                                    % commission
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Account Status</label>
                        <div class="mt-1">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($stats['status'] === 'active') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($stats['status'] === 'inactive') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @endif">
                                {{ ucfirst($stats['status']) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call Activity Chart -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Call Activity (Last 7 Days)</h3>
                
                <div class="space-y-3">
                    @foreach($dailyStats as $stat)
                    <div class="flex items-center">
                        <div class="w-16 text-sm text-gray-600 dark:text-gray-400">{{ $stat['date'] }}</div>
                        <div class="flex-1 mx-4">
                            <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                @php
                                    $maxCalls = collect($dailyStats)->max('calls') ?: 1;
                                    $percentage = $maxCalls > 0 ? ($stat['calls'] / $maxCalls) * 100 : 0;
                                @endphp
                                <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-sm font-medium text-gray-900 dark:text-white text-right">{{ $stat['calls'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Calls -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Calls</h3>
                <a href="{{ route('publisher.call-history') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                    View All →
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Caller</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Earnings</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentCalls as $call)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $call->created_at->format('M j, Y g:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $call->caller_number ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ gmdate('H:i:s', $call->duration ?? 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($call->lead && $call->lead->status === 'approved')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                    Approved
                                </span>
                            @elseif($call->lead && $call->lead->status === 'pending')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                    Pending
                                </span>
                            @elseif($call->lead && $call->lead->status === 'rejected')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                    Rejected
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    No Lead
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            @if($call->lead && $call->lead->status === 'approved')
                                <span class="text-green-600 dark:text-green-400">
                                    ${{ number_format($publisher->payout_rate, 2) }}
                                </span>
                            @else
                                <span class="text-gray-400">$0.00</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500 dark:text-gray-400">
                                <i class="fas fa-phone text-4xl mb-4"></i>
                                <div class="text-lg font-medium mb-2">No calls yet</div>
                                <div class="text-sm">Start promoting your tracking DID to see calls here</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

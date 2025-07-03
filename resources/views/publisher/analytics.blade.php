@extends('layouts.publisher')

@section('title', 'Analytics & Reports')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Analytics & Reports</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Detailed performance metrics and call tracking data</p>
    </div>

    <!-- Monthly Performance Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Performance (Last 12 Months)</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ collect($monthlyStats)->sum('calls') }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Calls</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ collect($monthlyStats)->sum('leads') }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Approved Leads</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">${{ number_format(collect($monthlyStats)->sum('earnings'), 2) }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Earnings</div>
                </div>
            </div>

            <!-- Chart -->
            <div class="space-y-4">
                @foreach($monthlyStats as $stat)
                <div class="flex items-center">
                    <div class="w-20 text-sm text-gray-600 dark:text-gray-400">{{ $stat['month'] }}</div>
                    <div class="flex-1 mx-4">
                        <div class="flex space-x-2">
                            <!-- Calls Bar -->
                            <div class="flex-1">
                                <div class="bg-gray-200 dark:bg-gray-700 rounded h-6 relative">
                                    @php
                                        $maxCalls = collect($monthlyStats)->max('calls') ?: 1;
                                        $callsPercentage = $maxCalls > 0 ? ($stat['calls'] / $maxCalls) * 100 : 0;
                                    @endphp
                                    <div class="bg-blue-500 h-6 rounded transition-all duration-300 flex items-center justify-center text-white text-xs font-medium" 
                                         style="width: {{ $callsPercentage }}%">
                                        @if($stat['calls'] > 0){{ $stat['calls'] }}@endif
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Calls</div>
                            </div>
                            <!-- Leads Bar -->
                            <div class="flex-1">
                                <div class="bg-gray-200 dark:bg-gray-700 rounded h-6 relative">
                                    @php
                                        $maxLeads = collect($monthlyStats)->max('leads') ?: 1;
                                        $leadsPercentage = $maxLeads > 0 ? ($stat['leads'] / $maxLeads) * 100 : 0;
                                    @endphp
                                    <div class="bg-green-500 h-6 rounded transition-all duration-300 flex items-center justify-center text-white text-xs font-medium" 
                                         style="width: {{ $leadsPercentage }}%">
                                        @if($stat['leads'] > 0){{ $stat['leads'] }}@endif
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leads</div>
                            </div>
                        </div>
                    </div>
                    <div class="w-20 text-sm font-medium text-gray-900 dark:text-white text-right">
                        ${{ number_format($stat['earnings'], 0) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Call Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Call Logs</h3>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Caller Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lead Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lead Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Earnings</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($callLogs as $call)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $call->created_at->format('M j, Y g:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-white">
                            {{ $call->caller_number ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ gmdate('H:i:s', $call->duration ?? 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($call->lead)
                                @if($call->lead->status === 'approved')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        Approved
                                    </span>
                                @elseif($call->lead->status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                        Rejected
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    No Lead
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            @if($call->lead)
                                <div>
                                    <div class="font-medium">{{ $call->lead->first_name }} {{ $call->lead->last_name }}</div>
                                    <div class="text-gray-500 dark:text-gray-400">{{ $call->lead->email }}</div>
                                </div>
                            @else
                                <span class="text-gray-400">No lead data</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
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
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500 dark:text-gray-400">
                                <i class="fas fa-chart-bar text-4xl mb-4"></i>
                                <div class="text-lg font-medium mb-2">No data available</div>
                                <div class="text-sm">Call logs will appear here once you start receiving calls</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($callLogs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $callLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

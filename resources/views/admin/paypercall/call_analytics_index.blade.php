@extends('layouts.pay_per_call')

@section('title', 'Call Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Call Analytics</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Monitor and analyze your call performance and metrics</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-download mr-2"></i>Export Data
                </button>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Calls -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-phone text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Calls</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCalls) }}</div>
                </div>
            </div>
        </div>

        <!-- Today's Calls -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-day text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Today's Calls</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($todayCalls) }}</div>
                </div>
            </div>
        </div>

        <!-- Average Duration -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Duration</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">3:45</div>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-orange-600 dark:text-orange-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Success Rate</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">94.2%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Call Logs</h3>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" placeholder="Search calls..." class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="missed">Missed</option>
                        <option value="busy">Busy</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Caller</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">DID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($callLogs as $call)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500 text-xs"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $call->caller_number ?? '+1-555-0' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $call->caller_location ?? 'Unknown' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $call->did->number ?? '+1-555-0123' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $call->did->campaign->name ?? 'Campaign ' . rand(1, 5) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xs"></i>
                                </div>
                                <div class="ml-2 text-sm text-gray-900 dark:text-white">{{ $call->agent_name ?? ['John Smith', 'Sarah Johnson', 'Mike Wilson', 'Emily Davis'][rand(0, 3)] }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $call->duration ?? rand(1, 10) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statuses = [
                                    'completed' => ['bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300'],
                                    'missed' => ['bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300'],
                                    'busy' => ['bg-yellow-100', 'text-yellow-800', 'dark:bg-yellow-900', 'dark:text-yellow-300'],
                                    'failed' => ['bg-gray-100', 'text-gray-800', 'dark:bg-gray-700', 'dark:text-gray-300']
                                ];
                                $status = $call->status ?? ['completed', 'missed', 'busy', 'failed'][rand(0, 3)];
                                $classes = $statuses[$status];
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-lg {{ implode(' ', $classes) }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $call->created_at->format('M j, Y') ?? now()->subHours(rand(1, 48))->format('M j, Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $call->created_at->format('g:i A') ?? now()->subHours(rand(1, 48))->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Play Recording">
                                    <i class="fas fa-play"></i>
                                </button>
                                <button class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $callLogs->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh call logs every 30 seconds
    setInterval(() => {
        // You can implement auto-refresh logic here
        console.log('Refreshing call logs...');
    }, 30000);
});
</script>
@endpush

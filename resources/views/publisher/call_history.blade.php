@extends('layouts.publisher')

@section('title', 'Call History')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Call History</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Complete history of all calls received through your tracking DID</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option>All Time</option>
                        <option>Today</option>
                        <option>Yesterday</option>
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>This Month</option>
                        <option>Last Month</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lead Status</label>
                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option>All Statuses</option>
                        <option>Approved</option>
                        <option>Pending</option>
                        <option>Rejected</option>
                        <option>No Lead</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Duration</label>
                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option>Any Duration</option>
                        <option>30+ seconds</option>
                        <option>1+ minute</option>
                        <option>2+ minutes</option>
                        <option>5+ minutes</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <i class="fas fa-phone text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $callLogs->total() }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Total Calls</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $callLogs->where('lead.status', 'approved')->count() }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-400">Approved Leads</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <i class="fas fa-clock text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ gmdate('H:i:s', $callLogs->avg('duration') ?? 0) }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-400">Avg Duration</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <i class="fas fa-dollar-sign text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        ${{ number_format($callLogs->where('lead.status', 'approved')->count() * $publisher->payout_rate, 2) }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-400">Total Earnings</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Call History</h3>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Caller Information</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Call Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lead Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lead Information</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Earnings</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($callLogs as $call)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $call->created_at->format('M j, Y') }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $call->created_at->format('g:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono text-gray-900 dark:text-white">
                                {{ $call->caller_number ?? 'Unknown' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $call->caller_location ?? 'Location unknown' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                Duration: {{ gmdate('H:i:s', $call->duration ?? 0) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                @if($call->recording_url)
                                    <a href="#" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-play mr-1"></i>Recording
                                    </a>
                                @else
                                    No recording
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($call->lead)
                                @if($call->lead->status === 'approved')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        <i class="fas fa-check mr-1"></i>Approved
                                    </span>
                                @elseif($call->lead->status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                        <i class="fas fa-times mr-1"></i>Rejected
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    <i class="fas fa-minus mr-1"></i>No Lead
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($call->lead)
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <div class="font-medium">{{ $call->lead->first_name }} {{ $call->lead->last_name }}</div>
                                    <div class="text-gray-500 dark:text-gray-400">{{ $call->lead->email }}</div>
                                    @if($call->lead->phone)
                                        <div class="text-gray-500 dark:text-gray-400">{{ $call->lead->phone }}</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-gray-400">No lead data available</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($call->lead && $call->lead->status === 'approved')
                                <div class="text-sm font-medium text-green-600 dark:text-green-400">
                                    <i class="fas fa-dollar-sign mr-1"></i>${{ number_format($publisher->payout_rate, 2) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Paid</div>
                            @elseif($call->lead && $call->lead->status === 'pending')
                                <div class="text-sm font-medium text-yellow-600 dark:text-yellow-400">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Under review</div>
                            @else
                                <div class="text-sm font-medium text-gray-400">
                                    $0.00
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">No payout</div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500 dark:text-gray-400">
                                <i class="fas fa-phone text-4xl mb-4"></i>
                                <div class="text-lg font-medium mb-2">No calls found</div>
                                <div class="text-sm">Calls will appear here once you start receiving them through your tracking DID</div>
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

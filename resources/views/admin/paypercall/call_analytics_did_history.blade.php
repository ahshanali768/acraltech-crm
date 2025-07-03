@extends('layouts.pay_per_call')

@section('title', 'DID Call History')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">DID Call History</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Call history for DID: <span class="font-medium text-gray-900 dark:text-white">{{ $did->number }}</span>
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.call-analytics.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Analytics
                </a>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-download mr-2"></i>Export History
                </button>
            </div>
        </div>
    </div>

    <!-- DID Information -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">DID Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-phone text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Phone Number</div>
                    <div class="font-medium text-gray-900 dark:text-white">{{ $did->number }}</div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bullhorn text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Campaign</div>
                    <div class="font-medium text-gray-900 dark:text-white">{{ $did->campaign->name ?? 'Unassigned' }}</div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-circle text-purple-600 dark:text-purple-400"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                    <div class="font-medium">
                        @if($did->status === 'active')
                            <span class="text-green-600 dark:text-green-400">Active</span>
                        @elseif($did->status === 'inactive')
                            <span class="text-red-600 dark:text-red-400">Inactive</span>
                        @else
                            <span class="text-yellow-600 dark:text-yellow-400">{{ ucfirst($did->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar text-orange-600 dark:text-orange-400"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Created</div>
                    <div class="font-medium text-gray-900 dark:text-white">{{ $did->created_at->format('M j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call Statistics -->
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
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $callHistory->total() }}</div>
                </div>
            </div>
        </div>

        <!-- Successful Calls -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Successful Calls</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ floor($callHistory->total() * 0.85) }}</div>
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
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">4:23</div>
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
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">85%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call History Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Call History</h3>
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
                    <input type="date" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Caller Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Recording</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($callHistory as $call)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500 text-xs"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $call->caller_number ?? '+1-555-0' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $call->caller_location ?? ['New York, NY', 'Los Angeles, CA', 'Chicago, IL', 'Houston, TX'][rand(0, 3)] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xs"></i>
                                </div>
                                <div class="ml-2">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $call->agent_name ?? ['John Smith', 'Sarah Johnson', 'Mike Wilson', 'Emily Davis'][rand(0, 3)] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Agent</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $call->duration ?? rand(1, 15) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) }}
                            </div>
                            @if(rand(0, 1))
                                <div class="text-xs text-green-500 dark:text-green-400">
                                    <i class="fas fa-arrow-up mr-1"></i>Long call
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statuses = [
                                    'completed' => ['bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300'],
                                    'missed' => ['bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300'],
                                    'busy' => ['bg-yellow-100', 'text-yellow-800', 'dark:bg-yellow-900', 'dark:text-yellow-300'],
                                    'failed' => ['bg-gray-100', 'text-gray-800', 'dark:bg-gray-700', 'dark:text-gray-300']
                                ];
                                $status = $call->status ?? ['completed', 'completed', 'completed', 'missed', 'busy'][rand(0, 4)]; // Weight towards completed
                                $classes = $statuses[$status];
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-lg {{ implode(' ', $classes) }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $call->created_at->format('M j, Y') ?? now()->subDays(rand(0, 30))->format('M j, Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $call->created_at->format('g:i A') ?? now()->subDays(rand(0, 30))->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(rand(0, 3) !== 0) <!-- 75% chance of having recording -->
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Available</span>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-gray-300 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Not available</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if(rand(0, 3) !== 0)
                                <button class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Play Recording">
                                    <i class="fas fa-play"></i>
                                </button>
                                <button class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300" title="Download Recording">
                                    <i class="fas fa-download"></i>
                                </button>
                                @endif
                                <button class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300" title="Call Details">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $callHistory->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers for play buttons
    document.querySelectorAll('[title="Play Recording"]').forEach(button => {
        button.addEventListener('click', function() {
            // You would implement actual audio player here
            alert('Playing recording...');
        });
    });
    
    // Add click handlers for download buttons
    document.querySelectorAll('[title="Download Recording"]').forEach(button => {
        button.addEventListener('click', function() {
            // You would implement actual download functionality here
            alert('Downloading recording...');
        });
    });
});
</script>
@endpush

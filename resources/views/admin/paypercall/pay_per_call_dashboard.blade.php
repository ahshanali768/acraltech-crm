@extends('layouts.pay_per_call')

@section('title', 'Pay Per Call Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pay Per Call Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your pay-per-call campaigns, DIDs, and call analytics</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>New Campaign
                </button>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-phone mr-2"></i>Add DID
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total DIDs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-phone-alt text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total DIDs</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalDids) }}</div>
                </div>
            </div>
        </div>

        <!-- Active DIDs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active DIDs</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($activeDids) }}</div>
                </div>
            </div>
        </div>

        <!-- Total Calls -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-phone text-purple-600 dark:text-purple-400"></i>
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
                    <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-day text-orange-600 dark:text-orange-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Today's Calls</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($todayCalls) }}</div>
                </div>
            </div>
        </div>

        <!-- Monthly Cost -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-rupee-sign text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Cost</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($monthlyCost, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- DID Management Preview -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">DID Management</h3>
                <a href="{{ route('admin.dids.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Active DIDs</span>
                    <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ $activeDids }}</span>
                </div>
                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Available DIDs</span>
                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ $totalDids - $activeDids }}</span>
                </div>
                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Monthly Cost</span>
                    <span class="text-sm font-medium text-red-600 dark:text-red-400">₹{{ number_format($monthlyCost, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Call Analytics Preview -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Call Analytics</h3>
                <a href="{{ route('admin.call-analytics.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Calls</span>
                    <span class="text-sm font-medium text-purple-600 dark:text-purple-400">{{ number_format($totalCalls) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Today's Calls</span>
                    <span class="text-sm font-medium text-orange-600 dark:text-orange-400">{{ number_format($todayCalls) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Success Rate</span>
                    <span class="text-sm font-medium text-green-600 dark:text-green-400">--</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h3>
        <div class="space-y-3">
            <div class="flex items-center space-x-3 py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-phone text-blue-600 dark:text-blue-400 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900 dark:text-white">New DID assigned to Campaign</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">2 minutes ago</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900 dark:text-white">Call routing updated successfully</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">15 minutes ago</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 py-3">
                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 dark:text-purple-400 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900 dark:text-white">Daily analytics report generated</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">1 hour ago</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

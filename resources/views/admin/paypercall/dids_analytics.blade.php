@extends('layouts.pay_per_call')

@section('title', 'DID Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">DID Analytics</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Analyze performance and usage metrics for your DIDs</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-sync mr-2"></i>Refresh Data
                </button>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-download mr-2"></i>Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">25</div>
                </div>
            </div>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-green-600 dark:text-green-400">
                    <i class="fas fa-arrow-up mr-1"></i>8.3%
                </span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">vs last month</span>
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
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">18</div>
                </div>
            </div>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-green-600 dark:text-green-400">
                    <i class="fas fa-arrow-up mr-1"></i>5.9%
                </span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">vs last month</span>
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
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">1,247</div>
                </div>
            </div>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-green-600 dark:text-green-400">
                    <i class="fas fa-arrow-up mr-1"></i>12.5%
                </span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">vs last month</span>
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
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">₹12,450</div>
                </div>
            </div>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-red-600 dark:text-red-400">
                    <i class="fas fa-arrow-up mr-1"></i>3.2%
                </span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Call Volume by DID -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Performing DIDs</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">+1-555-0123</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Real Estate Campaign</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">342 calls</div>
                        <div class="text-xs text-green-600 dark:text-green-400">+18.2%</div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">+1-555-0124</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Insurance Campaign</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">287 calls</div>
                        <div class="text-xs text-green-600 dark:text-green-400">+12.8%</div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone text-purple-600 dark:text-purple-400 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">+1-555-0125</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Healthcare Campaign</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">219 calls</div>
                        <div class="text-xs text-red-600 dark:text-red-400">-5.3%</div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone text-orange-600 dark:text-orange-400 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">+1-555-0126</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Auto Campaign</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">156 calls</div>
                        <div class="text-xs text-green-600 dark:text-green-400">+8.7%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DID Status Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">DID Status Distribution</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Active</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">18 DIDs</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">(72%)</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Available</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">5 DIDs</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">(20%)</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Assigned</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">1 DID</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">(4%)</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Inactive</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">1 DID</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">(4%)</span>
                    </div>
                </div>
                
                <!-- Visual Progress Bars -->
                <div class="pt-4 space-y-3">
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Active DIDs</span>
                            <span>72%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 72%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Available DIDs</span>
                            <span>20%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 20%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">DID Performance Details</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">DID Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaign</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Calls</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Success Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avg Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Monthly Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @for($i = 1; $i <= 10; $i++)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">+1-555-012{{ $i }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ ['Real Estate', 'Insurance', 'Healthcare', 'Auto'][($i-1) % 4] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ rand(50, 350) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ rand(70, 95) }}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ rand(2, 8) }}:{{ str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">₹{{ rand(300, 800) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statuses = ['active', 'available', 'assigned', 'inactive'];
                                $status = $statuses[($i-1) % 4];
                                $statusClasses = [
                                    'active' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'available' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'assigned' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'inactive' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-lg {{ $statusClasses[$status] }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.call-analytics.did-history', ['did' => $i]) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300" title="Call History">
                                    <i class="fas fa-history"></i>
                                </a>
                                <button class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Edit DID">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

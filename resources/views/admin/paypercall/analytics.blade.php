@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Analytics Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-300">Real-time insights and performance metrics</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span>Live Data</span>
                    </div>
                    <button onclick="window.location.reload()" class="btn-accent">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Leads</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-leads">{{ $totalLeads ?? 0 }}</p>
                        <p class="text-sm text-green-600 dark:text-green-400">+{{ $leadsGrowth ?? 0 }}% this month</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved Leads</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="approved-leads">{{ $approvedLeads ?? 0 }}</p>
                        <p class="text-sm text-green-600 dark:text-green-400">{{ $approvalRate ?? 0 }}% approval rate</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Conversion Rate</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="conversion-rate">{{ $conversionRate ?? 0 }}%</p>
                        <p class="text-sm text-purple-600 dark:text-purple-400">Industry average: 15%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="revenue">${{ number_format($revenue ?? 0) }}</p>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400">This month</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Leads Trend Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Leads Trend (Last 30 Days)</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-md">Daily</button>
                        <button class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Weekly</button>
                        <button class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Monthly</button>
                    </div>
                </div>
                <div class="h-64" id="leads-chart">
                    <canvas id="leadsCanvas"></canvas>
                </div>
            </div>

            <!-- Service Performance -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Service Performance</h3>
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Auto Insurance</p>
                                <p class="text-sm text-gray-500">2,547 leads</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900 dark:text-white">23.5%</p>
                            <p class="text-sm text-green-600">+12%</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Home Insurance</p>
                                <p class="text-sm text-gray-500">1,832 leads</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900 dark:text-white">18.7%</p>
                            <p class="text-sm text-green-600">+8%</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Medicare</p>
                                <p class="text-sm text-gray-500">1,299 leads</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900 dark:text-white">21.2%</p>
                            <p class="text-sm text-green-600">+15%</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Credit Repair</p>
                                <p class="text-sm text-gray-500">987 leads</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900 dark:text-white">19.8%</p>
                            <p class="text-sm text-red-600">-3%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Geographic Distribution & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Geographic Distribution -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Geographic Distribution</h3>
                <div class="h-80" id="map-container">
                    <!-- This would contain a map visualization -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 h-full">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-lg p-4 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100">California</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300">2,847 leads</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-blue-600">28.5%</span>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-lg p-4 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-green-900 dark:text-green-100">Texas</h4>
                                <p class="text-sm text-green-700 dark:text-green-300">2,134 leads</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-green-600">21.3%</span>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-lg p-4 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-purple-900 dark:text-purple-100">Florida</h4>
                                <p class="text-sm text-purple-700 dark:text-purple-300">1,876 leads</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-purple-600">18.8%</span>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 rounded-lg p-4 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-yellow-900 dark:text-yellow-100">New York</h4>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">1,543 leads</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-yellow-600">15.4%</span>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-lg p-4 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-red-900 dark:text-red-100">Illinois</h4>
                                <p class="text-sm text-red-700 dark:text-red-300">1,299 leads</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-red-600">13.0%</span>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 rounded-lg p-4 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-indigo-900 dark:text-indigo-100">Others</h4>
                                <p class="text-sm text-indigo-700 dark:text-indigo-300">985 leads</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-indigo-600">9.8%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Recent Activity</h3>
                <div class="space-y-4" id="recent-activity">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Lead Approved</p>
                            <p class="text-sm text-gray-500">John Smith - Auto Insurance</p>
                            <p class="text-xs text-gray-400">2 minutes ago</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">New Lead</p>
                            <p class="text-sm text-gray-500">Sarah Johnson - Medicare</p>
                            <p class="text-xs text-gray-400">5 minutes ago</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Campaign Started</p>
                            <p class="text-sm text-gray-500">Q2 Auto Insurance Push</p>
                            <p class="text-xs text-gray-400">1 hour ago</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Payment Processed</p>
                            <p class="text-sm text-gray-500">$2,450 for 35 leads</p>
                            <p class="text-xs text-gray-400">2 hours ago</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.view_leads') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        View all activity →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Leads Trend Chart
    const ctx = document.getElementById('leadsCanvas').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Leads',
                data: [1200, 1900, 3000, 5000, 4200, 6000],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Real-time updates
    setInterval(updateDashboard, 30000); // Update every 30 seconds
});

async function updateDashboard() {
    try {
        const response = await fetch('/api/analytics/campaigns');
        const data = await response.json();
        
        // Update metrics if data is available
        if (data.total_leads) {
            document.getElementById('total-leads').textContent = data.total_leads.toLocaleString();
        }
        if (data.approved_leads) {
            document.getElementById('approved-leads').textContent = data.approved_leads.toLocaleString();
        }
        if (data.conversion_rate) {
            document.getElementById('conversion-rate').textContent = data.conversion_rate + '%';
        }
        
    } catch (error) {
        console.error('Failed to update dashboard:', error);
    }
}
</script>
@endsection

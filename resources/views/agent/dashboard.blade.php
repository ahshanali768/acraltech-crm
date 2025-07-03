@extends('layouts.agent')

@section('title', 'Agent Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .dashboard-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .dark .dashboard-card {
        background: linear-gradient(135deg, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.9) 100%);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .metric-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient);
    }
    .gradient-blue { --gradient: linear-gradient(90deg, #3B82F6, #8B5CF6); }
    .gradient-green { --gradient: linear-gradient(90deg, #10B981, #059669); }
    .gradient-yellow { --gradient: linear-gradient(90deg, #F59E0B, #D97706); }
    .gradient-purple { --gradient: linear-gradient(90deg, #8B5CF6, #7C3AED); }
    
    .pulse-animation {
        animation: pulse-glow 2s infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
        50% { box-shadow: 0 0 30px rgba(59, 130, 246, 0.6); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-blue-900 dark:to-indigo-900 p-6">
    <!-- Top Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 bg-clip-text text-transparent">
                    <i class="fas fa-user-tie mr-3"></i>Agent Dashboard
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mt-2">
                    Welcome back, <span class="font-semibold text-blue-600">{{ auth()->user()->name }}</span> • 
                    <span id="current-time" class="font-mono"></span>
                </p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('agent.leads.add') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Add Lead
                </a>
                <a href="{{ route('agent.leads.view') }}" class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-xl font-semibold border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                    <i class="fas fa-list mr-2"></i>View Leads
                </a>
            </div>
        </div>
    </div>

    <!-- Attendance Section -->
    <div class="mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Current Attendance Widget -->
            <div class="lg:col-span-1">
                <x-attendance-widget class="h-full" />
            </div>
            
            <!-- Attendance History Widget -->
            <div class="lg:col-span-1 xl:col-span-1">
                <x-attendance-history-widget class="h-full" />
            </div>
            
            <!-- Quick Actions/Summary -->
            <div class="lg:col-span-2 xl:col-span-1">
                <div class="dashboard-card rounded-2xl p-6 h-full">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-tachometer-alt mr-2"></i>Quick Overview
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-1 gap-4">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Shift Status</p>
                            <p class="font-semibold text-gray-900 dark:text-white">Night Shift</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-target text-green-600 dark:text-green-400"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Today's Target</p>
                            <p class="font-semibold text-gray-900 dark:text-white">10 Leads</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-chart-line text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Performance</p>
                            <p class="font-semibold text-gray-900 dark:text-white">Excellent</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Leads -->
        <div class="dashboard-card metric-card gradient-blue rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Leads</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalLeads ?? 0 }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-2">
                        <i class="fas fa-arrow-up text-xs mr-1"></i>
                        +12% from last month
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Approved Leads -->
        <div class="dashboard-card metric-card gradient-green rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $approvedLeads ?? 0 }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-2">
                        <i class="fas fa-arrow-up text-xs mr-1"></i>
                        +8% from last month
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Leads -->
        <div class="dashboard-card metric-card gradient-yellow rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $pendingLeads ?? 0 }}</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 flex items-center mt-2">
                        <i class="fas fa-clock text-xs mr-1"></i>
                        Awaiting review
                    </p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900/30 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="dashboard-card metric-card gradient-purple rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">₹{{ number_format($revenue ?? 0, 0) }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-2">
                        <i class="fas fa-arrow-up text-xs mr-1"></i>
                        +15% from last month
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-indian-rupee-sign text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Trend Chart -->
        <div class="dashboard-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Revenue Trend</h3>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg">7D</button>
                    <button class="px-3 py-1 text-xs text-gray-500 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">30D</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Lead Status Distribution -->
        <div class="dashboard-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Lead Status Distribution</h3>
            </div>
            <div class="h-80">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Recent Leads -->
        <div class="dashboard-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Leads</h3>
                <a href="{{ route('agent.leads.view') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recentLeads ?? [] as $lead)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600/50 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr($lead->first_name, 0, 1) }}{{ substr($lead->last_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $lead->first_name }} {{ $lead->last_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $lead->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            @if($lead->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                            @elseif($lead->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                            @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                            @endif">
                            {{ ucfirst($lead->status) }}
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $lead->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clipboard-list text-blue-500 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No leads yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Start by adding your first lead!</p>
                    <a href="{{ route('agent.leads.add') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-all">
                        <i class="fas fa-plus mr-2"></i>Add Lead
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Live Activity Feed -->
        <div class="dashboard-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Live Activity</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full pulse-animation"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold">Live</span>
                </div>
            </div>
            <div class="space-y-4 max-h-80 overflow-y-auto">
                <!-- Activity items -->
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-plus text-blue-600 dark:text-blue-400 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">New lead submitted</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">John Doe - Healthcare Campaign</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">2 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-green-600 dark:text-green-400 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Lead approved</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Jane Smith - Education Campaign</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">5 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-dollar-sign text-purple-600 dark:text-purple-400 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Commission earned</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">$45.00 from approved lead</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">12 minutes ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update current time
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    document.getElementById('current-time').textContent = timeString;
}
updateTime();
setInterval(updateTime, 1000);

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Revenue',
            data: [1200, 1900, 3000, 5000, 2000, 3000, 4500],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: 'rgb(59, 130, 246)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6
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
                    color: 'rgba(156, 163, 175, 0.1)'
                },
                ticks: {
                    color: 'rgba(107, 114, 128, 0.8)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(156, 163, 175, 0.1)'
                },
                ticks: {
                    color: 'rgba(107, 114, 128, 0.8)'
                }
            }
        }
    }
});

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const approvedLeads = {{ $approvedLeads ?? 45 }};
const pendingLeads = {{ $pendingLeads ?? 23 }};
const totalLeads = {{ $totalLeads ?? 80 }};
const rejectedLeads = totalLeads - approvedLeads - pendingLeads;

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Approved', 'Pending', 'Rejected'],
        datasets: [{
            data: [approvedLeads, pendingLeads, rejectedLeads],
            backgroundColor: [
                'rgb(34, 197, 94)',
                'rgb(251, 191, 36)',
                'rgb(239, 68, 68)'
            ],
            borderWidth: 0,
            cutout: '70%'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 25,
                    usePointStyle: true,
                    font: {
                        size: 14
                    }
                }
            }
        }
    }
});
</script>
@endsection

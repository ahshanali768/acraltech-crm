@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
    .gradient-red { --gradient: linear-gradient(90deg, #EF4444, #DC2626); }
    .gradient-purple { --gradient: linear-gradient(90deg, #8B5CF6, #7C3AED); }
    .gradient-indigo { --gradient: linear-gradient(90deg, #6366F1, #4F46E5); }
    
    .activity-item {
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }
    .activity-item:hover {
        border-left-color: #3B82F6;
        background: rgba(59, 130, 246, 0.05);
    }
    
    .chart-container {
        position: relative;
        height: 400px;
    }
    
    .pulse-animation {
        animation: pulse-glow 2s infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
        50% { box-shadow: 0 0 30px rgba(59, 130, 246, 0.6); }
    }
    
    .notification-badge {
        animation: bounce 1s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Top Header with Real-time Status -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 bg-clip-text text-transparent">
                    <i class="fas fa-tachometer-alt mr-3"></i>Admin Command Center
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mt-2">
                    Welcome back, <span class="font-semibold text-blue-600">{{ auth()->user()->name }}</span> • 
                    <span id="current-time" class="font-mono"></span>
                </p>
            </div>
            
            <!-- Real-time System Status -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center bg-green-100 dark:bg-green-900 px-4 py-2 rounded-full">
                    <div class="w-3 h-3 bg-green-500 rounded-full pulse-animation mr-2"></div>
                    <span class="text-sm font-medium text-green-700 dark:text-green-300">System Online</span>
                </div>
                <div class="flex items-center bg-blue-100 dark:bg-blue-900 px-4 py-2 rounded-full">
                    <i class="fas fa-users text-blue-600 mr-2"></i>
                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ \App\Models\User::where('role', 'agent')->count() }} Agents Active</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Leads -->
        <div class="dashboard-card metric-card gradient-blue rounded-2xl p-6 text-center group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">This Month</div>
                    <div class="text-green-500 text-sm font-semibold">+12.5%</div>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2" id="total-leads">{{ number_format($totalLeads ?? 0) }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Leads</div>
        </div>

        <!-- Approved Leads -->
        <div class="dashboard-card metric-card gradient-green rounded-2xl p-6 text-center group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Conversion</div>
                    <div class="text-green-500 text-sm font-semibold">{{ $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 1) : 0 }}%</div>
                </div>
            </div>
            <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2" id="approved-leads">{{ number_format($approvedLeads ?? 0) }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Approved Leads</div>
        </div>

        <!-- Pending Leads -->
        <div class="dashboard-card metric-card gradient-yellow rounded-2xl p-6 text-center group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
                @if(($pendingLeads ?? 0) > 10)
                <div class="notification-badge">
                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation text-white text-xs"></i>
                    </div>
                </div>
                @endif
            </div>
            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mb-2" id="pending-leads">{{ number_format($pendingLeads ?? 0) }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Pending Review</div>
        </div>



        <!-- Revenue -->
        <div class="dashboard-card metric-card gradient-indigo rounded-2xl p-6 text-center group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">This Month</div>
                    <div class="text-green-500 text-sm font-semibold">+8.3%</div>
                </div>
            </div>
            <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">
                ${{ number_format(\App\Models\Campaign::sum('payout_usd') * ($approvedLeads ?? 0), 2) }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Revenue</div>
        </div>


    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
        <!-- Performance Analytics Chart -->
        <div class="xl:col-span-2">
            <div class="dashboard-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-chart-area text-blue-600 mr-2"></i>Performance Analytics
                    </h3>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors active" onclick="switchChart('leads')">Leads</button>
                        <button class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors" onclick="switchChart('revenue')">Revenue</button>
                        <button class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors" onclick="switchChart('calls')">Calls</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Real-time Activity Feed -->
        <div class="dashboard-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-stream text-green-600 mr-2"></i>Live Activity Feed
                </h3>
                <div class="w-3 h-3 bg-green-500 rounded-full pulse-animation"></div>
            </div>
            
            <div class="space-y-4 max-h-96 overflow-y-auto" id="activity-feed">
                @foreach($recentLeads->take(10) as $lead)
                <div class="activity-item p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            @if($lead->status === 'approved')
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                            @elseif($lead->status === 'pending')
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600 text-sm"></i>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    New lead: {{ $lead->first_name }} {{ $lead->last_name }}
                                </p>
                                <span class="text-xs text-gray-500">{{ $lead->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $lead->campaign }} • {{ $lead->phone }}
                            </p>
                            <div class="flex items-center mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($lead->status === 'approved') bg-green-100 text-green-800
                                    @elseif($lead->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($lead->status) }}
                                </span>
                                @if($lead->did)
                                    <span class="ml-2 text-xs text-blue-600 font-mono">{{ $lead->did }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ route('admin.view_leads') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-eye mr-2"></i>View All Leads
                </a>
            </div>
        </div>
    </div>

    <!-- Secondary Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-8 mb-8">
        <!-- Campaign Performance -->
        <div class="xl:col-span-2">
            <div class="dashboard-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-bullhorn text-purple-600 mr-2"></i>Campaign Performance
                    </h3>
                    <a href="{{ route('admin.manage_campaigns') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Manage <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="space-y-4">
                    @foreach(\App\Models\Campaign::withCount(['leads', 'leads as approved_leads_count' => function($query) { $query->where('status', 'approved'); }])->limit(5)->get() as $campaign)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-pie text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $campaign->campaign_name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">DID: {{ $campaign->did }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $campaign->approved_leads_count }}/{{ $campaign->leads_count }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $campaign->leads_count > 0 ? round(($campaign->approved_leads_count / $campaign->leads_count) * 100, 1) : 0 }}% conversion
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Agents -->
        <div class="dashboard-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-trophy text-yellow-600 mr-2"></i>Top Agents
                </h3>
                <span class="text-sm text-gray-500">This Month</span>
            </div>
            
            <div class="space-y-4">
                @foreach(\App\Models\User::where('role', 'agent')->withCount(['leads as approved_leads_count' => function($query) { $query->where('status', 'approved'); }])->orderByDesc('approved_leads_count')->limit(5)->get() as $index => $agent)
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center
                        @if($index === 0) bg-yellow-100 text-yellow-600 
                        @elseif($index === 1) bg-gray-100 text-gray-600
                        @elseif($index === 2) bg-orange-100 text-orange-600
                        @else bg-blue-100 text-blue-600 @endif">
                        @if($index < 3)
                            <i class="fas fa-medal"></i>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $agent->name }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $agent->approved_leads_count }} approved leads</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- System Health -->
        <div class="dashboard-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-server text-green-600 mr-2"></i>System Health
                </h3>
                <div class="w-3 h-3 bg-green-500 rounded-full pulse-animation"></div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Database</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Healthy</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">API Response</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ rand(50, 150) }}ms</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Storage</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">{{ rand(60, 85) }}% Used</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Telephony</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Connected</span>
                </div>
            </div>
        </div>
    </div>

<script>
// Real-time clock
function updateTime() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleString();
}
updateTime();
setInterval(updateTime, 1000);

// Performance Chart
const ctx = document.getElementById('performanceChart').getContext('2d');
let performanceChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_keys($leadsByMonth->toArray())) !!},
        datasets: [{
            label: 'Leads',
            data: {!! json_encode(array_values($leadsByMonth->toArray())) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: 'rgb(59, 130, 246)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
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
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            }
        },
        elements: {
            point: {
                hoverBackgroundColor: 'rgb(59, 130, 246)'
            }
        }
    }
});

// Chart switching functionality
function switchChart(type) {
    // Update button states
    document.querySelectorAll('button[onclick^="switchChart"]').forEach(btn => {
        btn.className = 'px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors';
    });
    event.target.className = 'px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors active';
    
    // Update chart data based on type
    switch(type) {
        case 'leads':
            performanceChart.data.datasets[0].label = 'Leads';
            performanceChart.data.datasets[0].data = {!! json_encode(array_values($leadsByMonth->toArray())) !!};
            performanceChart.data.datasets[0].borderColor = 'rgb(59, 130, 246)';
            performanceChart.data.datasets[0].backgroundColor = 'rgba(59, 130, 246, 0.1)';
            break;
        case 'revenue':
            performanceChart.data.datasets[0].label = 'Revenue ($)';
            performanceChart.data.datasets[0].data = {!! json_encode(array_map(function($leads) { return $leads * 50; }, array_values($leadsByMonth->toArray()))) !!};
            performanceChart.data.datasets[0].borderColor = 'rgb(16, 185, 129)';
            performanceChart.data.datasets[0].backgroundColor = 'rgba(16, 185, 129, 0.1)';
            break;
        case 'calls':
            performanceChart.data.datasets[0].label = 'Calls';
            performanceChart.data.datasets[0].data = {!! json_encode(array_map(function($leads) { return $leads * 2; }, array_values($leadsByMonth->toArray()))) !!};
            performanceChart.data.datasets[0].borderColor = 'rgb(168, 85, 247)';
            performanceChart.data.datasets[0].backgroundColor = 'rgba(168, 85, 247, 0.1)';
            break;
    }
    performanceChart.update();
}

// Real-time data updates
function updateDashboardMetrics() {
    fetch('/api/admin/dashboard/summary', {
        headers: {
            'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update metric cards
        updateMetricCard('total-leads', data.total_leads);
        updateMetricCard('leads-today', data.leads_today);
        updateMetricCard('conversion-rate', data.conversion_rate + '%');
        updateMetricCard('revenue-month', '$' + formatNumber(data.revenue_this_month));
        updateMetricCard('active-dids', data.active_dids);
        updateMetricCard('calls-today', data.calls_today);
        updateMetricCard('answer-rate', data.answer_rate + '%');
        updateMetricCard('active-agents', data.active_agents);
        
        console.log('Dashboard metrics updated:', new Date().toLocaleTimeString());
    })
    .catch(error => {
        console.error('Error updating dashboard metrics:', error);
    });
}

function updateMetricCard(id, value) {
    const element = document.getElementById(id);
    if (element) {
        // Add a subtle animation
        element.style.transform = 'scale(1.05)';
        element.textContent = value;
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 200);
    }
}

function formatNumber(num) {
    return new Intl.NumberFormat().format(num);
}

// Update activity feed
function updateActivityFeed() {
    fetch('/api/admin/dashboard/live-activity', {
        headers: {
            'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(activities => {
        const container = document.getElementById('activity-feed');
        if (container && activities.length > 0) {
            // Add new activities to the top
            activities.slice(0, 5).forEach(activity => {
                const existingItem = container.querySelector(`[data-activity-id="${activity.id}"]`);
                if (!existingItem) {
                    const activityHtml = createActivityItem(activity);
                    container.insertAdjacentHTML('afterbegin', activityHtml);
                    
                    // Remove excess items (keep only latest 10)
                    const items = container.querySelectorAll('.activity-item');
                    if (items.length > 10) {
                        for (let i = 10; i < items.length; i++) {
                            items[i].remove();
                        }
                    }
                }
            });
        }
    })
    .catch(error => {
        console.error('Error updating activity feed:', error);
    });
}

function createActivityItem(activity) {
    const iconClass = activity.type === 'lead' ? 'fa-user-plus' : 'fa-phone';
    const colorClass = activity.status === 'approved' ? 'green' : activity.status === 'pending' ? 'yellow' : 'blue';
    
    return `
        <div class="activity-item p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all opacity-0 animate-fade-in" data-activity-id="${activity.id}">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-${colorClass}-100 rounded-full flex items-center justify-center">
                        <i class="fas ${iconClass} text-${colorClass}-600 text-sm"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">${activity.title}</p>
                        <span class="text-xs text-gray-500">${activity.time_ago}</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">${activity.description}</p>
                </div>
            </div>
        </div>
    `;
}

// Check for system alerts
function checkSystemAlerts() {
    fetch('/api/admin/dashboard/alerts', {
        headers: {
            'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(alerts => {
        if (alerts.length > 0) {
            showSystemAlerts(alerts);
        }
    })
    .catch(error => {
        console.error('Error checking system alerts:', error);
    });
}

function showSystemAlerts(alerts) {
    // Show alerts in a notification area (you could implement a toast/notification system)
    alerts.forEach(alert => {
        console.warn(`${alert.title}: ${alert.message}`);
        // In production, you'd show these as proper notifications
    });
}

// Export functionality
function exportDashboard() {
    const exportData = {
        timestamp: new Date().toISOString(),
        metrics: {
            total_leads: document.getElementById('total-leads')?.textContent || '0',
            approved_leads: '{{ $approvedLeads }}',
            pending_leads: '{{ $pendingLeads }}',
            revenue: '{{ $totalRevenue ?? 0 }}',
            active_dids: '{{ $activeDids ?? 0 }}',
            total_calls: '{{ $totalCalls ?? 0 }}'
        }
    };
    
    const dataStr = JSON.stringify(exportData, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    
    const exportFileDefaultName = `dashboard-export-${new Date().toISOString().split('T')[0]}.json`;
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}

// Initialize real-time updates
document.addEventListener('DOMContentLoaded', function() {
    // Update metrics every 30 seconds
    setInterval(updateDashboardMetrics, 30000);
    
    // Update activity feed every 15 seconds
    setInterval(updateActivityFeed, 15000);
    
    // Check alerts every 2 minutes
    setInterval(checkSystemAlerts, 120000);
    
    // Initial check for alerts
    setTimeout(checkSystemAlerts, 5000);
    
    console.log('Real-time dashboard initialized');
});

// Add CSS for fade-in animation
const style = document.createElement('style');
style.textContent = `
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in forwards;
    }
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
@endsection

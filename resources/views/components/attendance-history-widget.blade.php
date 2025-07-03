@props(['class' => ''])

<div x-data="attendanceHistoryWidget()" 
     x-init="init()"
     class="attendance-history-widget {{ $class }}">
    
    <!-- Attendance History Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar-alt text-white text-lg"></i>
                    <h3 class="text-white font-semibold text-sm">Attendance History</h3>
                </div>
                <div class="flex items-center space-x-1">
                    <button @click="refreshHistory()" 
                            :disabled="loading"
                            class="text-white hover:text-gray-200 transition-colors duration-200 disabled:opacity-50">
                        <i class="fas fa-sync-alt text-xs" :class="loading ? 'fa-spin' : ''"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Period Selector -->
        <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-3">
            <div class="flex items-center space-x-2">
                <select x-model="selectedPeriod" 
                        @change="loadHistory()"
                        class="text-xs bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="custom">Custom Range</option>
                </select>
                
                <!-- Custom Date Range -->
                <template x-if="selectedPeriod === 'custom'">
                    <div class="flex items-center space-x-1">
                        <input type="date" 
                               x-model="customRange.start"
                               @change="loadHistory()"
                               class="text-xs bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-2 py-1">
                        <span class="text-xs text-gray-500">to</span>
                        <input type="date" 
                               x-model="customRange.end"
                               @change="loadHistory()"
                               class="text-xs bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-2 py-1">
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Summary Stats -->
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50">
            <div class="grid grid-cols-3 gap-3 text-center">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Days</div>
                    <div class="text-sm font-semibold text-gray-900 dark:text-white" x-text="summary.total_days || 0"></div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Present</div>
                    <div class="text-sm font-semibold text-green-600 dark:text-green-400" x-text="summary.present_days || 0"></div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Hours</div>
                    <div class="text-sm font-semibold text-blue-600 dark:text-blue-400" x-text="summary.total_hours || '0:00'"></div>
                </div>
            </div>
        </div>
        
        <!-- Attendance Records -->
        <div class="max-h-80 overflow-y-auto">
            <template x-if="loading">
                <div class="flex items-center justify-center p-8">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Loading attendance history...</p>
                    </div>
                </div>
            </template>
            
            <template x-if="!loading && history.length === 0">
                <div class="flex items-center justify-center p-8">
                    <div class="text-center">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No attendance records found</p>
                    </div>
                </div>
            </template>
            
            <template x-if="!loading && history.length > 0">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="record in history" :key="record.date">
                        <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="formatDate(record.date)"></span>
                                        <span class="px-2 py-1 text-xs rounded-full"
                                              :class="getStatusBadgeClass(record)"
                                              x-text="getStatusText(record)"></span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 text-xs text-gray-500 dark:text-gray-400">
                                        <div>
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-sign-in-alt text-green-500 mr-1"></i>
                                                <span x-text="record.clock_in_time || '--:--'"></span>
                                            </span>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-sign-out-alt text-red-500 mr-1"></i>
                                                <span x-text="record.clock_out_time || '--:--'"></span>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <template x-if="record.working_hours">
                                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-clock mr-1"></i>
                                            <span x-text="record.working_hours"></span> hours worked
                                            <template x-if="record.break_time > 0">
                                                <span class="ml-2">
                                                    <i class="fas fa-coffee text-yellow-500 mr-1"></i>
                                                    <span x-text="formatMinutes(record.break_time)"></span> break
                                                </span>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Expand/Collapse Details -->
                                <template x-if="record.location_notes || record.clock_in_address">
                                    <button @click="toggleDetails(record.date)" 
                                            class="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <i class="fas fa-chevron-down transition-transform duration-200"
                                           :class="expandedRecords.includes(record.date) ? 'rotate-180' : ''"></i>
                                    </button>
                                </template>
                            </div>
                            
                            <!-- Expanded Details -->
                            <template x-if="expandedRecords.includes(record.date)">
                                <div x-transition
                                     class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600 space-y-2">
                                    
                                    <template x-if="record.clock_in_address">
                                        <div class="text-xs">
                                            <span class="text-gray-500 dark:text-gray-400">Clock-in Location:</span>
                                            <p class="text-gray-700 dark:text-gray-300 mt-1" x-text="record.clock_in_address"></p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="record.clock_out_address">
                                        <div class="text-xs">
                                            <span class="text-gray-500 dark:text-gray-400">Clock-out Location:</span>
                                            <p class="text-gray-700 dark:text-gray-300 mt-1" x-text="record.clock_out_address"></p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="record.location_notes">
                                        <div class="text-xs">
                                            <span class="text-gray-500 dark:text-gray-400">Notes:</span>
                                            <p class="text-gray-700 dark:text-gray-300 mt-1" x-text="record.location_notes"></p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="record.breaks && record.breaks.length > 0">
                                        <div class="text-xs">
                                            <span class="text-gray-500 dark:text-gray-400">Breaks:</span>
                                            <div class="mt-1 space-y-1">
                                                <template x-for="breakRecord in record.breaks" :key="breakRecord.id">
                                                    <div class="flex items-center justify-between text-gray-700 dark:text-gray-300">
                                                        <span class="capitalize" x-text="breakRecord.break_type"></span>
                                                        <span>
                                                            <span x-text="breakRecord.start_time"></span>
                                                            <template x-if="breakRecord.end_time">
                                                                <span> - <span x-text="breakRecord.end_time"></span></span>
                                                            </template>
                                                            <template x-if="breakRecord.duration_minutes">
                                                                <span class="ml-1">(<span x-text="breakRecord.duration_minutes"></span>min)</span>
                                                            </template>
                                                        </span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
        
        <!-- Footer with Export Options -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3 bg-gray-50 dark:bg-gray-700/50">
            <button @click="exportHistory()" 
                    :disabled="loading || history.length === 0"
                    class="w-full text-xs bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white py-2 px-3 rounded transition-colors duration-200 disabled:cursor-not-allowed">
                <i class="fas fa-download mr-1"></i>
                Export to CSV
            </button>
        </div>
    </div>
</div>

<script>
function attendanceHistoryWidget() {
    return {
        history: [],
        summary: {
            total_days: 0,
            present_days: 0,
            total_hours: '0:00'
        },
        loading: false,
        selectedPeriod: 'week',
        customRange: {
            start: '',
            end: ''
        },
        expandedRecords: [],
        
        init() {
            console.log('Attendance history widget initializing...');
            this.loadHistory();
        },
        
        async loadHistory() {
            this.loading = true;
            
            try {
                let url = '/agent/attendance/history';
                const params = new URLSearchParams();
                
                switch(this.selectedPeriod) {
                    case 'week':
                        params.append('period', 'week');
                        break;
                    case 'month':
                        params.append('period', 'month');
                        break;
                    case 'last_month':
                        params.append('period', 'last_month');
                        break;
                    case 'custom':
                        if (this.customRange.start && this.customRange.end) {
                            params.append('start_date', this.customRange.start);
                            params.append('end_date', this.customRange.end);
                        }
                        break;
                }
                
                if (params.toString()) {
                    url += '?' + params.toString();
                }
                
                const response = await fetch(url);
                if (response.ok) {
                    const data = await response.json();
                    this.history = data.records || [];
                    this.summary = data.summary || this.summary;
                    console.log('Loaded attendance history:', data);
                } else {
                    console.error('Failed to load attendance history:', response.status);
                }
            } catch (error) {
                console.error('Error loading attendance history:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async refreshHistory() {
            await this.loadHistory();
        },
        
        toggleDetails(date) {
            const index = this.expandedRecords.indexOf(date);
            if (index > -1) {
                this.expandedRecords.splice(index, 1);
            } else {
                this.expandedRecords.push(date);
            }
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            
            if (date.toDateString() === today.toDateString()) {
                return 'Today';
            } else if (date.toDateString() === yesterday.toDateString()) {
                return 'Yesterday';
            } else {
                return date.toLocaleDateString('en-US', { 
                    weekday: 'short', 
                    month: 'short', 
                    day: 'numeric' 
                });
            }
        },
        
        formatMinutes(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            if (hours > 0) {
                return `${hours}h ${mins}m`;
            }
            return `${mins}m`;
        },
        
        getStatusText(record) {
            if (!record.clock_in_time) return 'Absent';
            if (!record.clock_out_time) return 'Incomplete';
            if (record.is_late) return 'Late';
            return 'Present';
        },
        
        getStatusBadgeClass(record) {
            if (!record.clock_in_time) {
                return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
            }
            if (!record.clock_out_time) {
                return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
            }
            if (record.is_late) {
                return 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400';
            }
            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        },
        
        async exportHistory() {
            try {
                let url = '/agent/attendance/export';
                const params = new URLSearchParams();
                
                switch(this.selectedPeriod) {
                    case 'week':
                        params.append('period', 'week');
                        break;
                    case 'month':
                        params.append('period', 'month');
                        break;
                    case 'last_month':
                        params.append('period', 'last_month');
                        break;
                    case 'custom':
                        if (this.customRange.start && this.customRange.end) {
                            params.append('start_date', this.customRange.start);
                            params.append('end_date', this.customRange.end);
                        }
                        break;
                }
                
                if (params.toString()) {
                    url += '?' + params.toString();
                }
                
                // Create a temporary link to download the CSV
                const link = document.createElement('a');
                link.href = url;
                link.download = `attendance_${this.selectedPeriod}_${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                this.showNotification('Attendance history exported successfully!', 'success');
            } catch (error) {
                console.error('Export error:', error);
                this.showNotification('Failed to export attendance history', 'error');
            }
        },
        
        showNotification(message, type = 'info') {
            // Use the same notification system as the attendance widget
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;
            
            const bgColor = type === 'success' ? 'bg-green-500' : 
                            type === 'error' ? 'bg-red-500' : 
                            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
            
            notification.className += ` ${bgColor} text-white`;
            
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }
    }
}
</script>

<style>
.attendance-history-widget {
    /* Custom styles for the attendance history widget */
}
</style>

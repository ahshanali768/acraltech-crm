@extends('layouts.admin')

@section('title', 'Attendance Management')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Attendance Management</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Track and manage agent attendance, work hours, and presence</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.settings') }}#attendance" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                        <button onclick="exportAttendanceData()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export CSV
                        </button>
                        <button onclick="refreshData()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <?php
            // Get attendance statistics
            $totalAgents = \App\Models\User::where('role', 'agent')->count();
            $todayAttendance = \App\Models\AgentAttendance::whereDate('login_time', today())->count();
            $presentToday = \App\Models\AgentAttendance::whereDate('login_time', today())
                ->where('status', 'present')
                ->count();
            $lateToday = \App\Models\AgentAttendance::whereDate('login_time', today())
                ->where('status', 'late')
                ->count();
            $absentToday = \App\Models\AgentAttendance::whereDate('login_time', today())
                ->where('status', 'absent')
                ->count();
            $currentlyOnline = \App\Models\AgentAttendance::whereDate('login_time', today())
                ->whereNull('logout_time')
                ->count();

            // Attendance records will be provided by the controller

            // Get attendance settings
            $attendanceSettings = [
                'login_time' => \App\Models\Setting::get('attendance.shift.login_time', '19:30'),
                'logout_time' => \App\Models\Setting::get('attendance.shift.logout_time', '04:00'),
                'grace_period' => \App\Models\Setting::get('attendance.grace_period', 15),
            ];
            ?>

            <?php
            // Calculate additional stats
            $activeAgents = \App\Models\User::where('role', 'agent')->where('is_active', true)->count();
            $inactiveAgents = $totalAgents - $activeAgents;
            
            $yesterdayPresent = \App\Models\AgentAttendance::whereDate('login_time', today()->subDay())
                ->where('status', 'present')
                ->count();
                
            $yesterdayLate = \App\Models\AgentAttendance::whereDate('login_time', today()->subDay())
                ->where('status', 'late')
                ->count();
                
            $yesterdayAbsent = \App\Models\AgentAttendance::whereDate('login_time', today()->subDay())
                ->where('status', 'absent')
                ->count();
                
            $onBreakCount = \App\Models\AgentBreak::whereDate('start_time', today())
                ->whereNull('end_time')
                ->count();
                
            $weekAttendanceRate = 0;
            $weekAttendanceCount = \App\Models\AgentAttendance::whereDate('login_time', '>=', today()->startOfWeek())
                ->whereDate('login_time', '<=', today()->endOfWeek())
                ->count();
            
            $weekTotalPossible = $totalAgents * today()->diffInDays(today()->startOfWeek()) + $totalAgents;
            
            if ($weekTotalPossible > 0) {
                $weekAttendanceRate = round(($weekAttendanceCount / $weekTotalPossible) * 100, 1);
            }
            ?>
            
            <!-- Statistics Cards -->
            <!-- First row of cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Agents Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Agents</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $totalAgents ?></p>
                        </div>
                    </div>
                </div>

                <!-- Present Today Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Present Today</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $presentToday ?></p>
                        </div>
                    </div>
                </div>

                <!-- Late Today Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                            <svg class="w-7 h-7 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Late Today</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $lateToday ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second row of cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Absent Today Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                            <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Absent Today</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $absentToday ?></p>
                        </div>
                    </div>
                </div>

                <!-- Currently Online Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Currently Online</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $currentlyOnline ?></p>
                        </div>
                    </div>
                </div>

                <!-- Attendance Rate Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                            <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Attendance Rate</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $totalAgents > 0 ? round(($presentToday + $lateToday) / $totalAgents * 100, 1) : 0 ?>%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Today's Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2"><?= date('l') ?></div>
                        <div class="text-gray-600 dark:text-gray-400"><?= date('F j, Y') ?></div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Shift Time</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            <?= date('g:i A', strtotime($attendanceSettings['login_time'])) ?> - 
                            <?= date('g:i A', strtotime($attendanceSettings['logout_time'])) ?>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Grace Period</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white"><?= $attendanceSettings['grace_period'] ?> minutes</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter Attendance Records</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Date Range Filter -->
                    <div>
                        <label for="date_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                        <select id="date_filter" onchange="toggleCustomDateRange()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="today" {{ isset($filters) && $filters['date'] == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ isset($filters) && $filters['date'] == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="week" {{ !isset($filters) || $filters['date'] == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ isset($filters) && $filters['date'] == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="custom" {{ isset($filters) && $filters['date'] == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                        
                        <div id="custom_date_container" class="{{ isset($filters) && $filters['date'] == 'custom' ? '' : 'hidden' }} mt-3 space-y-3">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From</label>
                                <input type="date" id="date_from" value="{{ isset($filters) && $filters['date_from'] ? $filters['date_from'] : '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To</label>
                                <input type="date" id="date_to" value="{{ isset($filters) && $filters['date_to'] ? $filters['date_to'] : '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="status_filter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="all" {{ !isset($filters) || $filters['status'] == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="present" {{ isset($filters) && $filters['status'] == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="late" {{ isset($filters) && $filters['status'] == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="absent" {{ isset($filters) && $filters['status'] == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="half_day" {{ isset($filters) && $filters['status'] == 'half_day' ? 'selected' : '' }}>Half Day</option>
                        </select>
                    </div>
                    
                    <!-- Agent Filter -->
                    <div>
                        <label for="user_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Agent</label>
                        <select id="user_filter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="all" {{ !isset($filters) || $filters['user'] == 'all' ? 'selected' : '' }}>All Agents</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ isset($filters) && $filters['user'] == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Apply Button -->
                    <div class="flex items-end">
                        <button onclick="applyFilters()" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Attendance Records Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <?php
                            // Generate a descriptive title based on filters
                            $title = 'Attendance Records';
                            
                            if (isset($filters)) {
                                switch ($filters['date']) {
                                    case 'today':
                                        $title = "Today's Attendance";
                                        break;
                                    case 'yesterday':
                                        $title = "Yesterday's Attendance";
                                        break;
                                    case 'week':
                                        $title = "This Week's Attendance";
                                        break;
                                    case 'month':
                                        $title = "This Month's Attendance";
                                        break;
                                    case 'custom':
                                        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
                                            $title = "Attendance from " . date('M j, Y', strtotime($filters['date_from'])) . 
                                                     " to " . date('M j, Y', strtotime($filters['date_to']));
                                        } else {
                                            $title = "Custom Date Range Attendance";
                                        }
                                        break;
                                }

                                if ($filters['status'] !== 'all') {
                                    $title .= " (" . ucfirst($filters['status']) . ")";
                                }

                                if ($filters['user'] !== 'all') {
                                    $agent = \App\Models\User::find($filters['user']);
                                    if ($agent) {
                                        $title .= " for " . $agent->name;
                                    }
                                }
                            }
                            
                            echo $title;
                            ?>
                        </h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Showing <?= $attendance->count() ?> records (Page <?= $attendance->currentPage() ?> of <?= $attendance->lastPage() ?>)
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Login Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Logout Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hours Worked</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <?php if ($attendance->count() > 0): ?>
                                <?php foreach ($attendance as $record): ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-white">
                                                        <?= strtoupper(substr($record->user->name ?? 'NA', 0, 2)) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($record->user->name ?? 'N/A') ?></div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($record->user->username ?? 'N/A') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?= $record->login_time ? date('M j, Y', strtotime($record->login_time)) : 'N/A' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?= $record->login_time ? date('g:i A', strtotime($record->login_time)) : 'N/A' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?= $record->logout_time ? date('g:i A', strtotime($record->logout_time)) : 'Still Active' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $statusColors = [
                                            'present' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'late' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'absent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'half_day' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                        ];
                                        $statusColor = $statusColors[$record->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                        ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusColor ?>">
                                            <?= ucfirst($record->status) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php 
                                        if ($record->login_time && $record->logout_time) {
                                            $seconds = $record->logout_time->diffInSeconds($record->login_time);
                                            $hours = floor($seconds / 3600);
                                            $minutes = floor(($seconds % 3600) / 60);
                                            echo $hours . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <p class="text-lg font-medium">No attendance records found</p>
                                            <p class="text-sm">Attendance data will appear here once agents start logging in</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <?php if ($attendance->hasPages()): ?>
                            <div class="flex-1 flex justify-between sm:hidden">
                                <?php if ($attendance->onFirstPage()): ?>
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                                        Previous
                                    </span>
                                <?php else: ?>
                                    <a href="<?= $attendance->previousPageUrl() ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500">
                                        Previous
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($attendance->hasMorePages()): ?>
                                    <a href="<?= $attendance->nextPageUrl() ?>" class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500">
                                        Next
                                    </a>
                                <?php else: ?>
                                    <span class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                                        Next
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-400">
                                        Showing
                                        <span class="font-medium"><?= $attendance->firstItem() ?? 0 ?></span>
                                        to
                                        <span class="font-medium"><?= $attendance->lastItem() ?? 0 ?></span>
                                        of
                                        <span class="font-medium"><?= $attendance->total() ?></span>
                                        results
                                    </p>
                                </div>
                                
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <?php if ($attendance->onFirstPage()): ?>
                                            <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
                                                <span class="sr-only">Previous</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        <?php else: ?>
                                            <a href="<?= $attendance->previousPageUrl() ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Previous</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php
                                        $window = 2; // Show 2 pages on each side of current
                                        $totalPages = $attendance->lastPage();
                                        $currentPage = $attendance->currentPage();
                                        
                                        // Calculate start and end page
                                        $startPage = max(1, $currentPage - $window);
                                        $endPage = min($totalPages, $currentPage + $window);
                                        
                                        // Show first page if not in range
                                        if ($startPage > 1) {
                                            echo '<a href="' . $attendance->url(1) . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>';
                                            if ($startPage > 2) {
                                                echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                                            }
                                        }
                                        
                                        // Pages
                                        for ($i = $startPage; $i <= $endPage; $i++) {
                                            if ($i == $currentPage) {
                                                echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">' . $i . '</span>';
                                            } else {
                                                echo '<a href="' . $attendance->url($i) . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">' . $i . '</a>';
                                            }
                                        }
                                        
                                        // Show last page if not in range
                                        if ($endPage < $totalPages) {
                                            if ($endPage < $totalPages - 1) {
                                                echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                                            }
                                            echo '<a href="' . $attendance->url($totalPages) . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">' . $totalPages . '</a>';
                                        }
                                        ?>
                                        
                                        <?php if ($attendance->hasMorePages()): ?>
                                            <a href="<?= $attendance->nextPageUrl() ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Next</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        <?php else: ?>
                                            <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
                                                <span class="sr-only">Next</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        <?php endif; ?>
                                    </nav>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportAttendanceData() {
    // Get the same filters as the current view
    const dateFilter = document.getElementById('date_filter').value;
    const statusFilter = document.getElementById('status_filter').value;
    const userFilter = document.getElementById('user_filter').value;
    
    // Build export URL with filters
    const params = new URLSearchParams();
    params.append('date', dateFilter);
    
    if (dateFilter === 'custom') {
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = document.getElementById('date_to').value;
        
        if (dateFrom && dateTo) {
            params.append('date_from', dateFrom);
            params.append('date_to', dateTo);
        }
    }
    
    if (statusFilter !== 'all') {
        params.append('status', statusFilter);
    }
    
    if (userFilter !== 'all') {
        params.append('user', userFilter);
    }
    
    // Construct export URL using route name
    let exportUrl = '{{ route("admin.attendance.export") }}';
    if (params.toString()) {
        exportUrl += '?' + params.toString();
    }
    
    window.location.href = exportUrl;
}

function refreshData() {
    location.reload();
}

function toggleCustomDateRange() {
    const customDateContainer = document.getElementById('custom_date_container');
    const dateFilter = document.getElementById('date_filter').value;
    
    if (dateFilter === 'custom') {
        customDateContainer.classList.remove('hidden');
    } else {
        customDateContainer.classList.add('hidden');
    }
}

function applyFilters() {
    // Get filter values
    const dateFilter = document.getElementById('date_filter').value;
    const statusFilter = document.getElementById('status_filter').value;
    const userFilter = document.getElementById('user_filter').value;        // Build URL with filters
        const params = new URLSearchParams();
        
        // Add date filter
        params.append('date', dateFilter);
        
        // If custom date range is selected, add date_from and date_to
        if (dateFilter === 'custom') {
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;
            
            // Validate custom date range
            if (!dateFrom || !dateTo) {
                alert('Please select both From and To dates for the custom date range');
                return;
            }
            
            params.append('date_from', dateFrom);
            params.append('date_to', dateTo);
        }
    
    // Add status filter
    if (statusFilter !== 'all') {
        params.append('status', statusFilter);
    }
    
    // Add user filter
    if (userFilter !== 'all') {
        params.append('user', userFilter);
    }
    
    const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

// Auto-refresh every 30 seconds
setInterval(() => {
    // Only refresh if user is on the page and no forms are being filled
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 30000);
</script>
@endsection

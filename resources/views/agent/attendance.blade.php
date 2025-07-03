@extends('layouts.agent')

@section('title', 'Attendance')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Attendance Management</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Track your attendance and monitor your work hours</p>
            </div>

            <!-- Shift Information Section - New design -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Shift Information</h3>
                    <div class="flex space-x-4 items-center">
                        <label for="monthFilter" class="text-sm text-gray-600 dark:text-gray-400">Month:</label>
                        <select id="monthFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @php
                                $currentMonth = now()->month;
                                $months = [
                                    1 => 'January',
                                    2 => 'February',
                                    3 => 'March',
                                    4 => 'April',
                                    5 => 'May',
                                    6 => 'June',
                                    7 => 'July',
                                    8 => 'August',
                                    9 => 'September',
                                    10 => 'October',
                                    11 => 'November',
                                    12 => 'December'
                                ];
                            @endphp
                            
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Shift Details -->
                        <div class="lg:col-span-2">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ now()->format('l, F j, Y') }}</h4>
                                    <div class="flex space-x-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        <div>
                                            <i class="fas fa-clock text-green-500 mr-1"></i>
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $attendanceSettings['login_time'])->format('g:i A') }}
                                            -
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $attendanceSettings['logout_time'])->format('g:i A') }}
                                        </div>
                                        <div>
                                            <i class="fas fa-hourglass-half text-blue-500 mr-1"></i>
                                            Grace Period: {{ $attendanceSettings['grace_period'] }} minutes
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Summary -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Days</p>
                                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalDays }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Hours</p>
                                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalHours, 1) }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">This Month</p>
                                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $thisMonth }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="lg:col-span-1">
                            @if($todayAttendance)
                                @if(!$todayAttendance->logout_time)
                                    <!-- Checked In - Show Break and Logout buttons -->
                                    <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 mb-4">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-green-800 dark:text-green-300">Checked In</p>
                                                <p class="text-sm text-green-700 dark:text-green-400">
                                                    @if($todayAttendance->login_time)
                                                        Since {{ $todayAttendance->login_time->format('g:i A') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button id="breakButton" class="inline-flex justify-center items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors">
                                                <i class="fas fa-coffee mr-2"></i>Take Break
                                            </button>
                                            <button onclick="checkOut()" class="inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Check Out
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <!-- Completed Status -->
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6 text-center">
                                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 text-2xl"></i>
                                        </div>
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Attendance Completed</h4>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                                            @if($todayAttendance->login_time && $todayAttendance->logout_time)
                                                Worked from {{ $todayAttendance->login_time->format('g:i A') }} 
                                                to {{ $todayAttendance->logout_time->format('g:i A') }}
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            @else
                                <!-- Not Checked In - Show Login button -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-sign-in-alt text-blue-600 dark:text-blue-400 text-2xl"></i>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Start Your Workday</h4>
                                    <button onclick="checkIn()" class="inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-lg transition-colors w-full">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Check In Now
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance History -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Attendance History</h3>
                    <span id="currentMonthLabel" class="text-sm text-gray-500 dark:text-gray-400">{{ $months[$currentMonth] }} {{ now()->year }}</span>
                </div>
                
                <div id="attendanceRecords">
                    @if($attendanceRecords->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Login
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Logout
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hours
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($attendanceRecords as $record)
                                        <tr data-month="{{ $record->login_time ? $record->login_time->month : ($record->clock_in ? \Carbon\Carbon::parse($record->clock_in)->month : 0) }}">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    @if($record->login_time)
                                                        {{ $record->login_time->format('M j, Y') }}
                                                    @elseif($record->clock_in)
                                                        {{ \Carbon\Carbon::parse($record->clock_in)->format('M j, Y') }}
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    @if($record->login_time)
                                                        {{ $record->login_time->format('l') }}
                                                    @elseif($record->clock_in)
                                                        {{ \Carbon\Carbon::parse($record->clock_in)->format('l') }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    @if($record->login_time)
                                                        {{ $record->login_time->format('g:i A') }}
                                                    @elseif($record->clock_in)
                                                        {{ \Carbon\Carbon::parse($record->clock_in)->format('g:i A') }}
                                                    @endif
                                                </div>
                                                @if($record->address || $record->clock_in_address)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-32" title="{{ $record->address ?? $record->clock_in_address }}">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($record->address ?? $record->clock_in_address, 25) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($record->logout_time)
                                                    <div class="text-sm text-gray-900 dark:text-white">
                                                        {{ $record->logout_time->format('g:i A') }}
                                                    </div>
                                                @elseif($record->clock_out)
                                                    <div class="text-sm text-gray-900 dark:text-white">
                                                        {{ \Carbon\Carbon::parse($record->clock_out)->format('g:i A') }}
                                                    </div>
                                                @else
                                                    <span class="text-sm text-orange-600 dark:text-orange-400">Still active</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($record->logout_time && $record->login_time)
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $record->logout_time->diffInHours($record->login_time) }}h {{ $record->logout_time->diffInMinutes($record->login_time) % 60 }}m
                                                    </div>
                                                @elseif($record->clock_out && $record->clock_in)
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ \Carbon\Carbon::parse($record->clock_out)->diffInHours(\Carbon\Carbon::parse($record->clock_in)) }}h 
                                                        {{ \Carbon\Carbon::parse($record->clock_out)->diffInMinutes(\Carbon\Carbon::parse($record->clock_in)) % 60 }}m
                                                    </div>
                                                @elseif($record->login_time)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $record->login_time->diffInHours(now()) }}h {{ $record->login_time->diffInMinutes(now()) % 60 }}m
                                                        <span class="text-xs">(ongoing)</span>
                                                    </div>
                                                @elseif($record->clock_in)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($record->clock_in)->diffInHours(now()) }}h 
                                                        {{ \Carbon\Carbon::parse($record->clock_in)->diffInMinutes(now()) % 60 }}m
                                                        <span class="text-xs">(ongoing)</span>
                                                    </div>
                                                @else
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        --
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $record->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                    {{ ucfirst($record->status ?? 'present') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $attendanceRecords->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-clock text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Attendance Records</h3>
                            <p class="text-gray-600 dark:text-gray-400">Start tracking your attendance by logging in</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Break Modal -->
<div id="breakModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Take a Break</h3>
            <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500" onclick="closeBreakModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <label for="breakType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Break Type</label>
                <select id="breakType" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="lunch">Lunch Break</option>
                    <option value="coffee">Coffee Break</option>
                    <option value="personal">Personal Break</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="breakReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason (Optional)</label>
                <textarea id="breakReason" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Briefly describe your reason for taking a break"></textarea>
            </div>
            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600" onclick="closeBreakModal()">
                    Cancel
                </button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="startBreak()">
                    Start Break
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Check In function
function checkIn() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            
            // Send check-in request with coordinates
            fetch('{{ route("agent.attendance.checkin.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    latitude: latitude,
                    longitude: longitude,
                    address: 'Location coordinates recorded'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.error || 'Check in failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Check in failed');
            });
        }, function(error) {
            // Location access denied or unavailable
            if (confirm('Location access is required for login. Continue without location?')) {
                fetch('{{ route("agent.attendance.checkin.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        latitude: null,
                        longitude: null,
                        address: 'Location not available'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.error || 'Check in failed');
                    }
                });
            }
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

// Check Out function
function checkOut() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            
            fetch('{{ route("agent.attendance.checkout.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    latitude: latitude,
                    longitude: longitude,
                    address: 'Checkout location'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.error || 'Check out failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Check out failed');
            });
        }, function(error) {
            // Continue without location for checkout
            fetch('{{ route("agent.attendance.checkout.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    latitude: null,
                    longitude: null,
                    address: 'Location not available'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.error || 'Check out failed');
                }
            });
        });
    }
}

// Month Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const monthFilter = document.getElementById('monthFilter');
    const currentMonthLabel = document.getElementById('currentMonthLabel');
    const attendanceRows = document.querySelectorAll('tr[data-month]');
    
    // Initial filter to match current month
    filterAttendanceByMonth(monthFilter.value);
    
    // Update filter when month changes
    monthFilter.addEventListener('change', function() {
        const selectedMonth = this.value;
        
        // Update the month label
        const monthName = this.options[this.selectedIndex].text;
        currentMonthLabel.textContent = monthName + ' ' + new Date().getFullYear();
        
        // Option 1: Client-side filtering if we already have the data
        if (attendanceRows.length > 0) {
            filterAttendanceByMonth(selectedMonth);
        } 
        // Option 2: Fetch new data from the server
        else {
            fetchAttendanceData(selectedMonth);
        }
    });
    
    // Function to filter attendance records by month (client-side)
    function filterAttendanceByMonth(month) {
        let visibleCount = 0;
        
        attendanceRows.forEach(row => {
            const rowMonth = row.getAttribute('data-month');
            if (rowMonth == month || month === 'all') {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show "No records" message if no matching records
        const noRecordsMessage = document.getElementById('noRecordsMessage');
        const tableElement = document.querySelector('table');
        
        if (visibleCount === 0 && tableElement) {
            if (!noRecordsMessage) {
                const tbody = tableElement.querySelector('tbody');
                const noRecordsTr = document.createElement('tr');
                noRecordsTr.id = 'noRecordsMessage';
                noRecordsTr.innerHTML = `<td colspan="5" class="px-4 py-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No attendance records found for this month</p>
                </td>`;
                tbody.appendChild(noRecordsTr);
            }
        } else if (noRecordsMessage) {
            noRecordsMessage.remove();
        }
    }
    
    // Function to fetch attendance data from the server (AJAX)
    function fetchAttendanceData(month) {
        // Show loading state
        const recordsContainer = document.getElementById('attendanceRecords');
        recordsContainer.innerHTML = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        `;
        
        // Fetch data from API
        fetch(`/api/agent/attendance/by-month?month=${month}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.records) {
                // Update the attendance records table
                updateAttendanceTable(data.records);
            } else {
                recordsContainer.innerHTML = `
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clock text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Attendance Records</h3>
                        <p class="text-gray-600 dark:text-gray-400">No attendance data found for the selected month</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching attendance data:', error);
            recordsContainer.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Error Loading Data</h3>
                    <p class="text-gray-600 dark:text-gray-400">Could not load attendance records</p>
                </div>
            `;
        });
    }
    
    // Function to update attendance table with new data
    function updateAttendanceTable(records) {
        const recordsContainer = document.getElementById('attendanceRecords');
        
        if (records.length === 0) {
            recordsContainer.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Attendance Records</h3>
                    <p class="text-gray-600 dark:text-gray-400">No attendance data found for the selected month</p>
                </div>
            `;
            return;
        }
        
        // Create table HTML
        let tableHtml = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Login
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Logout
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Hours
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
        `;
        
        // Add rows for each record
        records.forEach(record => {
            tableHtml += `
                <tr>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            ${record.date}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            ${record.day}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            ${record.login_time}
                        </div>
                        ${record.address ? `
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-32" title="${record.address}">
                            <i class="fas fa-map-marker-alt mr-1"></i>${record.address.length > 25 ? record.address.substring(0, 25) + '...' : record.address}
                        </div>
                        ` : ''}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        ${record.logout_time ? `
                        <div class="text-sm text-gray-900 dark:text-white">
                            ${record.logout_time}
                        </div>
                        ` : `<span class="text-sm text-orange-600 dark:text-orange-400">Still active</span>`}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            ${typeof record.hours === 'number' ? Math.floor(record.hours) + 'h ' + Math.round((record.hours % 1) * 60) + 'm' : '--'}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                            ${record.status.toLowerCase() === 'present' ? 
                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'}">
                            ${record.status}
                        </span>
                    </td>
                </tr>
            `;
        });
        
        // Close table
        tableHtml += `
                    </tbody>
                </table>
            </div>
        `;
        
        // Update container
        recordsContainer.innerHTML = tableHtml;
    }
});

// Break Functionality
const breakButton = document.getElementById('breakButton');
const breakModal = document.getElementById('breakModal');

function openBreakModal() {
    breakModal.classList.remove('hidden');
}

function closeBreakModal() {
    breakModal.classList.add('hidden');
}

function startBreak() {
    const breakType = document.getElementById('breakType').value;
    const breakReason = document.getElementById('breakReason').value;
    
    fetch('{{ route("agent.attendance.break.start") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            break_type: breakType,
            reason: breakReason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeBreakModal();
            
            // Change the break button to "End Break"
            if (breakButton) {
                breakButton.innerHTML = '<i class="fas fa-hourglass-end mr-2"></i>End Break';
                breakButton.classList.remove('bg-yellow-500', 'hover:bg-yellow-600');
                breakButton.classList.add('bg-red-500', 'hover:bg-red-600');
                
                // Clear all previous event listeners using cloneNode
                const newBreakButton = breakButton.cloneNode(true);
                breakButton.parentNode.replaceChild(newBreakButton, breakButton);
                
                // Set the button to the new reference
                document.getElementById('breakButton').addEventListener('click', endBreak);
            }
            
            // Show a small notification
            showNotification('Break started successfully', 'success');
        } else {
            alert(data.error || 'Failed to start break');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to start break');
    });
}

function endBreak() {
    fetch('{{ route("agent.attendance.break.end") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset the break button to "Take Break"
            if (breakButton) {
                const takeBreakBtn = document.getElementById('breakButton');
                takeBreakBtn.innerHTML = '<i class="fas fa-coffee mr-2"></i>Take Break';
                takeBreakBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
                takeBreakBtn.classList.add('bg-yellow-500', 'hover:bg-yellow-600');
                
                // Clear all previous event listeners using cloneNode
                const newBreakButton = takeBreakBtn.cloneNode(true);
                takeBreakBtn.parentNode.replaceChild(newBreakButton, takeBreakBtn);
                
                // Add new event listener to the new button
                document.getElementById('breakButton').addEventListener('click', openBreakModal);
            }
            
            // Show a small notification
            showNotification('Break ended successfully', 'success');
        } else {
            alert(data.error || 'Failed to end break');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to end break');
    });
}

// Check for active breaks when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the break button with the modal open event
    if (breakButton) {
        breakButton.addEventListener('click', openBreakModal);
        
        // Check if the agent is already on a break
        fetch('{{ route("agent.attendance.break.status") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.onBreak) {
                // Agent is on a break, update the UI
                breakButton.innerHTML = '<i class="fas fa-hourglass-end mr-2"></i>End Break';
                breakButton.classList.remove('bg-yellow-500', 'hover:bg-yellow-600');
                breakButton.classList.add('bg-red-500', 'hover:bg-red-600');
                
                // Clear all previous event listeners using cloneNode
                const newBreakButton = breakButton.cloneNode(true);
                breakButton.parentNode.replaceChild(newBreakButton, breakButton);
                
                // Add end break handler to the new button
                document.getElementById('breakButton').addEventListener('click', endBreak);
            }
        })
        .catch(error => {
            console.error('Error checking break status:', error);
        });
    }
    
    // Month filter functionality (already existing)
    const monthFilter = document.getElementById('monthFilter');
    const currentMonthLabel = document.getElementById('currentMonthLabel');
    const attendanceRows = document.querySelectorAll('tr[data-month]');
    
    // Initial filter to match current month
    filterAttendanceByMonth(monthFilter.value);
    
    // Update filter when month changes
    monthFilter.addEventListener('change', function() {
        const selectedMonth = this.value;
        filterAttendanceByMonth(selectedMonth);
        
        // Update the month label
        const monthName = this.options[this.selectedIndex].text;
        currentMonthLabel.textContent = monthName + ' ' + new Date().getFullYear();
    });
    
    // Function to filter attendance records by month
    function filterAttendanceByMonth(month) {
        let visibleCount = 0;
        
        attendanceRows.forEach(row => {
            const rowMonth = row.getAttribute('data-month');
            if (rowMonth == month || month === 'all') {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show "No records" message if no matching records
        const noRecordsMessage = document.getElementById('noRecordsMessage');
        const tableElement = document.querySelector('table');
        
        if (visibleCount === 0 && tableElement) {
            if (!noRecordsMessage) {
                const tbody = tableElement.querySelector('tbody');
                const noRecordsTr = document.createElement('tr');
                noRecordsTr.id = 'noRecordsMessage';
                noRecordsTr.innerHTML = `<td colspan="5" class="px-4 py-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No attendance records found for this month</p>
                </td>`;
                tbody.appendChild(noRecordsTr);
            }
        } else if (noRecordsMessage) {
            noRecordsMessage.remove();
        }
    }
});

// Notification function
function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    notification.innerHTML = message;
    
    // Add to body
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}

// Close modal if clicking outside of it
breakModal.addEventListener('click', function(event) {
    if (event.target === breakModal) {
        closeBreakModal();
    }
});
</script>
@endsection

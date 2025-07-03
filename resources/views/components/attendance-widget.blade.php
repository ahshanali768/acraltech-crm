@props(['class' => ''])

<div x-data="attendanceWidget()" 
     x-init="init()"
     class="attendance-widget {{ $class }}">
    
    <!-- Attendance Status Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-clock text-white text-lg"></i>
                    <h3 class="text-white font-semibold text-sm">Attendance</h3>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-2 h-2 rounded-full" 
                         :class="status.is_clocked_in ? 'bg-green-400 animate-pulse' : 'bg-red-400'"></div>
                    <span class="text-white text-xs" x-text="status.is_clocked_in ? 'ON DUTY' : 'OFF DUTY'"></span>
                </div>
            </div>
        </div>
        
        <!-- Status Display -->
        <div class="p-4 space-y-3">
            
            <!-- Current Time & Date -->
            <div class="text-center">
                <div class="text-lg font-mono font-bold text-gray-900 dark:text-white" x-text="currentTime"></div>
                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="currentDate"></div>
            </div>
            
            <!-- Clock In/Out Status -->
            <div class="space-y-2">
                <template x-if="status.attendance">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="text-center">
                            <div class="text-gray-500 dark:text-gray-400">Clock In</div>
                            <div class="font-mono text-green-600 dark:text-green-400" 
                                 x-text="status.attendance.clock_in_time || '--:--'"></div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-500 dark:text-gray-400">Clock Out</div>
                            <div class="font-mono text-red-600 dark:text-red-400" 
                                 x-text="status.attendance.clock_out_time || '--:--'"></div>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Break Status -->
            <template x-if="status.active_break">
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded p-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-coffee text-yellow-600 text-sm"></i>
                            <span class="text-xs text-yellow-800 dark:text-yellow-200">On Break</span>
                        </div>
                        <span class="text-xs font-mono text-yellow-800 dark:text-yellow-200" 
                              x-text="formatBreakDuration()"></span>
                    </div>
                    <div class="text-xs text-yellow-600 dark:text-yellow-400 mt-1 capitalize" 
                         x-text="status.active_break.break_type + ' break'"></div>
                </div>
            </template>
            
            <!-- Action Buttons -->
            <div class="space-y-2">
                
                <!-- Clock In Button -->
                <template x-if="!status.is_clocked_in && !loading.clockIn">
                    <button @click="clockIn()" 
                            :disabled="!locationPermissionGranted"
                            class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white text-sm font-semibold py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Clock In</span>
                    </button>
                </template>
                
                <!-- Clock In Loading -->
                <template x-if="loading.clockIn">
                    <div class="w-full bg-blue-600 text-white text-sm font-semibold py-2 px-3 rounded-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Getting Location...</span>
                    </div>
                </template>
                
                <!-- Clock Out Button -->
                <template x-if="status.is_clocked_in && !status.active_break && !loading.clockOut">
                    <button @click="clockOut()" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Clock Out</span>
                    </button>
                </template>
                
                <!-- Clock Out Loading -->
                <template x-if="loading.clockOut">
                    <div class="w-full bg-red-600 text-white text-sm font-semibold py-2 px-3 rounded-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Getting Location...</span>
                    </div>
                </template>
                
                <!-- Break Controls -->
                <template x-if="status.is_clocked_in">
                    <div class="grid grid-cols-2 gap-2">
                        
                        <!-- Start Break -->
                        <template x-if="!status.active_break">
                            <div x-data="{ showBreakTypes: false }" class="relative">
                                <button @click="showBreakTypes = !showBreakTypes" 
                                        class="w-full bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold py-2 px-2 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-1">
                                    <i class="fas fa-coffee"></i>
                                    <span>Break</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                
                                <!-- Break Types Dropdown -->
                                <div x-show="showBreakTypes" 
                                     x-transition
                                     @click.away="showBreakTypes = false"
                                     class="absolute bottom-full left-0 right-0 mb-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50">
                                    <div class="p-2 space-y-1">
                                        <button @click="startBreak('regular'); showBreakTypes = false" 
                                                class="w-full text-left px-2 py-1 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <i class="fas fa-pause mr-1"></i>Regular (15min)
                                        </button>
                                        <button @click="startBreak('lunch'); showBreakTypes = false" 
                                                class="w-full text-left px-2 py-1 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <i class="fas fa-utensils mr-1"></i>Lunch (45min)
                                        </button>
                                        <button @click="startBreak('emergency'); showBreakTypes = false" 
                                                class="w-full text-left px-2 py-1 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Emergency
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <!-- End Break -->
                        <template x-if="status.active_break">
                            <button @click="endBreak()" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-2 px-2 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-1">
                                <i class="fas fa-play"></i>
                                <span>End Break</span>
                            </button>
                        </template>
                        
                        <!-- Activity Tracker -->
                        <template x-if="!status.active_break">
                            <div x-data="{ showActivity: false }" class="relative">
                                <button @click="showActivity = !showActivity" 
                                        class="w-full bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold py-2 px-2 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-1">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Activity</span>
                                </button>
                                
                                <!-- Activity Dropdown -->
                                <div x-show="showActivity" 
                                     x-transition
                                     @click.away="showActivity = false"
                                     class="absolute bottom-full right-0 left-0 mb-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50">
                                    <div class="p-2 space-y-1">
                                        <button @click="updateActivity('call'); showActivity = false" 
                                                class="w-full text-left px-2 py-1 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <i class="fas fa-phone mr-1"></i>+1 Call
                                        </button>
                                        <button @click="updateActivity('approved'); showActivity = false" 
                                                class="w-full text-left px-2 py-1 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <i class="fas fa-check mr-1"></i>+1 Approved
                                        </button>
                                        <button @click="updateActivity('rejected'); showActivity = false" 
                                                class="w-full text-left px-2 py-1 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <i class="fas fa-times mr-1"></i>+1 Rejected
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                    </div>
                </template>
            </div>
            
            <!-- Location Permission Warning -->
            <template x-if="!locationPermissionGranted && !locationPermissionDenied">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded p-2">
                    <div class="text-xs text-blue-800 dark:text-blue-200 text-center">
                        <i class="fas fa-map-marker-alt mb-1"></i>
                        <div>Location access required for attendance tracking</div>
                        <button @click="requestLocationPermission()" 
                                class="mt-1 text-blue-600 dark:text-blue-400 underline hover:no-underline">
                            Enable Location
                        </button>
                    </div>
                </div>
            </template>
            
            <!-- Location Not Requested Yet -->
            <template x-if="!locationPermissionGranted && !locationPermissionDenied">
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded p-3 space-y-2">
                    <div class="text-xs text-yellow-800 dark:text-yellow-200 text-center">
                        <i class="fas fa-map-marker-alt mb-1"></i>
                        <div>Location access needed for attendance tracking.</div>
                    </div>
                    <button @click="requestLocationPermission()" 
                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold py-2 px-3 rounded transition-colors duration-200">
                        <i class="fas fa-location-arrow mr-1"></i>
                        Allow Location Access
                    </button>
                </div>
            </template>
            
            <!-- Location Permission Denied -->
            <template x-if="locationPermissionDenied">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded p-3 space-y-2">
                    <div class="text-xs text-red-800 dark:text-red-200 text-center">
                        <i class="fas fa-exclamation-triangle mb-1"></i>
                        <div>Location access denied. Please enable location in browser settings.</div>
                    </div>
                    <div class="space-y-1">
                        <button @click="requestLocationPermission()" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-2 px-3 rounded transition-colors duration-200">
                            <i class="fas fa-location-arrow mr-1"></i>
                            Try Again
                        </button>
                        <button @click="showManualLocation = true" 
                                class="w-full bg-gray-600 hover:bg-gray-700 text-white text-xs font-semibold py-1 px-3 rounded transition-colors duration-200">
                            <i class="fas fa-edit mr-1"></i>
                            Enter Location Manually
                        </button>
                    </div>
                </div>
            </template>
            
            <!-- Manual Location Entry -->
            <template x-if="showManualLocation">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded p-3 space-y-2">
                    <div class="text-xs text-blue-800 dark:text-blue-200 text-center font-semibold">
                        Manual Location Entry
                    </div>
                    <div class="space-y-2">
                        <input type="number" 
                               x-model="manualLocation.latitude" 
                               placeholder="Latitude (e.g., 22.5726)" 
                               step="any"
                               class="w-full text-xs px-2 py-1 border border-gray-300 rounded">
                        <input type="number" 
                               x-model="manualLocation.longitude" 
                               placeholder="Longitude (e.g., 88.3639)" 
                               step="any"
                               class="w-full text-xs px-2 py-1 border border-gray-300 rounded">
                        <div class="flex space-x-1">
                            <button @click="useManualLocation()" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2 px-2 rounded transition-colors duration-200">
                                Use Location
                            </button>
                            <button @click="showManualLocation = false" 
                                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-semibold py-2 px-2 rounded transition-colors duration-200">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </template>
            
        </div>
        
        <!-- Today's Summary (collapsed by default) -->
        <div x-data="{ showSummary: false }" class="border-t border-gray-200 dark:border-gray-700">
            <button @click="showSummary = !showSummary" 
                    class="w-full px-4 py-2 text-left text-xs text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center justify-between">
                <span>Today's Summary</span>
                <i class="fas fa-chevron-down transition-transform duration-200" 
                   :class="showSummary ? 'rotate-180' : ''"></i>
            </button>
            
            <div x-show="showSummary" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 max-h-0"
                 x-transition:enter-end="opacity-100 max-h-96"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 max-h-96"
                 x-transition:leave-end="opacity-0 max-h-0"
                 class="overflow-hidden">
                <div class="px-4 pb-4 space-y-2">
                    
                    <!-- Working Hours -->
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600 dark:text-gray-400">Working Hours:</span>
                        <span class="font-mono text-gray-900 dark:text-white" x-text="calculateWorkingHours()"></span>
                    </div>
                    
                    <!-- Break Time -->
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600 dark:text-gray-400">Break Time:</span>
                        <span class="font-mono text-gray-900 dark:text-white" x-text="formatMinutes(status.total_break_time || 0)"></span>
                    </div>
                    
                    <!-- Activity Summary -->
                    <template x-if="status.activity">
                        <div class="space-y-1">
                            <div class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Today's Activity:</div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Calls:</span>
                                    <span class="text-gray-900 dark:text-white" x-text="status.activity.calls_made || 0"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Approved:</span>
                                    <span class="text-green-600 dark:text-green-400" x-text="status.activity.leads_approved || 0"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Transfers:</span>
                                    <span class="text-blue-600 dark:text-blue-400" x-text="status.activity.leads_transferred || 0"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Rejected:</span>
                                    <span class="text-red-600 dark:text-red-400" x-text="status.activity.leads_rejected || 0"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function attendanceWidget() {
    return {
        status: {
            is_clocked_in: false,
            attendance: null,
            active_break: null,
            activity: null,
            total_break_time: 0
        },
        officeConfig: null,
        currentTime: '',
        currentDate: '',
        locationPermissionGranted: false,
        locationPermissionDenied: false,
        showManualLocation: false,
        manualLocation: {
            latitude: '',
            longitude: ''
        },
        loading: {
            clockIn: false,
            clockOut: false,
            break: false
        },
        
        init() {
            console.log('Attendance widget initializing...');
            this.updateDateTime();
            this.loadOfficeConfig();
            this.loadAttendanceStatus();
            this.checkLocationPermission();
            
            // Update time every second
            setInterval(() => {
                this.updateDateTime();
            }, 1000);
            
            // Refresh status every 30 seconds
            setInterval(() => {
                this.loadAttendanceStatus();
            }, 30000);
            
            console.log('Attendance widget initialized');
        },
        
        updateDateTime() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('en-IN', { 
                timeZone: 'Asia/Kolkata',
                hour12: true,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            this.currentDate = now.toLocaleDateString('en-IN', {
                timeZone: 'Asia/Kolkata',
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },
        
        async loadOfficeConfig() {
            try {
                const response = await fetch('/agent/attendance/office-location');
                if (response.ok) {
                    this.officeConfig = await response.json();
                    console.log('Loaded office config:', this.officeConfig);
                }
            } catch (error) {
                console.error('Failed to load office config:', error);
            }
        },
        
        async loadAttendanceStatus() {
            try {
                const response = await fetch('/agent/attendance/status');
                if (response.ok) {
                    const data = await response.json();
                    this.status = {
                        is_clocked_in: data.attendance?.clock_in_time && !data.attendance?.clock_out_time,
                        attendance: data.attendance,
                        active_break: data.active_break,
                        activity: data.activity,
                        total_break_time: data.total_break_time || 0
                    };
                }
            } catch (error) {
                console.error('Failed to load attendance status:', error);
            }
        },
        
        async checkLocationPermission() {
            console.log('Checking location permission...');
            
            if (!('geolocation' in navigator)) {
                console.error('Geolocation not supported');
                this.locationPermissionDenied = true;
                this.showNotification('Geolocation is not supported by this browser', 'error');
                return;
            }
            
            try {
                if ('permissions' in navigator) {
                    const permission = await navigator.permissions.query({name: 'geolocation'});
                    console.log('Permission state:', permission.state);
                    
                    this.locationPermissionGranted = permission.state === 'granted';
                    this.locationPermissionDenied = permission.state === 'denied';
                    
                    permission.addEventListener('change', () => {
                        console.log('Permission changed to:', permission.state);
                        this.locationPermissionGranted = permission.state === 'granted';
                        this.locationPermissionDenied = permission.state === 'denied';
                    });
                } else {
                    console.log('Permissions API not supported, will request permission when needed');
                    // Fallback for browsers that don't support permissions API
                    this.requestLocationPermission();
                }
            } catch (error) {
                console.error('Error checking location permission:', error);
                // Fallback for browsers that don't support permissions API
                this.requestLocationPermission();
            }
        },
        
        async requestLocationPermission() {
            if ('geolocation' in navigator) {
                try {
                    console.log('Requesting location permission...');
                    await new Promise((resolve, reject) => {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                console.log('Location permission granted:', position.coords);
                                resolve(position);
                            },
                            (error) => {
                                console.error('Location permission error:', error);
                                reject(error);
                            },
                            {
                                timeout: 10000,
                                enableHighAccuracy: false,
                                maximumAge: 300000
                            }
                        );
                    });
                    this.locationPermissionGranted = true;
                    this.locationPermissionDenied = false;
                    this.showNotification('Location access granted!', 'success');
                } catch (error) {
                    console.error('Location permission denied:', error);
                    this.locationPermissionGranted = false;
                    this.locationPermissionDenied = true;
                    this.showNotification('Location access denied. Please enable location in your browser settings.', 'error');
                }
            } else {
                this.showNotification('Geolocation is not supported by this browser.', 'error');
            }
        },
        
        async getCurrentPosition() {
            return new Promise((resolve, reject) => {
                if (!('geolocation' in navigator)) {
                    reject(new Error('Geolocation not supported by this browser'));
                    return;
                }
                
                const options = this.officeConfig?.geolocation_config || {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 60000
                };
                
                console.log('Requesting location with options:', options);
                
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        console.log('Location received:', position.coords);
                        resolve(position);
                    },
                    (error) => {
                        console.error('Geolocation error:', error);
                        let message = 'Failed to get location. ';
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                message += 'Location access denied. Please enable location permissions.';
                                this.locationPermissionDenied = true;
                                this.locationPermissionGranted = false;
                                break;
                            case error.POSITION_UNAVAILABLE:
                                message += 'Location information unavailable.';
                                break;
                            case error.TIMEOUT:
                                message += 'Location request timed out. Please try again.';
                                break;
                            default:
                                message += 'Unknown error occurred.';
                                break;
                        }
                        
                        reject(new Error(message));
                    },
                    options
                );
            });
        },
        
        async clockIn() {
            if (!this.locationPermissionGranted) {
                this.showNotification('Location permission required for clock-in. Please allow location access.', 'error');
                await this.requestLocationPermission();
                if (!this.locationPermissionGranted) {
                    return;
                }
            }
            
            this.loading.clockIn = true;
            
            try {
                this.showNotification('Getting your location...', 'info');
                const position = await this.getCurrentPosition();
                
                this.showNotification('Verifying location...', 'info');
                const response = await fetch('/agent/attendance/clock-in', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy
                    })
                });
                
                const data = await response.json();
                console.log('Clock-in response:', data);
                
                if (response.ok && data.success) {
                    this.showNotification(data.message || 'Successfully clocked in!', 'success');
                    await this.loadAttendanceStatus();
                } else {
                    const errorMessage = data.message || `Server error: ${response.status} ${response.statusText}`;
                    this.showNotification(errorMessage, 'error');
                    console.error('Clock-in failed:', data);
                }
            } catch (error) {
                console.error('Clock in error:', error);
                this.showNotification(error.message || 'Failed to get location. Please try again.', 'error');
            } finally {
                this.loading.clockIn = false;
            }
        },
        
        async clockOut() {
            this.loading.clockOut = true;
            
            try {
                const position = await this.getCurrentPosition();
                const response = await fetch('/agent/attendance/clock-out', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    })
                });
                
                const data = await response.json();
                console.log('Clock-out response:', data);
                
                if (response.ok && data.success) {
                    this.showNotification(data.message || 'Successfully clocked out!', 'success');
                    await this.loadAttendanceStatus();
                } else {
                    const errorMessage = data.message || `Server error: ${response.status} ${response.statusText}`;
                    this.showNotification(errorMessage, 'error');
                    console.error('Clock-out failed:', data);
                }
            } catch (error) {
                console.error('Clock out error:', error);
                this.showNotification('Failed to get location. Please try again.', 'error');
            } finally {
                this.loading.clockOut = false;
            }
        },
        
        async startBreak(breakType) {
            try {
                const response = await fetch('/agent/attendance/break/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        break_type: breakType
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.loadAttendanceStatus();
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Start break error:', error);
                this.showNotification('Failed to start break', 'error');
            }
        },
        
        async endBreak() {
            try {
                const response = await fetch('/agent/attendance/break/end', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message + ` (${data.duration_minutes} minutes)`, 'success');
                    this.loadAttendanceStatus();
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('End break error:', error);
                this.showNotification('Failed to end break', 'error');
            }
        },
        
        async updateActivity(type) {
            try {
                const response = await fetch('/agent/attendance/activity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        type: type
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.loadAttendanceStatus();
                    this.showNotification(`${type.charAt(0).toUpperCase() + type.slice(1)} recorded`, 'success');
                } else {
                    this.showNotification('Failed to update activity', 'error');
                }
            } catch (error) {
                console.error('Update activity error:', error);
                this.showNotification('Failed to update activity', 'error');
            }
        },
        
        formatBreakDuration() {
            if (!this.status.active_break?.start_time) return '00:00';
            
            const startTime = new Date(`2000-01-01 ${this.status.active_break.start_time}`);
            const now = new Date();
            const currentTime = new Date(`2000-01-01 ${now.toTimeString().substring(0, 8)}`);
            
            const diffMs = currentTime - startTime;
            const diffMins = Math.floor(diffMs / 60000);
            
            const hours = Math.floor(diffMins / 60);
            const minutes = diffMins % 60;
            
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        },
        
        calculateWorkingHours() {
            if (!this.status.attendance?.clock_in_time) return '00:00';
            
            const clockIn = new Date(`2000-01-01 ${this.status.attendance.clock_in_time}`);
            let clockOut;
            
            if (this.status.attendance.clock_out_time) {
                clockOut = new Date(`2000-01-01 ${this.status.attendance.clock_out_time}`);
            } else {
                const now = new Date();
                clockOut = new Date(`2000-01-01 ${now.toTimeString().substring(0, 8)}`);
            }
            
            // Handle overnight shifts
            if (clockOut < clockIn) {
                clockOut.setDate(clockOut.getDate() + 1);
            }
            
            const diffMs = clockOut - clockIn;
            const diffMins = Math.floor(diffMs / 60000) - (this.status.total_break_time || 0);
            
            const hours = Math.floor(diffMins / 60);
            const minutes = diffMins % 60;
            
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        },
        
        formatMinutes(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
        },
        
        showNotification(message, type = 'info') {
            // Create notification element
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
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        },
        
        useManualLocation() {
            if (!this.manualLocation.latitude || !this.manualLocation.longitude) {
                this.showNotification('Please enter both latitude and longitude', 'error');
                return;
            }
            
            const lat = parseFloat(this.manualLocation.latitude);
            const lng = parseFloat(this.manualLocation.longitude);
            
            if (isNaN(lat) || isNaN(lng)) {
                this.showNotification('Please enter valid numbers for location', 'error');
                return;
            }
            
            if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                this.showNotification('Please enter valid latitude (-90 to 90) and longitude (-180 to 180)', 'error');
                return;
            }
            
            this.showManualLocation = false;
            this.manualClockIn(lat, lng);
        },
        
        async manualClockIn(latitude, longitude) {
            this.loading.clockIn = true;
            
            try {
                this.showNotification('Verifying manual location...', 'info');
                const response = await fetch('/agent/attendance/clock-in', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: latitude,
                        longitude: longitude,
                        accuracy: 50, // Manual entry, assume 50m accuracy
                        manual: true
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.loadAttendanceStatus();
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Manual clock in error:', error);
                this.showNotification('Failed to clock in with manual location. Please try again.', 'error');
            } finally {
                this.loading.clockIn = false;
            }
        }
    }
}
</script>

<style>
.attendance-widget {
    /* Custom styles for the attendance widget */
}

.attendance-widget .notification-dot {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>

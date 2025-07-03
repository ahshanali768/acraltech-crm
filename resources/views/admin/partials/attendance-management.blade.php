{{-- 
    Settings are now passed in from the route to avoid database errors 
    The $attendanceSettings and $allowedLocations variables are expected to be set
--}}

<!-- Attendance Settings Form -->
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Attendance & Agent Settings</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure shift timings, notifications, and attendance rules</p>
        </div>
        
        <form action="{{ route('admin.settings.attendance') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Shift Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="login_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Shift Login Time
                    </label>
                    <input type="time" 
                           id="login_time" 
                           name="login_time" 
                           value="{{ $attendanceSettings['login_time'] }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           required>
                </div>
                
                <div>
                    <label for="logout_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Shift Logout Time
                    </label>
                    <input type="time" 
                           id="logout_time" 
                           name="logout_time" 
                           value="{{ $attendanceSettings['logout_time'] }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           required>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Notification Settings</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="email_notifications" 
                               name="email_notifications" 
                               value="1"
                               {{ $attendanceSettings['email_notifications'] ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="email_notifications" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Email Notifications
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="bell_notifications" 
                               name="bell_notifications" 
                               value="1"
                               {{ $attendanceSettings['bell_notifications'] ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="bell_notifications" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Bell Notifications
                        </label>
                    </div>
                </div>
            </div>

            <!-- Attendance Rules -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="grace_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Grace Period (minutes)
                    </label>
                    <input type="number" 
                           id="grace_period" 
                           name="grace_period" 
                           value="{{ $attendanceSettings['grace_period'] }}"
                           min="0" max="60"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           required>
                </div>
                
                <div>
                    <label for="late_fee_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Late Fee Amount (₹)
                    </label>
                    <input type="number" 
                           id="late_fee_amount" 
                           name="late_fee_amount" 
                           value="{{ $attendanceSettings['late_fee_amount'] }}"
                           min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           required>
                </div>
                
                <div>
                    <label for="auto_absent_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Auto-Absent After (hours)
                    </label>
                    <input type="number" 
                           id="auto_absent_threshold" 
                           name="auto_absent_threshold" 
                           value="{{ $attendanceSettings['auto_absent_threshold'] }}"
                           min="1" max="24"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           required>
                </div>
            </div>

            <!-- Half-Day Settings -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="half_day_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Half-Day Threshold (hours)
                    </label>
                    <input type="number" 
                           id="half_day_threshold" 
                           name="half_day_threshold" 
                           value="{{ $attendanceSettings['half_day_threshold'] }}"
                           min="1" max="12" step="0.5"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           required>
                </div>
                
                <div>
                    <label for="half_day_deduction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Half-Day Deduction (₹)
                    </label>
                    <input type="number" 
                           id="half_day_deduction" 
                           name="half_day_deduction" 
                           value="{{ $attendanceSettings['half_day_deduction'] }}"
                           min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           required>
                </div>
                
                <div>
                    <label for="monthly_absence_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Monthly Absence Limit
                    </label>
                    <input type="number" 
                           id="monthly_absence_limit" 
                           name="monthly_absence_limit" 
                           value="{{ $attendanceSettings['monthly_absence_limit'] }}"
                           min="0" max="31"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           required>
                </div>
            </div>

            <div>
                <label for="absence_penalty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Absence Penalty Amount (₹)
                </label>
                <input type="number" 
                       id="absence_penalty" 
                       name="absence_penalty" 
                       value="{{ $attendanceSettings['absence_penalty'] }}"
                       min="0" step="0.01"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                       required>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Attendance Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Allowed Locations Management -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Allowed Locations</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage locations where agents can check in/out</p>
        </div>
        
        <div class="p-6">
            <!-- Add New Location Form -->
            <form id="addLocationForm" action="{{ route('admin.settings.add_location') }}" method="POST" class="mb-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="location_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Location Name
                        </label>
                        <input type="text" 
                               id="location_name" 
                               name="location_name" 
                               placeholder="Office, Home, etc."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white h-[42px]"
                               required>
                        <p class="text-xs text-transparent mt-1 min-h-[16px]">.</p>
                    </div>
                    
                    <div>
                        <label for="radius_meters" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Radius (meters)
                        </label>
                        <input type="number" 
                               id="radius_meters" 
                               name="radius_meters" 
                               value="100"
                               min="10" max="1000"
                               placeholder="100"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white h-[42px]"
                               required>
                        <p class="text-xs text-gray-500 mt-1 min-h-[16px]">10-1000 meters</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Capture GPS
                        </label>
                        <button type="button" 
                                id="getCurrentLocation"
                                class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors h-[42px]">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Get Location
                        </button>
                        <div id="locationStatus" class="text-xs mt-1 text-gray-500 min-h-[16px]"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Add Location
                        </label>
                        <button type="submit" 
                                id="addLocationBtn"
                                disabled
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition-colors h-[42px]">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Location
                        </button>
                        <p class="text-xs text-transparent mt-1 min-h-[16px]">.</p>
                    </div>
                </div>
                
                <!-- Hidden fields for coordinates and address -->
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
                <input type="hidden" id="address" name="address">
                
                <!-- Required fields for the controller -->
                <input type="hidden" name="location_type" value="geo">
                <input type="hidden" id="location_value" name="location_value">
                
                <!-- Location Preview -->
                <div id="locationPreview" class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 hidden">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900 dark:text-blue-100">Location Captured</h4>
                            <p id="previewAddress" class="text-sm text-blue-700 dark:text-blue-300"></p>
                            <p id="previewCoords" class="text-xs text-blue-600 dark:text-blue-400"></p>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Existing Locations List -->
            <div class="space-y-3">
                @if ($allowedLocations->count() > 0)
                    @foreach ($allowedLocations as $location)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $location->name }}</h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $location->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $location->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $location->address ?? 'No address available' }}</p>
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500 dark:text-gray-500">
                                    @if($location->latitude && $location->longitude)
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ number_format($location->latitude, 6) }}, {{ number_format($location->longitude, 6) }}
                                        </span>
                                    @endif
                                    @if($location->radius_meters)
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m-6 3l6-3"></path>
                                            </svg>
                                            {{ $location->radius_meters }}m radius
                                        </span>
                                    @endif
                                    <span>Added: {{ $location->created_at ? $location->created_at->format('M j, Y') : 'Unknown' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <!-- Toggle Active Status -->
                                <form action="{{ route('admin.settings.toggle_location', $location->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            title="{{ $location->is_active ? 'Deactivate' : 'Activate' }} location"
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium transition-colors {{ $location->is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200' }}">
                                        @if($location->is_active)
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                            Deactivate
                                        @else
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Activate
                                        @endif
                                    </button>
                                </form>
                                
                                <!-- Delete Location -->
                                <form action="{{ route('admin.settings.delete_location', $location->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to delete this location? Agents will no longer be able to check in from here.')"
                                            title="Delete location"
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p>No allowed locations configured</p>
                        <p class="text-sm">Add locations where agents can check in/out</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Access to Attendance Tracking -->
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Attendance Tracking Dashboard</h3>
                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">View detailed attendance reports and manage agent check-ins</p>
            </div>
            <a href="{{ route('admin.attendance.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                </svg>
                Open Attendance Dashboard
            </a>
        </div>
    </div>
</div>

<!-- GPS Location functionality is now handled by the main settings page via AJAX -->
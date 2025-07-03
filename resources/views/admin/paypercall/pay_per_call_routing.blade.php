@extends('layouts.pay_per_call')

@section('title', 'Call Routing')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Call Routing</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Configure how incoming calls are routed to different agents and destinations</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Route
                </button>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Routing Rules -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Global Routing Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Global Routing Settings</h3>
                <form method="POST" action="{{ route('admin.pay-per-call.update-routing') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="default_routing" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Default Routing Method
                            </label>
                            <select id="default_routing" name="default_routing" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="round_robin">Round Robin</option>
                                <option value="priority">Priority Based</option>
                                <option value="availability">Availability Based</option>
                                <option value="load_balancing">Load Balancing</option>
                            </select>
                        </div>
                        <div>
                            <label for="ring_timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ring Timeout (seconds)
                            </label>
                            <input type="number" id="ring_timeout" name="ring_timeout" value="30" min="10" max="120" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="max_queue_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Max Queue Time (minutes)
                            </label>
                            <input type="number" id="max_queue_time" name="max_queue_time" value="5" min="1" max="30" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="voicemail_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Voicemail Fallback
                            </label>
                            <select id="voicemail_enabled" name="voicemail_enabled" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="enabled">Enabled</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Campaign-Specific Routing -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Campaign-Specific Routing</h3>
                <div class="space-y-4">
                    <!-- Campaign Route 1 -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Real Estate Campaign</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">DID: +1-555-0123</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-medium rounded">Active</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Routing Method:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">Priority Based</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Primary Agent:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">John Smith</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Backup Agent:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">Sarah Johnson</span>
                            </div>
                        </div>
                    </div>

                    <!-- Campaign Route 2 -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Insurance Campaign</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">DID: +1-555-0124</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-medium rounded">Active</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Routing Method:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">Round Robin</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Agent Pool:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">3 Agents</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Queue Limit:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">5 calls</span>
                            </div>
                        </div>
                    </div>

                    <!-- Campaign Route 3 -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Healthcare Campaign</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">DID: +1-555-0125</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 text-xs font-medium rounded">Paused</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Routing Method:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">Availability Based</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Primary Agent:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">Mike Wilson</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                <span class="ml-1 text-yellow-600 dark:text-yellow-400">Under Maintenance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time-Based Routing -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Time-Based Routing</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="time_routing_enabled" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Enable Time-Based Routing
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Route calls differently based on time of day</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="time_routing_enabled" name="time_routing_enabled" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="business_hours_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Business Hours Start
                            </label>
                            <input type="time" id="business_hours_start" name="business_hours_start" value="09:00" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="business_hours_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Business Hours End
                            </label>
                            <input type="time" id="business_hours_end" name="business_hours_end" value="17:00" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="after_hours_action" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                After Hours Action
                            </label>
                            <select id="after_hours_action" name="after_hours_action" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="voicemail">Voicemail</option>
                                <option value="forward">Forward to Mobile</option>
                                <option value="queue">Add to Queue</option>
                                <option value="disconnect">Disconnect</option>
                            </select>
                        </div>
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Timezone
                            </label>
                            <select id="timezone" name="timezone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="Asia/Kolkata">Asia/Kolkata (IST)</option>
                                <option value="America/New_York">America/New_York (EST)</option>
                                <option value="America/Los_Angeles">America/Los_Angeles (PST)</option>
                                <option value="Europe/London">Europe/London (GMT)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Current Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Current Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Active Routes</span>
                        <span class="text-sm font-medium text-green-600 dark:text-green-400">2</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Paused Routes</span>
                        <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400">1</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Available Agents</span>
                        <span class="text-sm font-medium text-green-600 dark:text-green-400">5</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Queue Length</span>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">3</span>
                    </div>
                </div>
            </div>

            <!-- Agent Availability -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Agent Availability</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">John Smith</span>
                        </div>
                        <span class="text-xs text-green-600 dark:text-green-400">Available</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Sarah Johnson</span>
                        </div>
                        <span class="text-xs text-green-600 dark:text-green-400">Available</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Mike Wilson</span>
                        </div>
                        <span class="text-xs text-red-600 dark:text-red-400">On Call</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Emily Davis</span>
                        </div>
                        <span class="text-xs text-yellow-600 dark:text-yellow-400">Away</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">David Brown</span>
                        </div>
                        <span class="text-xs text-green-600 dark:text-green-400">Available</span>
                    </div>
                </div>
            </div>

            <!-- Recent Call Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                            <span>Call routed to John Smith</span>
                        </div>
                        <span class="ml-3.5">2 minutes ago</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                            <span>Route configuration updated</span>
                        </div>
                        <span class="ml-3.5">15 minutes ago</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></div>
                            <span>Healthcare route paused</span>
                        </div>
                        <span class="ml-3.5">1 hour ago</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>Call failed to route</span>
                        </div>
                        <span class="ml-3.5">2 hours ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

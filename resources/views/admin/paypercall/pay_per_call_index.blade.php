@extends('layouts.pay_per_call')

@section('title', 'Pay Per Call Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pay Per Call Settings</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Configure your pay-per-call system settings</p>
            </div>
            <div class="flex items-center space-x-3">
                <button id="test-provider" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plug mr-2"></i>Test Provider
                </button>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Provider Configuration -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Provider Configuration</h3>
                <form method="POST" action="{{ route('admin.pay-per-call.update-settings') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="provider_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Provider Name
                            </label>
                            <select id="provider_name" name="provider_name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="twilio">Twilio</option>
                                <option value="nexmo">Nexmo/Vonage</option>
                                <option value="plivo">Plivo</option>
                                <option value="ringcentral">RingCentral</option>
                            </select>
                        </div>
                        <div>
                            <label for="provider_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <select id="provider_status" name="provider_status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="testing">Testing</option>
                            </select>
                        </div>
                        <div>
                            <label for="api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                API Key
                            </label>
                            <input type="password" id="api_key" name="api_key" placeholder="Enter API Key" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="api_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                API Secret
                            </label>
                            <input type="password" id="api_secret" name="api_secret" placeholder="Enter API Secret" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label for="webhook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Webhook URL
                            </label>
                            <input type="url" id="webhook_url" name="webhook_url" placeholder="https://your-domain.com/webhook" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                </form>
            </div>

            <!-- DNI Configuration -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dynamic Number Insertion (DNI)</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="dni_enabled" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Enable DNI
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Automatically replace phone numbers on websites</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="dni_enabled" name="dni_enabled" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="dni_pool_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Number Pool Size
                            </label>
                            <input type="number" id="dni_pool_size" name="dni_pool_size" value="100" min="10" max="1000" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="dni_session_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Session Duration (minutes)
                            </label>
                            <input type="number" id="dni_session_duration" name="dni_session_duration" value="30" min="5" max="180" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-code mr-2"></i>Generate DNI Script
                        </button>
                    </div>
                </div>
            </div>

            <!-- Call Recording -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Call Recording Settings</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="recording_enabled" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Enable Call Recording
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Record all incoming calls for quality assurance</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="recording_enabled" name="recording_enabled" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="recording_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Recording Format
                            </label>
                            <select id="recording_format" name="recording_format" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="mp3">MP3</option>
                                <option value="wav">WAV</option>
                                <option value="ogg">OGG</option>
                            </select>
                        </div>
                        <div>
                            <label for="recording_retention" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Retention Period (days)
                            </label>
                            <input type="number" id="recording_retention" name="recording_retention" value="90" min="30" max="365" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- System Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">System Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Provider Connection</span>
                        <span class="flex items-center text-green-600 dark:text-green-400">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            Online
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">DNI Service</span>
                        <span class="flex items-center text-green-600 dark:text-green-400">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            Active
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Call Recording</span>
                        <span class="flex items-center text-green-600 dark:text-green-400">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            Enabled
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Webhook Status</span>
                        <span class="flex items-center text-yellow-600 dark:text-yellow-400">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                            Pending
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Stats</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Active Campaigns</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">8</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total DIDs</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">25</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Monthly Calls</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">1,247</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Success Rate</span>
                        <span class="text-sm font-medium text-green-600 dark:text-green-400">94%</span>
                    </div>
                </div>
            </div>

            <!-- Recent Logs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                            <span>Provider connection tested</span>
                        </div>
                        <span class="ml-3.5">2 minutes ago</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                            <span>DNI script updated</span>
                        </div>
                        <span class="ml-3.5">15 minutes ago</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></div>
                            <span>Webhook configuration changed</span>
                        </div>
                        <span class="ml-3.5">1 hour ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Test Provider Button
    document.getElementById('test-provider').addEventListener('click', function() {
        const button = this;
        const originalText = button.innerHTML;
        
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Testing...';
        button.disabled = true;
        
        fetch('{{ route("admin.pay-per-call.test-provider") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Success!';
                button.className = 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors';
            } else {
                button.innerHTML = '<i class="fas fa-times mr-2"></i>Failed';
                button.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors';
            }
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.className = 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors';
                button.disabled = false;
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = '<i class="fas fa-times mr-2"></i>Error';
            button.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.className = 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors';
                button.disabled = false;
            }, 3000);
        });
    });
});
</script>
@endpush

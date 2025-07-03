@extends('layouts.pay_per_call')

@section('title', 'Add New DID')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New DID</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Configure a new Direct Inward Dialing number</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.dids.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to DIDs
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('admin.dids.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- DID Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">DID Information</h3>
                    
                    <div>
                        <label for="number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Phone Number *
                        </label>
                        <input type="tel" id="number" name="number" required placeholder="+1-555-123-4567" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Provider
                        </label>
                        <select id="provider" name="provider" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="twilio">Twilio</option>
                            <option value="nexmo">Nexmo/Vonage</option>
                            <option value="plivo">Plivo</option>
                            <option value="ringcentral">RingCentral</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Country
                        </label>
                        <select id="country" name="country" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="GB">United Kingdom</option>
                            <option value="AU">Australia</option>
                            <option value="IN">India</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="area_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Area Code
                        </label>
                        <input type="text" id="area_code" name="area_code" placeholder="555" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>
                
                <!-- Configuration -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Configuration</h3>
                    
                    <div>
                        <label for="campaign_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Assign to Campaign
                        </label>
                        <select id="campaign_id" name="campaign_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select Campaign (Optional)</option>
                            <option value="1">Real Estate Campaign</option>
                            <option value="2">Insurance Campaign</option>
                            <option value="3">Healthcare Campaign</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="monthly_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Monthly Cost (₹)
                        </label>
                        <input type="number" id="monthly_cost" name="monthly_cost" step="0.01" placeholder="0.00" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status
                        </label>
                        <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="available">Available</option>
                            <option value="assigned">Assigned</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Optional notes about this DID..." 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Call Routing Settings -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Call Routing Settings</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="forward_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Forward Calls To
                        </label>
                        <input type="tel" id="forward_to" name="forward_to" placeholder="+1-555-987-6543" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="ring_timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ring Timeout (seconds)
                        </label>
                        <input type="number" id="ring_timeout" name="ring_timeout" value="30" min="10" max="120" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    
                    <div class="lg:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="enable_recording" name="enable_recording" value="1" 
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="enable_recording" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Enable call recording for this DID
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.dids.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create DID
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

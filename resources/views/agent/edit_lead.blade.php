@extends('layouts.agent')

@section('title', 'Edit Lead')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Lead</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Update lead information for {{ $lead->first_name }} {{ $lead->last_name }}</p>
        </div>
        <a href="{{ route('agent.leads.show', $lead) }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Details
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Lead Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
        <div class="p-8">
            <form action="{{ route('agent.leads.update', $lead) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Active Campaigns & DIDs Display -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bullhorn text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                Active Campaigns & DID Numbers
                            </h3>
                            <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">
                                Reference the available campaigns and their associated DID numbers
                            </p>
                        </div>
                    </div>
                    
                    @if($activeCampaigns->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
                            @foreach($activeCampaigns as $campaign)
                                <div class="bg-white dark:bg-blue-800/50 border border-blue-200 dark:border-blue-700 rounded-lg p-3 flex items-center">
                                    <i class="fas fa-phone text-blue-500 text-sm mr-2"></i>
                                    <div class="font-medium text-gray-900 dark:text-white text-sm">
                                        DID: {{ $campaign->did }} ({{ $campaign->campaign_name }})
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-2"></i>
                            <p class="text-yellow-600 dark:text-yellow-400 text-sm">
                                No active campaigns available at the moment.
                            </p>
                        </div>
                    @endif
                </div>
                
                <!-- Personal Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-user text-blue-500 mr-3"></i>Personal Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $lead->first_name) }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $lead->last_name) }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $lead->phone) }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                placeholder="(555) 123-4567">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $lead->email) }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                placeholder="john@example.com">
                        </div>
                    </div>
                </div>

                <!-- Lead Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-chart-line text-purple-500 mr-3"></i>Lead Information
                    </h2>
                    <div class="space-y-6">
                        <!-- Row 1: DID Number and Campaign Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="did_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    DID Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="did_number" name="did_number" value="{{ old('did_number', $lead->did_number) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Enter DID number">
                            </div>
                            <div>
                                <label for="campaign_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Campaign Name
                                </label>
                                <input type="text" id="campaign_name" name="campaign_name" 
                                    value="{{ old('campaign_name', $lead->campaign->campaign_name ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:text-white transition-all duration-200 bg-gray-100"
                                    placeholder="Auto-filled when DID is entered"
                                    readonly>
                                <input type="hidden" id="campaign_id" name="campaign_id" value="{{ old('campaign_id', $lead->campaign_id) }}">
                            </div>
                        </div>
                        
                        <!-- Row 2: Agent Name and Verifier Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <label for="agent_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Agent Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="agent_name" name="agent_name" value="{{ old('agent_name', $lead->agent_name) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Enter agent username"
                                    autocomplete="off">
                                <div id="agent_suggestions" class="absolute z-10 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg hidden max-h-40 overflow-y-auto"></div>
                            </div>
                            <div class="relative">
                                <label for="verifier_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Verifier Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="verifier_name" name="verifier_name" value="{{ old('verifier_name', $lead->verifier_name) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Enter verifier username"
                                    autocomplete="off">
                                <div id="verifier_suggestions" class="absolute z-10 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg hidden max-h-40 overflow-y-auto"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-green-500 mr-3"></i>Address Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Street Address
                            </label>
                            <input type="text" id="address" name="address" value="{{ old('address', $lead->address) }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                placeholder="123 Main Street">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                City
                            </label>
                            <input type="text" id="city" name="city" value="{{ old('city', $lead->city) }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                placeholder="New York">
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                State
                            </label>
                            <select id="state" name="state"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                                <option value="">Select State</option>
                                <option value="AL" {{ old('state', $lead->state) == 'AL' ? 'selected' : '' }}>Alabama</option>
                                <option value="AK" {{ old('state', $lead->state) == 'AK' ? 'selected' : '' }}>Alaska</option>
                                <option value="AZ" {{ old('state', $lead->state) == 'AZ' ? 'selected' : '' }}>Arizona</option>
                                <option value="AR" {{ old('state', $lead->state) == 'AR' ? 'selected' : '' }}>Arkansas</option>
                                <option value="CA" {{ old('state', $lead->state) == 'CA' ? 'selected' : '' }}>California</option>
                                <option value="CO" {{ old('state', $lead->state) == 'CO' ? 'selected' : '' }}>Colorado</option>
                                <option value="CT" {{ old('state', $lead->state) == 'CT' ? 'selected' : '' }}>Connecticut</option>
                                <option value="DE" {{ old('state', $lead->state) == 'DE' ? 'selected' : '' }}>Delaware</option>
                                <option value="FL" {{ old('state', $lead->state) == 'FL' ? 'selected' : '' }}>Florida</option>
                                <option value="GA" {{ old('state', $lead->state) == 'GA' ? 'selected' : '' }}>Georgia</option>
                                <option value="HI" {{ old('state', $lead->state) == 'HI' ? 'selected' : '' }}>Hawaii</option>
                                <option value="ID" {{ old('state', $lead->state) == 'ID' ? 'selected' : '' }}>Idaho</option>
                                <option value="IL" {{ old('state', $lead->state) == 'IL' ? 'selected' : '' }}>Illinois</option>
                                <option value="IN" {{ old('state', $lead->state) == 'IN' ? 'selected' : '' }}>Indiana</option>
                                <option value="IA" {{ old('state', $lead->state) == 'IA' ? 'selected' : '' }}>Iowa</option>
                                <option value="KS" {{ old('state', $lead->state) == 'KS' ? 'selected' : '' }}>Kansas</option>
                                <option value="KY" {{ old('state', $lead->state) == 'KY' ? 'selected' : '' }}>Kentucky</option>
                                <option value="LA" {{ old('state', $lead->state) == 'LA' ? 'selected' : '' }}>Louisiana</option>
                                <option value="ME" {{ old('state', $lead->state) == 'ME' ? 'selected' : '' }}>Maine</option>
                                <option value="MD" {{ old('state', $lead->state) == 'MD' ? 'selected' : '' }}>Maryland</option>
                                <option value="MA" {{ old('state', $lead->state) == 'MA' ? 'selected' : '' }}>Massachusetts</option>
                                <option value="MI" {{ old('state', $lead->state) == 'MI' ? 'selected' : '' }}>Michigan</option>
                                <option value="MN" {{ old('state', $lead->state) == 'MN' ? 'selected' : '' }}>Minnesota</option>
                                <option value="MS" {{ old('state', $lead->state) == 'MS' ? 'selected' : '' }}>Mississippi</option>
                                <option value="MO" {{ old('state', $lead->state) == 'MO' ? 'selected' : '' }}>Missouri</option>
                                <option value="MT" {{ old('state', $lead->state) == 'MT' ? 'selected' : '' }}>Montana</option>
                                <option value="NE" {{ old('state', $lead->state) == 'NE' ? 'selected' : '' }}>Nebraska</option>
                                <option value="NV" {{ old('state', $lead->state) == 'NV' ? 'selected' : '' }}>Nevada</option>
                                <option value="NH" {{ old('state', $lead->state) == 'NH' ? 'selected' : '' }}>New Hampshire</option>
                                <option value="NJ" {{ old('state', $lead->state) == 'NJ' ? 'selected' : '' }}>New Jersey</option>
                                <option value="NM" {{ old('state', $lead->state) == 'NM' ? 'selected' : '' }}>New Mexico</option>
                                <option value="NY" {{ old('state', $lead->state) == 'NY' ? 'selected' : '' }}>New York</option>
                                <option value="NC" {{ old('state', $lead->state) == 'NC' ? 'selected' : '' }}>North Carolina</option>
                                <option value="ND" {{ old('state', $lead->state) == 'ND' ? 'selected' : '' }}>North Dakota</option>
                                <option value="OH" {{ old('state', $lead->state) == 'OH' ? 'selected' : '' }}>Ohio</option>
                                <option value="OK" {{ old('state', $lead->state) == 'OK' ? 'selected' : '' }}>Oklahoma</option>
                                <option value="OR" {{ old('state', $lead->state) == 'OR' ? 'selected' : '' }}>Oregon</option>
                                <option value="PA" {{ old('state', $lead->state) == 'PA' ? 'selected' : '' }}>Pennsylvania</option>
                                <option value="RI" {{ old('state', $lead->state) == 'RI' ? 'selected' : '' }}>Rhode Island</option>
                                <option value="SC" {{ old('state', $lead->state) == 'SC' ? 'selected' : '' }}>South Carolina</option>
                                <option value="SD" {{ old('state', $lead->state) == 'SD' ? 'selected' : '' }}>South Dakota</option>
                                <option value="TN" {{ old('state', $lead->state) == 'TN' ? 'selected' : '' }}>Tennessee</option>
                                <option value="TX" {{ old('state', $lead->state) == 'TX' ? 'selected' : '' }}>Texas</option>
                                <option value="UT" {{ old('state', $lead->state) == 'UT' ? 'selected' : '' }}>Utah</option>
                                <option value="VT" {{ old('state', $lead->state) == 'VT' ? 'selected' : '' }}>Vermont</option>
                                <option value="VA" {{ old('state', $lead->state) == 'VA' ? 'selected' : '' }}>Virginia</option>
                                <option value="WA" {{ old('state', $lead->state) == 'WA' ? 'selected' : '' }}>Washington</option>
                                <option value="WV" {{ old('state', $lead->state) == 'WV' ? 'selected' : '' }}>West Virginia</option>
                                <option value="WI" {{ old('state', $lead->state) == 'WI' ? 'selected' : '' }}>Wisconsin</option>
                                <option value="WY" {{ old('state', $lead->state) == 'WY' ? 'selected' : '' }}>Wyoming</option>
                            </select>
                        </div>
                        <div>
                            <label for="zip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                ZIP Code
                            </label>
                            <input type="text" id="zip" name="zip" value="{{ old('zip', $lead->zip) }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                placeholder="12345">
                        </div>
                    </div>
                </div>

                <!-- Agent & Verifier Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-users text-purple-500 mr-3"></i>Agent & Verifier Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="agent_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Agent Name
                            </label>
                            <input type="text" id="agent_name" name="agent_name" value="{{ old('agent_name', $lead->agent_name) }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                placeholder="Agent Name">
                        </div>
                        <div>
                            <label for="verifier_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Verifier Name
                            </label>
                            <input type="text" id="verifier_name" name="verifier_name" value="{{ old('verifier_name', $lead->verifier_name) }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                placeholder="Verifier Name">
                        </div>
                    </div>
                </div>

                <!-- Additional Notes Section -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-yellow-500 mr-3"></i>Additional Notes
                    </h2>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" name="notes" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                            placeholder="Any additional notes about this lead...">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6">
                    <a href="{{ route('agent.leads.show', $lead) }}" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save mr-2"></i>Update Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Real-time campaign lookup by DID
function setupDidCampaignLookup() {
    const didInput = document.getElementById('did_number');
    const campaignNameInput = document.getElementById('campaign_name');
    const campaignIdInput = document.getElementById('campaign_id');

    didInput.addEventListener('input', function() {
        const didNumber = this.value.trim();
        
        if (didNumber.length >= 3) {
            fetch(`/api/campaign-by-did?did=${encodeURIComponent(didNumber)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.campaign_name) {
                        campaignNameInput.value = data.campaign_name;
                        campaignIdInput.value = data.campaign_id;
                        campaignNameInput.classList.remove('border-red-500');
                        campaignNameInput.classList.add('border-green-500');
                    } else {
                        campaignNameInput.value = '';
                        campaignIdInput.value = '';
                        campaignNameInput.classList.remove('border-green-500');
                        if (didNumber.length > 0) {
                            campaignNameInput.classList.add('border-red-500');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching campaign:', error);
                });
        } else {
            campaignNameInput.value = '';
            campaignIdInput.value = '';
            campaignNameInput.classList.remove('border-green-500', 'border-red-500');
        }
    });
}

// User suggestions for agent/verifier fields
function setupUserSuggestions(inputId, suggestionsId) {
    const input = document.getElementById(inputId);
    const suggestions = document.getElementById(suggestionsId);
    let debounceTimer;

    input.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            if (query.length >= 2) {
                fetch(`/api/user-suggestions?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => {
                        suggestions.innerHTML = '';
                        
                        if (users.length > 0) {
                            users.forEach(user => {
                                const div = document.createElement('div');
                                div.className = 'px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer text-sm';
                                div.textContent = user;
                                div.addEventListener('click', function() {
                                    input.value = user;
                                    suggestions.classList.add('hidden');
                                    input.classList.remove('border-red-500');
                                    input.classList.add('border-green-500');
                                });
                                suggestions.appendChild(div);
                            });
                            suggestions.classList.remove('hidden');
                        } else {
                            suggestions.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching users:', error);
                        suggestions.classList.add('hidden');
                    });
            } else {
                suggestions.classList.add('hidden');
            }
        }, 300);
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.classList.add('hidden');
        }
    });

    // Validate user exists when field loses focus
    input.addEventListener('blur', function() {
        const username = this.value.trim();
        if (username) {
            fetch(`/api/user-suggestions?query=${encodeURIComponent(username)}`)
                .then(response => response.json())
                .then(users => {
                    if (users.includes(username)) {
                        input.classList.remove('border-red-500');
                        input.classList.add('border-green-500');
                        // Remove any existing warning
                        const warning = input.parentNode.querySelector('.user-warning');
                        if (warning) {
                            warning.remove();
                        }
                    } else {
                        input.classList.remove('border-green-500');
                        input.classList.add('border-red-500');
                        // Show warning
                        let warning = input.parentNode.querySelector('.user-warning');
                        if (!warning) {
                            warning = document.createElement('p');
                            warning.className = 'text-red-500 text-sm mt-1 user-warning';
                            warning.textContent = 'Username not found in CRM system (Admin/Agent only)';
                            input.parentNode.appendChild(warning);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error validating user:', error);
                });
        }
    });

    // Remove warning when user starts typing
    input.addEventListener('focus', function() {
        const warning = input.parentNode.querySelector('.user-warning');
        if (warning) {
            warning.remove();
        }
        input.classList.remove('border-red-500', 'border-green-500');
    });
}

// Initialize all functionality
document.addEventListener('DOMContentLoaded', function() {
    setupDidCampaignLookup();
    setupUserSuggestions('agent_name', 'agent_suggestions');
    setupUserSuggestions('verifier_name', 'verifier_suggestions');
});
</script>
@endsection

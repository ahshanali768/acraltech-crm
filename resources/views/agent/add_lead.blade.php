@extends('layouts.agent')

@section('title', 'Add New Lead')

@push('styles')
<style>
    .form-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .dark .form-card {
        background: linear-gradient(135deg, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.9) 100%);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .form-input {
        background: rgba(255,255,255,0.8);
        border: 1px solid rgba(0,0,0,0.1);
        color: #1f2937;
    }
    .dark .form-input {
        background: rgba(30,41,59,0.8);
        border: 1px solid rgba(255,255,255,0.1);
        color: #f1f5f9;
    }
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }
    .autocomplete-dropdown {
        display: none;
        position: absolute;
        z-index: 50;
        width: 100%;
        background-color: white;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.375rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-height: 15rem;
        overflow-y: auto;
        color: #1f2937;
    }
    .autocomplete-dropdown div {
        padding: 0.5rem 1rem;
        cursor: pointer;
    }
    .autocomplete-dropdown div:hover {
        background-color: rgba(59, 130, 246, 0.1);
    }
    .autocomplete-dropdown div.selected {
        background-color: rgba(59, 130, 246, 0.3);
    }
    /* Dark mode for autocomplete dropdown */
    .dark .autocomplete-dropdown {
        background-color: #1e293b;
        border: 1px solid rgba(255,255,255,0.1);
        color: #f1f5f9;
    }
    .dark .autocomplete-dropdown div:hover {
        background-color: rgba(59, 130, 246, 0.15);
    }
    .dark .autocomplete-dropdown div.selected {
        background-color: rgba(59, 130, 246, 0.4);
    }
</style>
@endpush

@push('scripts')
<!-- TrustedForm -->
<script type="text/javascript">
  (function() {
    var tf = document.createElement('script');
    tf.type = 'text/javascript';
    tf.async = true;
    tf.src = ("https:" == document.location.protocol ? 'https' : 'http') +
      '://api.trustedform.com/trustedform.js?field=xxTrustedFormCertUrl&use_tagged_consent=true&l=' +
      new Date().getTime() + Math.random();
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(tf, s);
  })();
</script>
<noscript>
  <img src='https://api.trustedform.com/ns.gif' />
</noscript>
<!-- End TrustedForm -->

<!-- Jornaya LeadiD -->
<script type="text/javascript">
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.async = true;
  script.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'js.jornaya.com/leadid_v2.js';
  script.onload = function() {
    console.log('Jornaya LeadiD script loaded successfully');
    // Initialize Jornaya tracking
    if (typeof LeadiD !== 'undefined') {
      LeadiD.init();
      console.log('Jornaya LeadiD initialized');
    }
  };
  var firstScript = document.getElementsByTagName('script')[0];
  firstScript.parentNode.insertBefore(script, firstScript);
</script>
<!-- End Jornaya -->
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <div class="font-semibold mb-2">Please correct the following errors:</div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30">
                    <i class="fas fa-user-plus text-2xl text-primary-600 dark:text-primary-400"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Lead</h1>
                    <p class="text-gray-600 dark:text-gray-300">Capture and manage potential customer information</p>
                </div>
            </div>
        </div>

        <form action="{{ route('agent.leads.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- TrustedForm Hidden Field -->
            <input type="hidden" name="xxTrustedFormCertUrl" id="xxTrustedFormCertUrl" value="" />
            
            <!-- Hidden tracking fields -->
            <input type="hidden" name="name" id="name" value="" />
            <input type="hidden" name="contact_info" id="contact_info" value="" />
            <input type="hidden" name="campaign_id" id="campaign_id" value="" />
            <input type="hidden" name="trusted_form_cert_id" id="trusted_form_cert_id" value="" />
            <input type="hidden" name="trusted_form_url" id="trusted_form_url" value="" />
            <input type="hidden" name="ip_address" id="ip_address" value="" />
            <input type="hidden" name="jornaya_lead_id" id="jornaya_lead_id" value="" />
            <input type="hidden" name="lead_submitted_at" id="lead_submitted_at" value="" />
            
            <!-- Available DIDs Section (Top) -->
            <div class="form-card rounded-2xl p-6 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <!-- Icon and Title (Left Side) -->
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30">
                            <i class="fas fa-phone text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Available DIDs</h3>
                    </div>
                    
                    <!-- Password Field (Center) -->
                    <div class="flex justify-center flex-1">
                        <input type="password" id="dids-password-inline" placeholder="Enter 4-digit code" maxlength="4"
                            class="w-48 px-4 py-2 rounded-xl border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-center text-lg font-mono"
                            onkeypress="if(event.key==='Enter') verifyDIDsPasswordInline()" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)">
                    </div>
                    
                    <!-- Enter Button (Right Side) -->
                    <div class="flex-shrink-0">
                        <button type="button" onclick="verifyDIDsPasswordInline()" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all duration-200 focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-key mr-2"></i>Enter
                        </button>
                    </div>
                </div>
                
                <!-- DIDs List (Initially Hidden) -->
                <div id="dids-list" class="hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3">
                        @if(isset($activeCampaigns) && count($activeCampaigns) > 0)
                            @foreach($activeCampaigns as $campaign)
                                <div class="bg-white/70 dark:bg-gray-800/70 border border-gray-200 dark:border-gray-700 rounded-lg p-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200 hover:shadow-md">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-phone text-green-600 dark:text-green-400 text-sm"></i>
                                        <span class="text-gray-900 dark:text-white font-semibold text-sm">
                                            {{ $campaign['did'] }} ({{ $campaign['campaign_name'] }})
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-full text-center py-8">
                                <i class="fas fa-phone-slash text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                                <p class="text-gray-500 dark:text-gray-400">No DIDs available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="form-card rounded-2xl p-6 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                        <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Personal Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-tag mr-2 text-blue-600 dark:text-blue-400"></i>First Name
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}"
                               class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all duration-300"
                               placeholder="Enter first name"
                               required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-tag mr-2 text-blue-600 dark:text-blue-400"></i>Last Name
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}"
                               class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all duration-300"
                               placeholder="Enter last name"
                               required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-green-600 dark:text-green-400"></i>Phone Number
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-300"
                               placeholder="5551234567"
                               required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-envelope mr-2 text-purple-600 dark:text-purple-400"></i>Email Address
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all duration-300"
                               placeholder="Enter email address">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Details -->
            <div class="form-card rounded-2xl p-6 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-purple-100 dark:bg-purple-900/30">
                        <i class="fas fa-map-marker-alt text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Contact Details</h3>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-home mr-2 text-purple-600 dark:text-purple-400"></i>Street Address
                        </label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               value="{{ old('address') }}"
                               class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all duration-300"
                               placeholder="Enter street address">
                        @error('address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-city mr-2 text-purple-600 dark:text-purple-400"></i>City
                            </label>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   value="{{ old('city') }}"
                                   class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all duration-300"
                                   placeholder="Enter city">
                            @error('city')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-flag mr-2 text-purple-600 dark:text-purple-400"></i>State
                            </label>
                            <select id="state" name="state" class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all duration-300" required>
                                <option value="">Select State</option>
                                <option value="AL">AL</option>
                                <option value="AK">AK</option>
                                <option value="AZ">AZ</option>
                                <option value="AR">AR</option>
                                <option value="CA">CA</option>
                                <option value="CO">CO</option>
                                <option value="CT">CT</option>
                                <option value="DE">DE</option>
                                <option value="FL">FL</option>
                                <option value="GA">GA</option>
                                <option value="HI">HI</option>
                                <option value="ID">ID</option>
                                <option value="IL">IL</option>
                                <option value="IN">IN</option>
                                <option value="IA">IA</option>
                                <option value="KS">KS</option>
                                <option value="KY">KY</option>
                                <option value="LA">LA</option>
                                <option value="ME">ME</option>
                                <option value="MD">MD</option>
                                <option value="MA">MA</option>
                                <option value="MI">MI</option>
                                <option value="MN">MN</option>
                                <option value="MS">MS</option>
                                <option value="MO">MO</option>
                                <option value="MT">MT</option>
                                <option value="NE">NE</option>
                                <option value="NV">NV</option>
                                <option value="NH">NH</option>
                                <option value="NJ">NJ</option>
                                <option value="NM">NM</option>
                                <option value="NY">NY</option>
                                <option value="NC">NC</option>
                                <option value="ND">ND</option>
                                <option value="OH">OH</option>
                                <option value="OK">OK</option>
                                <option value="OR">OR</option>
                                <option value="PA">PA</option>
                                <option value="RI">RI</option>
                                <option value="SC">SC</option>
                                <option value="SD">SD</option>
                                <option value="TN">TN</option>
                                <option value="TX">TX</option>
                                <option value="UT">UT</option>
                                <option value="VT">VT</option>
                                <option value="VA">VA</option>
                                <option value="WA">WA</option>
                                <option value="WV">WV</option>
                                <option value="WI">WI</option>
                                <option value="WY">WY</option>
                            </select>
                            @error('state')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="zip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-mail-bulk mr-2 text-purple-600 dark:text-purple-400"></i>ZIP Code
                            </label>
                            <input type="text" 
                                   id="zip" 
                                   name="zip" 
                                   value="{{ old('zip') }}"
                                   class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all duration-300"
                                   placeholder="12345"
                                   maxlength="5"
                                   pattern="\d{5}"
                                   required>
                            @error('zip')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Information -->
            <div class="form-card rounded-2xl p-6 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-cyan-100 dark:bg-cyan-900/30">
                        <i class="fas fa-bullhorn text-cyan-600 dark:text-cyan-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Campaign Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="did_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone-square mr-2 text-cyan-600 dark:text-cyan-400"></i>DID Number
                        </label>
                        <input type="text" 
                               id="did_number" 
                               name="did_number" 
                               value="{{ old('did_number') }}"
                               class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all duration-300"
                               placeholder="Enter DID number (e.g., 5024667411)">
                        @error('did_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="campaign_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tag mr-2 text-cyan-600 dark:text-cyan-400"></i>Campaign Name
                        </label>
                        <input type="text" 
                               id="campaign_name" 
                               name="campaign_name" 
                               value="{{ old('campaign_name') }}"
                               class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all duration-300 bg-gray-100 dark:bg-gray-700"
                               placeholder="Campaign name will auto-populate"
                               readonly>
                        @error('campaign_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="agent_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-tie mr-2 text-cyan-600 dark:text-cyan-400"></i>Agent Username
                        </label>
                        <div style="position: relative;">
                            <input type="text"
                                   id="agent_name"
                                   name="agent_name"
                                   autocomplete="off"
                                   class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all duration-300"
                                   placeholder="Enter or select agent username"
                                   required>
                            <div id="agent_autocomplete" class="autocomplete-dropdown"></div>
                        </div>
                    </div>

                    <div>
                        <label for="verifier_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-check mr-2 text-cyan-600 dark:text-cyan-400"></i>Verifier Username
                        </label>
                        <div style="position: relative;">
                            <input type="text"
                                   id="verifier_name"
                                   name="verifier_name"
                                   autocomplete="off"
                                   class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all duration-300"
                                   placeholder="Enter or select verifier username (optional)">
                            <div id="verifier_autocomplete" class="autocomplete-dropdown"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-sticky-note mr-2 text-yellow-600 dark:text-yellow-400"></i>Additional Notes
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="4"
                              class="form-input w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500/50 transition-all duration-300 resize-none"
                              placeholder="Enter any additional notes or comments...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <!-- Tracking Status Indicator -->
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                    <div class="flex items-center gap-2">
                        <span>Tracking Status:</span>
                        <span id="trustedform-status" class="px-2 py-1 rounded-full bg-gray-200 dark:bg-gray-700">TF: Loading...</span>
                        <span id="jornaya-status" class="px-2 py-1 rounded-full bg-gray-200 dark:bg-gray-700">Jornaya: Loading...</span>
                    </div>
                </div>
                
                <a href="{{ route('agent.dashboard') }}" 
                   class="px-6 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        onclick="
                            console.log('Save button clicked');
                            document.getElementById('lead_submitted_at').value = new Date().toISOString();
                            updateCompositeFields();
                            console.log('Form data before submission:', new FormData(this.form));
                            return true;
                        "
                        class="px-8 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>Save Lead
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tracking data
    initializeTrackingData();
    
    // Campaign data for DID lookup
    const campaignData = @json($activeCampaigns ?? []);
    
    // DID input handler for real-time campaign lookup
    const didInput = document.getElementById('did_number');
    const campaignInput = document.getElementById('campaign_name');
    
    if (didInput && campaignInput) {
        didInput.addEventListener('input', function(e) {
            const enteredDid = e.target.value.trim();
            
            // Clear campaign name if DID is empty
            if (!enteredDid) {
                campaignInput.value = '';
                campaignInput.style.backgroundColor = '';
                return;
            }
            
            // Look for matching campaign
            const matchingCampaign = campaignData.find(campaign => 
                campaign.did === enteredDid
            );
            
            if (matchingCampaign) {
                campaignInput.value = matchingCampaign.campaign_name;
                // Set the campaign_id in the hidden field
                document.getElementById('campaign_id').value = matchingCampaign.id;
                // Visual feedback for found campaign
                campaignInput.style.backgroundColor = 'rgba(34, 197, 94, 0.1)';
                campaignInput.style.borderColor = 'rgb(34, 197, 94)';
            } else {
                campaignInput.value = '';
                // Clear the campaign_id if no match
                document.getElementById('campaign_id').value = '';
                // Visual feedback for not found
                campaignInput.style.backgroundColor = '';
                campaignInput.style.borderColor = '';
            }
        });
        
        // Also check on blur for final validation
        didInput.addEventListener('blur', function(e) {
            const enteredDid = e.target.value.trim();
            if (enteredDid) {
                const matchingCampaign = campaignData.find(campaign => 
                    campaign.did === enteredDid
                );
                
                if (!matchingCampaign) {
                    // Visual feedback for invalid DID
                    didInput.style.borderColor = 'rgb(239, 68, 68)';
                    didInput.style.backgroundColor = 'rgba(239, 68, 68, 0.1)';
                } else {
                    // Reset to normal styling
                    didInput.style.borderColor = '';
                    didInput.style.backgroundColor = '';
                }
            }
        });
    }

    // Initialize tracking data function
    function initializeTrackingData() {
        // Only set IP address and Jornaya Lead ID, remove other tracking fields
        // Try to get real Jornaya Lead ID
        captureJornayaLeadId();
        // Get IP address (this will be set server-side, but we can try client-side too)
        fetch('https://api.ipify.org?format=json')
            .then(response => response.json())
            .then(data => {
                document.getElementById('ip_address').value = data.ip;
            })
            .catch(error => {
                console.log('Could not get IP address:', error);
            });
    }
    
    // Capture real Jornaya Lead ID
    function captureJornayaLeadId() {
        // Check if Jornaya LeadiD is available
        if (typeof LeadiD !== 'undefined') {
            try {
                const leadId = LeadiD.getToken();
                if (leadId) {
                    console.log('Real Jornaya Lead ID captured:', leadId);
                    document.getElementById('jornaya_lead_id').value = leadId;
                    updateTrackingStatus('jornaya', 'real');
                } else {
                    console.log('Jornaya LeadiD available but no token yet, retrying...');
                    updateTrackingStatus('jornaya', 'waiting');
                    // Retry after a short delay
                    setTimeout(captureJornayaLeadId, 1000);
                }
            } catch (error) {
                console.log('Error capturing Jornaya Lead ID:', error);
                updateTrackingStatus('jornaya', 'simulated');
            }
        } else {
            console.log('Jornaya LeadiD not loaded yet, retrying...');
            updateTrackingStatus('jornaya', 'waiting');
            // Retry after script loads
            setTimeout(captureJornayaLeadId, 1000);
        }
    }
    
    // Generate a unique session ID
    function generateSessionId() {
        return 'sess_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
    
    // Generate a simple device fingerprint
    function generateDeviceFingerprint() {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        ctx.textBaseline = 'top';
        ctx.font = '14px Arial';
        ctx.fillText('Device fingerprint', 2, 2);
        
        const fingerprint = [
            navigator.userAgent,
            navigator.language,
            screen.width + 'x' + screen.height,
            new Date().getTimezoneOffset(),
            canvas.toDataURL()
        ].join('|');
        
        return btoa(fingerprint).substr(0, 32);
    }
    
    // Monitor TrustedForm field for changes (REAL CERTIFICATE CAPTURE)
    const trustedFormField = document.getElementById('xxTrustedFormCertUrl');
    if (trustedFormField) {
        // Check if TrustedForm loaded successfully
        console.log('TrustedForm monitoring initialized');
        
        // Monitor for value changes (real TrustedForm certificates)
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                    const certUrl = trustedFormField.value;
                    if (certUrl && certUrl.includes('cert.trustedform.com')) {
                        console.log('Real TrustedForm certificate captured:', certUrl);
                        // Extract certificate ID from URL
                        const certId = certUrl.split('/').pop().split('?')[0];
                        document.getElementById('trusted_form_cert_id').value = certId;
                        document.getElementById('trusted_form_url').value = certUrl;
                    }
                }
            });
        });
        
        observer.observe(trustedFormField, {
            attributes: true,
            attributeFilter: ['value']
        });
        
        // Also check periodically for TrustedForm cert URL (REAL CAPTURE)
        setInterval(function() {
            const currentValue = trustedFormField.value;
            if (currentValue && currentValue.includes('cert.trustedform.com') && !document.getElementById('trusted_form_cert_id').value) {
                console.log('TrustedForm certificate found via polling:', currentValue);
                const certId = currentValue.split('/').pop().split('?')[0];
                document.getElementById('trusted_form_cert_id').value = certId;
                document.getElementById('trusted_form_url').value = currentValue;
                updateTrackingStatus('trustedform', 'real');
            }
        }, 1000);
        
        // Update status to show we're waiting for real certificate
        updateTrackingStatus('trustedform', 'waiting');
        
        // Alternative: Check if TrustedForm is loaded in window
        if (window.tf_async_success) {
            console.log('TrustedForm script loaded successfully');
        }
    }
    
    // Update tracking status indicators
    function updateTrackingStatus(service, status) {
        const statusElement = document.getElementById(service + '-status');
        if (statusElement) {
            switch (status) {
                case 'real':
                    statusElement.textContent = (service === 'trustedform' ? 'TF' : 'Jornaya') + ': Real ✓';
                    statusElement.className = 'px-2 py-1 rounded-full bg-green-200 dark:bg-green-700 text-green-800 dark:text-green-200';
                    break;
                case 'simulated':
                    statusElement.textContent = (service === 'trustedform' ? 'TF' : 'Jornaya') + ': Simulated';
                    statusElement.className = 'px-2 py-1 rounded-full bg-yellow-200 dark:bg-yellow-700 text-yellow-800 dark:text-yellow-200';
                    break;
                case 'waiting':
                    statusElement.textContent = (service === 'trustedform' ? 'TF' : 'Jornaya') + ': Waiting...';
                    statusElement.className = 'px-2 py-1 rounded-full bg-blue-200 dark:bg-blue-700 text-blue-800 dark:text-blue-200';
                    break;
                default:
                    statusElement.textContent = (service === 'trustedform' ? 'TF' : 'Jornaya') + ': Loading...';
                    statusElement.className = 'px-2 py-1 rounded-full bg-gray-200 dark:bg-gray-700';
            }
        }
    }

    // State abbreviation mapping
    const stateAbbreviations = {
        'alabama': 'AL', 'alaska': 'AK', 'arizona': 'AZ', 'arkansas': 'AR', 'california': 'CA',
        'colorado': 'CO', 'connecticut': 'CT', 'delaware': 'DE', 'florida': 'FL', 'georgia': 'GA',
        'hawaii': 'HI', 'idaho': 'ID', 'illinois': 'IL', 'indiana': 'IN', 'iowa': 'IA',
        'kansas': 'KS', 'kentucky': 'KY', 'louisiana': 'LA', 'maine': 'ME', 'maryland': 'MD',
        'massachusetts': 'MA', 'michigan': 'MI', 'minnesota': 'MN', 'mississippi': 'MS', 'missouri': 'MO',
        'montana': 'MT', 'nebraska': 'NE', 'nevada': 'NV', 'new hampshire': 'NH', 'new jersey': 'NJ',
        'new mexico': 'NM', 'new york': 'NY', 'north carolina': 'NC', 'north dakota': 'ND', 'ohio': 'OH',
        'oklahoma': 'OK', 'oregon': 'OR', 'pennsylvania': 'PA', 'rhode island': 'RI', 'south carolina': 'SC',
        'south dakota': 'SD', 'tennessee': 'TN', 'texas': 'TX', 'utah': 'UT', 'vermont': 'VT',
        'virginia': 'VA', 'washington': 'WA', 'west virginia': 'WV', 'wisconsin': 'WI', 'wyoming': 'WY'
    };

    // Phone number formatting (10 digits only)
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            e.target.value = value;
        });
    }

    // State input with abbreviation conversion
    const stateInput = document.getElementById('state');
    if (stateInput) {
        stateInput.addEventListener('blur', function(e) {
            let value = e.target.value.toLowerCase().trim();
            if (stateAbbreviations[value]) {
                e.target.value = stateAbbreviations[value];
            } else {
                e.target.value = e.target.value.toUpperCase();
            }
        });
    }

    // ZIP code formatting
    const zipInput = document.getElementById('zip');
    if (zipInput) {
        zipInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.slice(0, 5) + '-' + value.slice(5, 9);
            }
            e.target.value = value;
        });
    }

    // ZIP code auto-fill for city/state using Zippopotam.us API
    if (zipInput) {
        var lastZip = '';
        function autofillCityState(zip) {
            fetch('https://api.zippopotam.us/us/' + zip)
                .then(function(response) {
                    if (!response.ok) throw new Error('ZIP not found');
                    return response.json();
                })
                .then(function(data) {
                    var place = data.places && data.places[0];
                    if (place) {
                        var cityInput = document.getElementById('city');
                        var stateInput = document.getElementById('state');
                        if (cityInput) cityInput.value = place['place name'];
                        if (stateInput) stateInput.value = place['state abbreviation'];
                    }
                })
                .catch(function(err) {
                    // Optionally clear autofilled city/state if ZIP is invalid
                });
        }
        zipInput.addEventListener('input', function(e) {
            var value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) value = value.slice(0, 5);
            e.target.value = value;
            if (value.length === 5 && value !== lastZip) {
                lastZip = value;
                autofillCityState(value);
            }
        });
        zipInput.addEventListener('blur', function(e) {
            var value = e.target.value.replace(/\D/g, '');
            if (value.length === 5 && value !== lastZip) {
                lastZip = value;
                autofillCityState(value);
            }
        });
    }

    // Real-time update of composite fields
    function updateCompositeFields() {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();
        const address = document.getElementById('address').value.trim();
        const city = document.getElementById('city').value.trim();
        const state = document.getElementById('state').value.trim();
        const zip = document.getElementById('zip').value.trim();
        
        // Update name field
        document.getElementById('name').value = (firstName + ' ' + lastName).trim();
        
        // Update contact_info JSON
        const contactInfo = {
            phone: phone,
            email: email,
            address: address,
            city: city,
            state: state,
            zip: zip
        };
        document.getElementById('contact_info').value = JSON.stringify(contactInfo);
    }
    
    // Add form submission event listener
    const form = document.querySelector('form');
    if (form) {
        // Add error handler for any JavaScript errors
        window.addEventListener('error', function(e) {
            console.error('JavaScript error:', e.error);
            console.error('Error details:', e.message, e.filename, e.lineno);
        });

        form.addEventListener('submit', function(e) {
            console.log('Form submission initiated...');
            try {
                // Set submission timestamp
                document.getElementById('lead_submitted_at').value = new Date().toISOString();
                // Populate composite fields before submission
                updateCompositeFields();
                // Debug: Log field values
                const firstName = document.getElementById('first_name').value.trim();
                const lastName = document.getElementById('last_name').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const email = document.getElementById('email').value.trim();
                console.log('Form data:', {
                    firstName,
                    lastName,
                    phone,
                    email,
                    name: document.getElementById('name').value,
                    contact_info: document.getElementById('contact_info').value
                });
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                let missingFields = [];
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        missingFields.push(field.name || field.id);
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                if (!isValid) {
                    e.preventDefault();
                    console.log('Form validation failed. Missing required fields:', missingFields);
                    alert('Please fill in all required fields: ' + missingFields.join(', '));
                    // Scroll to first invalid field
                    const firstInvalid = form.querySelector('.border-red-500');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstInvalid.focus();
                    }
                } else {
                    console.log('Form validation passed. Submitting form...');
                    // Additional debugging - check for any last-minute issues
                    console.log('Form action:', form.action);
                    console.log('Form method:', form.method);
                    console.log('CSRF token:', document.querySelector('input[name="_token"]')?.value);
                    // Let the form submit naturally
                    return true;
                }
            } catch (error) {
                console.error('Error in form submission handler:', error);
                e.preventDefault();
                alert('An error occurred while processing the form. Please try again.');
            }
        });
    }

    // Add event listeners to update composite fields in real-time
    ['first_name', 'last_name', 'phone', 'email', 'address', 'city', 'state', 'zip'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', updateCompositeFields);
            field.addEventListener('blur', updateCompositeFields);
        }
    });

    // Additional form submission debugging
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            console.log('Submit button clicked');
            console.log('Button type:', this.type);
            console.log('Form element:', this.form);
        });
    }

    // Custom autocomplete for agent/verifier username
    function setupCustomAutocomplete(inputId, dropdownId) {
        const input = document.getElementById(inputId);
        const dropdown = document.getElementById(dropdownId);
        let suggestions = [];
        let selectedIndex = -1;
        let closeTimeout;

        function showDropdown() {
            dropdown.style.display = suggestions.length ? 'block' : 'none';
        }
        function hideDropdown() {
            dropdown.style.display = 'none';
            selectedIndex = -1;
        }
        function renderSuggestions() {
            dropdown.innerHTML = '';
            suggestions.forEach((u, i) => {
                const div = document.createElement('div');
                div.textContent = u;
                div.className = (i === selectedIndex ? 'selected' : '');
                div.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    input.value = u;
                    hideDropdown();
                });
                dropdown.appendChild(div);
            });
            showDropdown();
        }
        function fetchAndShow(query) {
            fetchUserSuggestions(query, function(users) {
                suggestions = users;
                selectedIndex = -1;
                renderSuggestions();
            });
        }
        input.addEventListener('input', function() {
            fetchAndShow(input.value.trim());
        });
        input.addEventListener('focus', function() {
            fetchAndShow('');
        });
        input.addEventListener('keydown', function(e) {
            if (!suggestions.length) return;
            if (e.key === 'ArrowDown') {
                selectedIndex = (selectedIndex + 1) % suggestions.length;
                renderSuggestions();
                e.preventDefault();
            } else if (e.key === 'ArrowUp') {
                selectedIndex = (selectedIndex - 1 + suggestions.length) % suggestions.length;
                renderSuggestions();
                e.preventDefault();
            } else if (e.key === 'Enter') {
                if (selectedIndex >= 0 && selectedIndex < suggestions.length) {
                    input.value = suggestions[selectedIndex];
                    hideDropdown();
                    e.preventDefault();
                }
            } else if (e.key === 'Escape') {
                hideDropdown();
            }
        });
        input.addEventListener('blur', function() {
            closeTimeout = setTimeout(hideDropdown, 100);
        });
        dropdown.addEventListener('mousedown', function(e) {
            e.preventDefault();
        });
    }
    setupCustomAutocomplete('agent_name', 'agent_autocomplete');
    setupCustomAutocomplete('verifier_name', 'verifier_autocomplete');

    // Fetch user suggestions for autocomplete
    function fetchUserSuggestions(query, callback) {
        fetch('/api/user-suggestions?query=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => callback(Array.isArray(data) ? data : []))
            .catch(() => callback([]));
    }
});

// DIDs section password protection functions
let didsUnlocked = false;

function verifyDIDsPasswordInline() {
    const passwordInput = document.getElementById('dids-password-inline');
    const enteredPassword = passwordInput.value.trim();
    
    if (!enteredPassword) {
        passwordInput.style.borderColor = '#ef4444';
        passwordInput.placeholder = 'Please enter 4-digit code';
        setTimeout(() => {
            passwordInput.style.borderColor = '';
            passwordInput.placeholder = 'Enter 4-digit code';
        }, 2000);
        return;
    }
    
    // Fetch today's password from server
    fetch('/api/daily-password')
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data); // Debug log
            if (enteredPassword === data.password) {
                // Correct password
                didsUnlocked = true;
                
                // Show the DIDs list
                const didsList = document.getElementById('dids-list');
                didsList.classList.remove('hidden');
                
                // Clear and disable password field
                passwordInput.value = '';
                passwordInput.disabled = true;
                passwordInput.placeholder = 'Access granted';
                passwordInput.style.backgroundColor = '#dcfce7';
                passwordInput.style.borderColor = '#22c55e';
                
                // Success notification
                showNotification('DIDs unlocked successfully!', 'success');
            } else {
                // Wrong password
                passwordInput.value = '';
                passwordInput.style.borderColor = '#ef4444';
                passwordInput.placeholder = 'Incorrect 4-digit code, try again';
                
                // Reset border color after 2 seconds
                setTimeout(() => {
                    passwordInput.style.borderColor = '';
                    passwordInput.placeholder = 'Enter 4-digit code';
                }, 2000);
                
                showNotification('Incorrect password!', 'error');
            }
        })
        .catch(error => {
            console.error('Error fetching password:', error);
            showNotification('Error verifying password!', 'error');
        });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white font-medium transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Slide in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Slide out and remove
    setTimeout(() => {
        notification.style.transform = 'translateX(full)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('password-modal');
    if (event.target === modal) {
        closeDIDsPasswordModal();
    }
});
</script>
@endsection
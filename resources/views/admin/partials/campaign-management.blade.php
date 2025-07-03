<!-- Campaign Management Content (without layout) -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Campaign Management</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create and manage your marketing campaigns</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-4">
            <!-- DID Password Display -->
            <div class="flex items-center bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg px-3 py-2">
                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mr-2">Today's DID Password:</span>
                <code class="text-sm font-bold text-yellow-900 dark:text-yellow-100 bg-yellow-100 dark:bg-yellow-800 px-2 py-1 rounded">{{ $currentDidPassword ?? 'N/A' }}</code>
            </div>
            
            <!-- Add Campaign Button -->
            <button onclick="openAddCampaignModal()" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Campaign
            </button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Campaigns Table -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaign Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vertical</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">DID Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Commission (INR)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payout $</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($campaigns ?? [] as $campaign)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $campaign->campaign_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            ID: {{ $campaign->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                                @if($campaign->vertical)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        @switch($campaign->vertical)
                                            @case('ACA Health')
                                                🏥 {{ $campaign->vertical }}
                                                @break
                                            @case('Final Expense')
                                                ⚱️ {{ $campaign->vertical }}
                                                @break
                                            @case('Pest Control')
                                                🐛 {{ $campaign->vertical }}
                                                @break
                                            @case('Auto Insurance')
                                                🚗 {{ $campaign->vertical }}
                                                @break
                                            @case('Medicare')
                                                🏥 {{ $campaign->vertical }}
                                                @break
                                            @case('Home Warranty')
                                                🏠 {{ $campaign->vertical }}
                                                @break
                                            @case('SSDI')
                                                ♿ {{ $campaign->vertical }}
                                                @break
                                            @case('Debt Relief')
                                                💳 {{ $campaign->vertical }}
                                                @break
                                            @case('Tax Debt Relief')
                                                📊 {{ $campaign->vertical }}
                                                @break
                                            @default
                                                📋 {{ $campaign->vertical }}
                                        @endswitch
                                    </span>
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center font-mono">
                                {{ $campaign->did ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                                ₹{{ number_format($campaign->commission_inr ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                                ${{ number_format($campaign->payout_usd ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @switch($campaign->status ?? 'draft')
                                    @case('active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></span>
                                            Active
                                        </span>
                                        @break
                                    @case('paused')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1.5"></span>
                                            Paused
                                        </span>
                                        @break
                                    @case('draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                            Draft
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            Unknown
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <button onclick="editCampaign({{ $campaign->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                        Edit
                                    </button>
                                    <button onclick="deleteCampaign({{ $campaign->id }})" 
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">No campaigns</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Get started by creating your first campaign.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals and Scripts for Campaign Management -->

<!-- Add/Edit Campaign Modal -->
<div id="campaign-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl p-6 m-4 transform transition-all duration-300 ease-out">
        <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
            <h4 id="campaign-modal-title" class="text-xl font-semibold text-gray-900 dark:text-white">Add New Campaign</h4>
            <button onclick="closeCampaignModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-transform transform hover:rotate-90">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="campaign-form" class="space-y-6 mt-6">
            @csrf
            <input type="hidden" id="campaign-id" name="id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="campaign_name" class="form-label">Campaign Name</label>
                    <input type="text" id="campaign_name" name="campaign_name" class="form-input" placeholder="e.g., Summer Sale Campaign" required>
                </div>
                <div class="form-group">
                    <label for="vertical" class="form-label">Vertical</label>
                    <select id="vertical" name="vertical" class="form-input" required>
                        <option value="ACA Health" selected>ACA Health</option>
                        <option value="Final Expense">Final Expense</option>
                        <option value="Pest Control">Pest Control</option>
                        <option value="Auto Insurance">Auto Insurance</option>
                        <option value="Medicare">Medicare</option>
                        <option value="Home Warranty">Home Warranty</option>
                        <option value="SSDI">SSDI</option>
                        <option value="Debt Relief">Debt Relief</option>
                        <option value="Tax Debt Relief">Tax Debt Relief</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="did" class="form-label">DID Number</label>
                    <input type="text" id="did" name="did" class="form-input" placeholder="Enter associated DID number" required>
                </div>
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-input" required>
                        <option value="active" selected>Active</option>
                        <option value="paused">Paused</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="commission_inr" class="form-label">Commission (INR)</label>
                    <input type="number" step="0.01" id="commission_inr" name="commission_inr" class="form-input" placeholder="0.00" value="0" required>
                </div>
                <div class="form-group">
                    <label for="payout_usd" class="form-label">Payout (USD)</label>
                    <input type="number" step="0.01" id="payout_usd" name="payout_usd" class="form-input" placeholder="0.00" value="0" required>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="closeCampaignModal()" class="btn-secondary">Cancel</button>
                <button type="submit" id="save-campaign-btn" class="btn-primary">Save Campaign</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-campaign-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md p-6 m-4">
        <div class="text-center">
            <svg class="w-16 h-16 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Delete Campaign</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Are you sure you want to delete this campaign? This action cannot be undone.</p>
        </div>
        <div class="mt-6 flex justify-center space-x-4">
            <button onclick="closeDeleteModal()" class="btn-secondary">Cancel</button>
            <button id="confirm-delete-btn" class="btn-danger">Delete</button>
        </div>
    </div>
</div>

<script>
// Campaign JavaScript functionality is now handled by the main settings page
document.addEventListener('DOMContentLoaded', function () {
    const campaignModal = document.getElementById('campaign-modal');
    const deleteModal = document.getElementById('delete-campaign-modal');
    const campaignForm = document.getElementById('campaign-form');
    const modalTitle = document.getElementById('campaign-modal-title');
    const campaignIdField = document.getElementById('campaign-id');

    // --- Modal Control Functions ---
    window.openAddCampaignModal = function() {
        campaignForm.reset();
        campaignIdField.value = '';
        modalTitle.textContent = 'Add New Campaign';
        campaignForm.action = '{{ route("admin.manage_campaigns.store") }}';
        campaignModal.classList.remove('hidden');
    }

    window.closeCampaignModal = function() {
        campaignModal.classList.add('hidden');
    }

    window.closeDeleteModal = function() {
        deleteModal.classList.add('hidden');
    }

    // --- Edit Campaign ---
    window.editCampaign = function(id) {
        fetch(`/admin/campaigns/${id}/edit`)
            .then(response => response.json())
            .then(campaign => {
                modalTitle.textContent = 'Edit Campaign';
                campaignIdField.value = campaign.id;
                document.getElementById('campaign_name').value = campaign.campaign_name;
                document.getElementById('vertical').value = campaign.vertical;
                document.getElementById('did').value = campaign.did;
                document.getElementById('status').value = campaign.status;
                document.getElementById('commission_inr').value = campaign.commission_inr;
                document.getElementById('payout_usd').value = campaign.payout_usd;
                
                campaignForm.action = `/admin/campaigns/${id}`;
                campaignForm.querySelector('input[name="_method"]')?.remove(); // Remove existing method spoofing
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                campaignForm.appendChild(methodInput);

                campaignModal.classList.remove('hidden');
            });
    }

    // --- Delete Campaign ---
    window.deleteCampaign = function(id) {
        deleteModal.classList.remove('hidden');
        const confirmBtn = document.getElementById('confirm-delete-btn');
        confirmBtn.onclick = function() {
            fetch(`/admin/campaigns/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload(); // Or better, reload tab content
                }
            });
        }
    }

    // --- Form Submission ---
    campaignForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const url = this.action;
        const method = formData.get('_method') || 'POST';

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeCampaignModal();
                // Reload the campaigns tab content instead of the whole page
                loadTabData('campaigns'); 
            } else {
                // Handle errors (e.g., display validation messages)
                alert(data.message || 'An error occurred.');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

<!-- Campaign JavaScript functionality is now handled by the main settings page -->
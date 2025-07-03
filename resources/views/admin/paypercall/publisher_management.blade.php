@extends('layouts.pay_per_call')

@section('title', 'Publisher Management')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Publisher Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage publishers and their tracking DIDs for call distribution</p>
                </div>
                <button onclick="openAddPublisherModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Publisher
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Publishers</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['total_publishers'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-check-circle text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Publishers</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['active_publishers'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-phone text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Calls Today</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['total_calls_today'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-600 rounded-md flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Est. Earnings</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">${{ number_format($stats['total_earnings_month'] ?? 0, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Publishers Table -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Publishers & Tracking DIDs</h3>
                    <div class="relative">
                        <input type="text" placeholder="Search publishers..." class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Publisher Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tracking DID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Destination DID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Performance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payout</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($publishers as $publisher)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center text-white font-medium">
                                            {{ substr($publisher->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $publisher->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $publisher->email }}</div>
                                        @if($publisher->company)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $publisher->company }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($publisher->tracking_did)
                                    <div class="text-sm font-mono text-gray-900 dark:text-white">{{ $publisher->formatted_tracking_did }}</div>
                                    <div class="text-xs text-green-600 dark:text-green-400">
                                        <i class="fas fa-check-circle mr-1"></i>Assigned
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500 dark:text-gray-400">No DID assigned</div>
                                    <button onclick="assignDid({{ $publisher->id }})" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        <i class="fas fa-plus mr-1"></i>Assign DID
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($publisher->destination_did)
                                    <div class="text-sm font-mono text-gray-900 dark:text-white">{{ $publisher->formatted_destination_did }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Main buyer DID</div>
                                @else
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Not configured</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <div>{{ $publisher->total_calls }} total calls</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $publisher->getTotalCallsToday() }} today | {{ $publisher->conversion_rate }}% conversion
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($publisher->payout_rate, 2) }}
                                    @if($publisher->payout_type === 'per_call')
                                        <span class="text-xs text-gray-500">per call</span>
                                    @else
                                        <span class="text-xs text-gray-500">%</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Est. ${{ number_format($publisher->total_earnings, 2) }} total
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($publisher->status === 'active') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                    @elseif($publisher->status === 'inactive') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @endif">
                                    {{ ucfirst($publisher->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button onclick="viewPublisher({{ $publisher->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editPublisher({{ $publisher->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="togglePublisherStatus({{ $publisher->id }})" class="text-{{ $publisher->status === 'active' ? 'yellow' : 'green' }}-600 hover:text-{{ $publisher->status === 'active' ? 'yellow' : 'green' }}-900" title="{{ $publisher->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $publisher->status === 'active' ? 'pause' : 'play' }}"></i>
                                </button>
                                <button onclick="showPublisherAnalytics({{ $publisher->id }})" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300" title="Analytics">
                                    <i class="fas fa-chart-bar"></i>
                                </button>
                                <button onclick="deletePublisher({{ $publisher->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <div class="text-lg font-medium mb-2">No publishers found</div>
                                    <div class="text-sm">Add your first publisher to start tracking calls</div>
                                    <button onclick="openAddPublisherModal()" class="mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Add Publisher
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Publisher Modal -->
<div id="addPublisherModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New Publisher</h3>
            <button onclick="closeAddPublisherModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addPublisherForm" onsubmit="submitAddPublisher(event)">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Publisher Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone (Optional)</label>
                    <input type="tel" name="phone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company (Optional)</label>
                    <input type="text" name="company" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payout Rate</label>
                        <input type="number" name="payout_rate" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payout Type</label>
                        <select name="payout_type" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="per_call">Per Call</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Destination DID (Main Buyer)</label>
                    <input type="tel" name="destination_did" placeholder="8884444888" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <p class="text-xs text-gray-500 mt-1">The main buyer DID where calls will be forwarded</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tracking DID (Optional)</label>
                    <input type="tel" name="tracking_did" placeholder="8882323882" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to assign automatically from available DIDs</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
            </div>
            
            <div class="flex space-x-3 mt-6">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Publisher
                </button>
                <button type="button" onclick="closeAddPublisherModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddPublisherModal() {
    document.getElementById('addPublisherModal').classList.remove('hidden');
    document.getElementById('addPublisherModal').classList.add('flex');
}

function closeAddPublisherModal() {
    document.getElementById('addPublisherModal').classList.add('hidden');
    document.getElementById('addPublisherModal').classList.remove('flex');
    document.getElementById('addPublisherForm').reset();
}

async function submitAddPublisher(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    try {
        const response = await fetch('{{ route("admin.publishers.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeAddPublisherModal();
            location.reload(); // Refresh to show new publisher
        } else {
            alert('Error: ' + (result.message || 'Failed to add publisher'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to add publisher. Please try again.');
    }
}

async function togglePublisherStatus(publisherId) {
    try {
        const response = await fetch(`{{ route("admin.publishers.index") }}/${publisherId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload(); // Refresh to show updated status
        } else {
            alert('Error: ' + (result.message || 'Failed to update status'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update publisher status');
    }
}

async function deletePublisher(publisherId) {
    if (!confirm('Are you sure you want to delete this publisher? This will also release their tracking DID.')) {
        return;
    }
    
    try {
        const response = await fetch(`{{ route("admin.publishers.index") }}/${publisherId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload(); // Refresh to remove deleted publisher
        } else {
            alert('Error: ' + (result.message || 'Failed to delete publisher'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete publisher');
    }
}

function viewPublisher(publisherId) {
    // Redirect to publisher details view
    window.open(`{{ route("admin.publishers.index") }}/${publisherId}`, '_blank');
}

function editPublisher(publisherId) {
    // Redirect to publisher edit form
    window.location.href = `{{ route("admin.publishers.index") }}/${publisherId}/edit`;
}

function showPublisherAnalytics(publisherId) {
    // Open analytics in new tab
    window.open(`{{ route("admin.publishers.index") }}/${publisherId}/analytics`, '_blank');
}

function assignDid(publisherId) {
    const destinationDid = prompt('Enter destination DID (main buyer DID):');
    if (!destinationDid) return;
    
    const trackingDid = prompt('Enter tracking DID for this publisher (or leave empty for auto-assignment):');
    
    // Implementation for assigning DID would go here
    alert('DID assignment functionality will be implemented');
}
</script>
@endsection

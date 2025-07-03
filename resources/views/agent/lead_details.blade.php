@extends('layouts.agent')

@section('title', 'Lead Details')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('agent.leads.view') }}" class="p-2 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Lead Details</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $lead->first_name }} {{ $lead->last_name }}</p>
            </div>
        </div>
        <div class="flex space-x-3">
            @if($lead->canBeEdited())
                <a href="{{ route('agent.leads.edit', $lead) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Lead
                </a>
            @else
                <span class="bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed" title="Edit disabled after 30 minutes">
                    <i class="fas fa-edit mr-2"></i>Edit Disabled
                </span>
            @endif
            <form action="{{ route('agent.leads.destroy', $lead) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this lead?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lead Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-user text-blue-500 mr-3"></i>Basic Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->first_name }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->last_name }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <div class="text-sm text-gray-900 dark:text-white">
                            @if($lead->email)
                                <a href="mailto:{{ $lead->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $lead->email }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                        <div class="text-sm text-gray-900 dark:text-white">
                            @if($lead->phone)
                                @if($lead->canViewFullPhone())
                                    <a href="tel:{{ $lead->phone }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $lead->phone }}</a>
                                @else
                                    <span class="text-gray-600 dark:text-gray-400" title="Phone number masked after 30 minutes">{{ $lead->masked_phone }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">DID Number</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->did_number ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            @if($lead->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($lead->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @endif">
                            <i class="fas fa-{{ $lead->status === 'approved' ? 'check-circle' : ($lead->status === 'rejected' ? 'times-circle' : 'clock') }} mr-1"></i>
                            {{ ucfirst($lead->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-blue-500 mr-3"></i>Address Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Street Address</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->address ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->city ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">State</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->state ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ZIP Code</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->zip ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Campaign & Agent Information -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-bullhorn text-blue-500 mr-3"></i>Campaign & Agent Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Campaign</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->campaign->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Agent</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->agent_name ?? ($lead->user->name ?? 'N/A') }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Verifier</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->verifier_name ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Created</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->created_at->format('M d, Y g:i A') }}</div>
                    </div>
                </div>
            </div>

            @if($lead->notes)
            <!-- Notes -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-blue-500 mr-3"></i>Notes
                </h2>
                <div class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $lead->notes }}</div>
            </div>
            @endif
        </div>

        <!-- Tracking Information Sidebar -->
        <div class="space-y-6">
            <!-- Tracking Data -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-shield-alt text-green-500 mr-3"></i>Tracking Data
                </h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">IP Address</label>
                        <div class="text-sm text-gray-900 dark:text-white font-mono">{{ $lead->ip_address ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Jornaya Lead ID</label>
                        <div class="text-xs text-gray-900 dark:text-white font-mono">{{ $lead->jornaya_lead_id ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">TrustedForm Cert</label>
                        <div class="text-xs text-gray-900 dark:text-white font-mono">{{ $lead->trusted_form_cert_id ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Device Fingerprint</label>
                        <div class="text-xs text-gray-900 dark:text-white font-mono">{{ $lead->device_fingerprint ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Timezone</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $lead->timezone ?? '-' }}</div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('agent.leads.tracking-data', $lead) }}" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>Export Tracking Data
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-3"></i>Quick Actions
                </h2>
                <div class="space-y-2">
                    @if($lead->canBeEdited())
                        <a href="{{ route('agent.leads.edit', $lead) }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>Edit Lead
                        </a>
                    @else
                        <div class="w-full bg-gray-400 text-white px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed flex items-center justify-center" title="Edit disabled after 30 minutes">
                            <i class="fas fa-edit mr-2"></i>Edit Disabled
                        </div>
                    @endif
                    @if($lead->phone)
                        @if($lead->canViewFullPhone())
                            <a href="tel:{{ $lead->phone }}" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-phone mr-2"></i>Call Lead
                            </a>
                        @else
                            <div class="w-full bg-gray-400 text-white px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed flex items-center justify-center" title="Call disabled - phone number masked after 30 minutes">
                                <i class="fas fa-phone mr-2"></i>Call Disabled
                            </div>
                        @endif
                    @endif
                    @if($lead->email)
                    <a href="mailto:{{ $lead->email }}" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i>Email Lead
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

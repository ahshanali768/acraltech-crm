@extends('layouts.agent')

@section('title', 'My Leads')

@section('content')
<div class="p-6 space-y-6">
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

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Leads</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Manage and track your lead submissions</p>
        </div>
        <a href="{{ route('agent.leads.add') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-plus mr-2"></i>Add New Lead
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $leads->total() }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Leads</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $leads->where('status', 'approved')->count() }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Approved</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $leads->where('status', 'pending')->count() }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $leads->where('status', 'rejected')->count() }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Rejected</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leads Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-table text-blue-500 mr-3"></i>Recent Leads
                </h2>
                <div class="flex gap-3">
                    <button class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl transition-colors">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Lead</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Phone Number</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Campaign</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Verifier</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <!-- Date -->
                        <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                            <div>{{ $lead->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $lead->created_at->format('g:i A') }}</div>
                            @php
                                $minutesOld = $lead->created_at->diffInMinutes(now());
                                $timeLeft = 30 - $minutesOld;
                            @endphp
                            @if($lead->canBeEdited())
                                <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                    <i class="fas fa-clock mr-1"></i>{{ $timeLeft }}m left to edit
                                </div>
                            @else
                                <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                    <i class="fas fa-lock mr-1"></i>Edit locked
                                </div>
                            @endif
                        </td>
                        
                        <!-- Lead -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                                        <span class="text-xs font-bold text-white">{{ substr($lead->first_name, 0, 1) }}{{ substr($lead->last_name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $lead->first_name }} {{ $lead->last_name }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">{{ $lead->email ?? 'No email' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Phone Number -->
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                @if($lead->phone)
                                    @if($lead->canViewFullPhone())
                                        <a href="tel:{{ $lead->phone }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                            <i class="fas fa-phone mr-1"></i>{{ $lead->phone }}
                                        </a>
                                    @else
                                        <span class="text-gray-600 dark:text-gray-400" title="Phone number masked after 30 minutes">
                                            <i class="fas fa-phone mr-1"></i>{{ $lead->masked_phone }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Campaign -->
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $lead->campaign->name ?? 'N/A' }}</div>
                        </td>
                        
                        <!-- Agent -->
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $lead->agent_name ?? ($lead->user->name ?? 'N/A') }}
                            </div>
                        </td>
                        
                        <!-- Verifier -->
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $lead->verifier_name ?? '-' }}
                            </div>
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if($lead->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($lead->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @endif">
                                <i class="fas fa-{{ $lead->status === 'approved' ? 'check-circle' : ($lead->status === 'rejected' ? 'times-circle' : 'clock') }} mr-1"></i>
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('agent.leads.show', $lead) }}" class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-lg transition-all" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @php
                                    $canEdit = $lead->canBeEdited();
                                @endphp
                                @if($canEdit)
                                    <a href="{{ route('agent.leads.edit', $lead) }}" class="p-2 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-100 dark:hover:bg-green-900 rounded-lg transition-all" title="Edit Lead">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @else
                                    <span class="p-2 text-gray-400 dark:text-gray-600 cursor-not-allowed rounded-lg" title="Edit disabled after 30 minutes">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                @endif
                                <form action="{{ route('agent.leads.destroy', $lead) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition-all" title="Delete Lead">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-clipboard-list text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No leads found</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md">You haven't created any leads yet. Start building your pipeline by adding your first lead!</p>
                                <a href="{{ route('agent.leads.add') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-plus mr-2"></i>Add Your First Lead
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($leads->hasPages())
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-center">
            {{ $leads->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

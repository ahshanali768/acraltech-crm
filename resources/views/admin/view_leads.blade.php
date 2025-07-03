@extends('layouts.admin')

@section('title', 'View Leads')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lead Management</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage and track all your leads</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <button id="toggleFiltersBtn" type="button" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                onclick="toggleFilters()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Show Filters
            </button>
            <button type="button" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg text-sm font-medium transition-colors"
                onclick="document.getElementById('importLeadsModal').showModal()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Import Leads
            </button>
            <form method="GET" action="{{ route('admin.leads.export') }}" class="inline">
                @foreach(request()->except('page') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Leads
                </button>
            </form>
        </div>
    </div>

    <!-- Filters Panel -->
    <div id="filtersPanel" class="hidden">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <form id="filtersForm" method="GET" action="" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="date_range" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                    <select id="date_range" name="date_range" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Range</option>
                        <option value="today" @if(request('date_range')=='today') selected @endif>Today</option>
                        <option value="yesterday" @if(request('date_range')=='yesterday') selected @endif>Yesterday</option>
                        <option value="this_week" @if(request('date_range')=='this_week') selected @endif>This Week</option>
                        <option value="last_week" @if(request('date_range')=='last_week') selected @endif>Last Week</option>
                        <option value="this_month" @if(request('date_range')=='this_month') selected @endif>This Month</option>
                        <option value="last_month" @if(request('date_range')=='last_month') selected @endif>Last Month</option>
                        <option value="this_year" @if(request('date_range')=='this_year') selected @endif>This Year</option>
                        <option value="custom" @if(request('date_range')=='custom') selected @endif>Custom Range</option>
                    </select>
                </div>
                
                <div id="customRangeFields" style="display:@if(request('date_range')=='custom')''@else'none'@endif;" class="col-span-1 md:col-span-2 grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" id="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="pending" @if(request('status')=='pending') selected @endif>Pending</option>
                        <option value="approved" @if(request('status')=='approved') selected @endif>Approved</option>
                        <option value="rejected" @if(request('status')=='rejected') selected @endif>Rejected</option>
                    </select>
                </div>
                
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Agent Username</label>
                    <select name="username" id="username" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Agents</option>
                        @foreach($agentUsers as $user)
                            <option value="{{ $user->username }}" @if(request('username') == $user->username) selected @endif>
                                {{ $user->username }} ({{ $user->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="did_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">DID</label>
                    <select name="did_number" id="did_number" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All DIDs</option>
                        @foreach($didCampaigns as $did)
                            <option value="{{ $did->did }}" @if(request('did_number') == $did->did) selected @endif>
                                {{ $did->did }} @if($did->campaign_name) - {{ $did->campaign_name }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Number</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by Phone Number" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.view_leads') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>
    <!-- Import Leads Modal -->
    <dialog id="importLeadsModal" class="rounded-xl p-0 w-full max-w-2xl z-50">
        <form id="importLeadsForm" method="POST" action="{{ route('admin.leads.import.store') }}" class="bg-white dark:bg-gray-900 rounded-xl p-6 space-y-4">
            @csrf
            <h3 class="text-lg font-bold mb-2 text-primary dark:text-primary-dark">Bulk Update Lead Status</h3>
            <div>
                <label for="status" class="block font-medium mb-1">Set Status for Numbers</label>
                <select id="status" name="status" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" required>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div>
                <label for="numbers" class="block font-medium mb-1">Paste 10-digit Numbers (one per line or comma/space separated)</label>
                <textarea id="numbers" name="numbers" rows="8" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" placeholder="e.g. 9876543210\n9123456789\n..."></textarea>
            </div>            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('importLeadsModal').close()" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg text-sm font-medium transition-colors">Update Status</button>
            </div>
        </form>
    </dialog>
    <div class="flex flex-col items-center justify-center">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full mb-4 gap-4">
            <!-- Remove the old sort form and button completely -->
        </div>        <div class="overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="?{{ http_build_query(array_merge(request()->except('sort_field', 'sort', 'page'), ['sort_field' => 'created_at', 'sort' => request('sort_field') === 'created_at' && request('sort', 'az') === 'az' ? 'za' : 'az'])) }}" class="flex items-center gap-1">
                                Date
                                @if(request('sort_field') === 'created_at')
                                    <span>@if(request('sort', 'az') === 'az') &#9650; @else &#9660; @endif</span>
                                @else
                                    <span class="text-gray-300">&#9651;</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lead Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="?{{ http_build_query(array_merge(request()->except('sort_field', 'sort', 'page'), ['sort_field' => 'phone', 'sort' => request('sort_field') === 'phone' && request('sort', 'az') === 'az' ? 'za' : 'az'])) }}" class="flex items-center gap-1">
                                Phone
                                @if(request('sort_field') === 'phone')
                                    <span>@if(request('sort', 'az') === 'az') &#9650; @else &#9660; @endif</span>
                                @else
                                    <span class="text-gray-300">&#9651;</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="?{{ http_build_query(array_merge(request()->except('sort_field', 'sort', 'page'), ['sort_field' => 'did_number', 'sort' => request('sort_field') === 'did_number' && request('sort', 'az') === 'az' ? 'za' : 'az'])) }}" class="flex items-center gap-1">
                                DID
                                @if(request('sort_field') === 'did_number')
                                    <span>@if(request('sort', 'az') === 'az') &#9650; @else &#9660; @endif</span>
                                @else
                                    <span class="text-gray-300">&#9651;</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Campaign</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="?{{ http_build_query(array_merge(request()->except('sort_field', 'sort', 'page'), ['sort_field' => 'agent_name', 'sort' => request('sort_field') === 'agent_name' && request('sort', 'az') === 'az' ? 'za' : 'az'])) }}" class="flex items-center gap-1">
                                Agent
                                @if(request('sort_field') === 'agent_name')
                                    <span>@if(request('sort', 'az') === 'az') &#9650; @else &#9660; @endif</span>
                                @else
                                    <span class="text-gray-300">&#9651;</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="?{{ http_build_query(array_merge(request()->except('sort_field', 'sort', 'page'), ['sort_field' => 'verifier_name', 'sort' => request('sort_field') === 'verifier_name' && request('sort', 'az') === 'az' ? 'za' : 'az'])) }}" class="flex items-center gap-1">
                                Verifier
                                @if(request('sort_field') === 'verifier_name')
                                    <span>@if(request('sort', 'az') === 'az') &#9650; @else &#9660; @endif</span>
                                @else
                                    <span class="text-gray-300">&#9651;</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="?{{ http_build_query(array_merge(request()->except('sort_field', 'sort', 'page'), ['sort_field' => 'status', 'sort' => request('sort_field') === 'status' && request('sort', 'az') === 'az' ? 'za' : 'az'])) }}" class="flex items-center gap-1">
                                Status
                                @if(request('sort_field') === 'status')
                                    <span>@if(request('sort', 'az') === 'az') &#9650; @else &#9660; @endif</span>
                                @else
                                    <span class="text-gray-300">&#9651;</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $lead->created_at->format('M d, Y') }}<br>
                            <span class="text-xs text-gray-500">{{ $lead->created_at->format('H:i:s') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="{{ url('/multiavatar/' . md5(strtolower(trim($lead->first_name . $lead->last_name . $lead->email))) . '.svg') }}" alt="Avatar" class="w-10 h-10 rounded-full border-2 border-indigo-200 dark:border-indigo-600">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $lead->first_name }} {{ $lead->last_name }}</div>
                                    @if($lead->email)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $lead->email }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <span class="font-mono">{{ $lead->phone }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <span class="font-mono">{{ $lead->did_number }}</span>
                        </td>                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            @if($lead->campaign_id && $lead->campaign)
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                    {{ $lead->campaign->campaign_name }}
                                </span>
                            @elseif($lead->campaign && is_string($lead->campaign))
                                @php
                                    $campaignData = json_decode($lead->campaign, true);
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                    {{ $campaignData['campaign_name'] ?? 'Unknown' }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 rounded-full">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $lead->agent_name ?: 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $lead->verifier_name ?: 'N/A' }}
                        </td>                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($lead->status=='approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($lead->status=='rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" onclick="openEditLeadModal({{ $lead->id }})">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </button>
                        </td>
                    </tr>                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No leads found</h3>
                                <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or add some leads to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-6 gap-4">
            <div>
                Showing page <span class="font-semibold">{{ $leads->currentPage() }}</span> of <span class="font-semibold">{{ $leads->lastPage() }}</span>
            </div>
            <div class="flex items-center gap-2">
                @if($leads->onFirstPage())
                    <span class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-400">&larr;</span>
                @else
                    <a href="{{ $leads->previousPageUrl() }}{{ $leads->count() ? '&' : '?' }}per_page={{ $leads->perPage() }}" class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-primary hover:text-white">&larr;</a>
                @endif
                <span class="px-3 py-1">Page {{ $leads->currentPage() }}</span>
                @if($leads->hasMorePages())
                    <a href="{{ $leads->nextPageUrl() }}{{ $leads->count() ? '&' : '?' }}per_page={{ $leads->perPage() }}" class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-primary hover:text-white">&rarr;</a>
                @else
                    <span class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-400">&rarr;</span>
                @endif
            </div>
            <div>
                <form method="GET" action="" class="inline">
                    @foreach(request()->except('per_page', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label for="per_page_bottom" class="text-sm mr-2">Show</label>
                    <select name="per_page" id="per_page_bottom" class="rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700" onchange="this.form.submit()">
                        <option value="10" @if($leads->perPage()==10) selected @endif>10</option>
                        <option value="25" @if($leads->perPage()==25) selected @endif>25</option>
                        <option value="50" @if($leads->perPage()==50) selected @endif>50</option>
                        <option value="100" @if($leads->perPage()==100) selected @endif>100</option>
                    </select>
                    <span class="text-sm ml-2">entries per page</span>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Lead Modal -->
    <dialog id="editLeadModal" class="rounded-xl p-0 w-full max-w-2xl z-50">
        <form id="editLeadForm" method="POST" class="bg-white dark:bg-gray-900 rounded-xl p-6 space-y-4">
            @csrf
            @method('PUT')
            <h3 class="text-lg font-bold mb-2 text-primary dark:text-primary-dark">Edit Lead</h3>
            <input type="hidden" name="lead_id" id="edit_lead_id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_first_name" class="block font-medium mb-1">First Name</label>
                    <input type="text" id="edit_first_name" name="first_name" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_last_name" class="block font-medium mb-1">Last Name</label>
                    <input type="text" id="edit_last_name" name="last_name" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_phone" class="block font-medium mb-1">Phone</label>
                    <input type="text" id="edit_phone" name="phone" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_did_number" class="block font-medium mb-1">DID</label>
                    <input type="text" id="edit_did_number" name="did_number" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_email" class="block font-medium mb-1">Email</label>
                    <input type="email" id="edit_email" name="email" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_notes" class="block font-medium mb-1">Notes</label>
                    <input type="text" id="edit_notes" name="notes" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_address" class="block font-medium mb-1">Address</label>
                    <input type="text" id="edit_address" name="address" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_city" class="block font-medium mb-1">City</label>
                    <input type="text" id="edit_city" name="city" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_state" class="block font-medium mb-1">State</label>
                    <input type="text" id="edit_state" name="state" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_zip" class="block font-medium mb-1">ZIP</label>
                    <input type="text" id="edit_zip" name="zip" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_agent_name" class="block font-medium mb-1">Agent Name</label>
                    <input type="text" id="edit_agent_name" name="agent_name" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_verifier_name" class="block font-medium mb-1">Verifier Name</label>
                    <input type="text" id="edit_verifier_name" name="verifier_name" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_campaign" class="block font-medium mb-1">Campaign</label>
                    <input type="text" id="edit_campaign" name="campaign" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label for="edit_status" class="block font-medium mb-1">Status</label>
                    <select id="edit_status" name="status" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-between gap-2 mt-4">
                <button type="button" onclick="deleteLeadFromModal()" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-xl">Delete</button>
                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('editLeadModal').close()" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-xl">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-xl">Save</button>
                </div>
            </div>
        </form>
    </dialog>    <script>
    function toggleFilters() {
        var panel = document.getElementById('filtersPanel');
        var btn = document.getElementById('toggleFiltersBtn');
        if (panel.classList.contains('hidden')) {
            panel.classList.remove('hidden');
            btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Hide Filters';
        } else {
            panel.classList.add('hidden');
            btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>Show Filters';
        }
    }
    
    function openEditLeadModal(leadId) {
        fetch(`/admin/leads/${leadId}/json`)
            .then(res => res.json())
            .then(function(data) {
                document.getElementById('edit_lead_id').value = data.id;
                document.getElementById('edit_first_name').value = data.first_name || '';
                document.getElementById('edit_last_name').value = data.last_name || '';
                document.getElementById('edit_phone').value = data.phone || '';
                document.getElementById('edit_did_number').value = data.did_number || '';
                document.getElementById('edit_email').value = data.email || '';
                document.getElementById('edit_notes').value = data.notes || '';
                document.getElementById('edit_address').value = data.address || '';
                document.getElementById('edit_city').value = data.city || '';
                document.getElementById('edit_state').value = data.state || '';
                document.getElementById('edit_zip').value = data.zip || '';
                document.getElementById('edit_agent_name').value = data.agent_name || '';
                document.getElementById('edit_verifier_name').value = data.verifier_name || '';
                document.getElementById('edit_campaign').value = data.campaign || '';
                document.getElementById('edit_status').value = data.status || 'pending';
                document.getElementById('editLeadForm').action = `/admin/leads/${leadId}`;
                document.getElementById('editLeadModal').showModal();
            })
            .catch(error => {
                console.error('Error loading lead data:', error);
                alert('Error loading lead data. Please try again.');
            });
    }
    
    function deleteLeadFromModal() {
        if(confirm('Are you sure you want to delete this lead?')) {
            const leadId = document.getElementById('edit_lead_id').value;
            fetch(`/admin/leads/${leadId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    'Content-Type': 'application/json'
                }
            }).then(res => {
                if(res.ok) {
                    document.getElementById('editLeadModal').close();
                    location.reload();
                } else {
                    alert('Failed to delete lead.');
                }
            }).catch(error => {
                console.error('Error deleting lead:', error);
                alert('Error deleting lead. Please try again.');
            });
        }
    }
    
    // Date range handling
    document.getElementById('date_range').addEventListener('change', function() {
        const preset = this.value;
        const customFields = document.getElementById('customRangeFields');
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const today = new Date();
        let startDate, endDate;
        
        customFields.style.display = (preset === 'custom') ? 'block' : 'none';
        
        if (preset === 'today') {
            startDate = endDate = today.toISOString().slice(0,10);
        } else if (preset === 'yesterday') {
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            startDate = endDate = yesterday.toISOString().slice(0,10);
        } else if (preset === 'this_week') {
            const startOfWeek = new Date(today);
            const day = startOfWeek.getDay() || 7;
            startOfWeek.setDate(startOfWeek.getDate() - day + 1);
            startDate = startOfWeek.toISOString().slice(0,10);
            endDate = today.toISOString().slice(0,10);
        } else if (preset === 'last_week') {
            const endOfLastWeek = new Date(today);
            const day = endOfLastWeek.getDay() || 7;
            endOfLastWeek.setDate(endOfLastWeek.getDate() - day);
            const startOfLastWeek = new Date(endOfLastWeek);
            startOfLastWeek.setDate(startOfLastWeek.getDate() - 6);
            startDate = startOfLastWeek.toISOString().slice(0,10);
            endDate = endOfLastWeek.toISOString().slice(0,10);
        } else if (preset === 'this_month') {
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            startDate = startOfMonth.toISOString().slice(0,10);
            endDate = today.toISOString().slice(0,10);
        } else if (preset === 'last_month') {
            const startOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            startDate = startOfLastMonth.toISOString().slice(0,10);
            endDate = endOfLastMonth.toISOString().slice(0,10);
        } else if (preset === 'this_year') {
            const startOfYear = new Date(today.getFullYear(), 0, 1);
            startDate = startOfYear.toISOString().slice(0,10);
            endDate = today.toISOString().slice(0,10);
        } else if (preset === 'custom') {
            return; // Let user pick custom dates
        } else {
            startInput.value = '';
            endInput.value = '';
            return;
        }
        
        startInput.value = startDate;
        endInput.value = endDate;
    });
    
    // Show custom date fields if custom range is pre-selected
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('date_range').value === 'custom') {
            document.getElementById('customRangeFields').style.display = 'block';
        }
    });
    </script>
@endsection

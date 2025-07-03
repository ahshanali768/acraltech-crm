@extends('layouts.admin')

@section('title', 'Import Leads')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Import Leads</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload a CSV file to bulk import leads</p>
        </div>
        <a href="{{ route('admin.view_leads') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Leads
        </a>
    </div>

    <!-- Import Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <form method="POST" action="{{ route('admin.leads.import.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- File Upload -->
                <div>
                    <label for="leads_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select CSV File
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-primary-500 transition-colors">
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <div>
                                <input type="file" id="leads_file" name="leads_file" accept=".csv" required class="hidden">
                                <label for="leads_file" class="cursor-pointer text-primary-600 hover:text-primary-500 font-medium">
                                    Click to upload
                                </label>
                                <span class="text-gray-500"> or drag and drop</span>
                            </div>
                            <p class="text-xs text-gray-500">CSV files only (MAX. 10MB)</p>
                        </div>
                    </div>
                    @error('leads_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Import Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="skip_duplicates" class="flex items-center">
                            <input type="checkbox" id="skip_duplicates" name="skip_duplicates" value="1" checked class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Skip duplicate leads</span>
                        </label>
                    </div>
                    <div>
                        <label for="update_existing" class="flex items-center">
                            <input type="checkbox" id="update_existing" name="update_existing" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Update existing leads</span>
                        </label>
                    </div>
                </div>

                <!-- Default Campaign -->
                <div>
                    <label for="default_campaign" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Default Campaign (if not specified in CSV)
                    </label>
                    <select id="default_campaign" name="default_campaign" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select a campaign...</option>
                        @foreach(\App\Models\Campaign::all() as $campaign)
                            <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="window.history.back()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-upload mr-2"></i>
                        Import Leads
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
        <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-3">
            <i class="fas fa-info-circle mr-2"></i>
            CSV Format Instructions
        </h3>
        <div class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
            <p>Your CSV file should include the following columns:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>first_name</strong> - Lead's first name (required)</li>
                <li><strong>last_name</strong> - Lead's last name (required)</li>
                <li><strong>email</strong> - Lead's email address</li>
                <li><strong>phone</strong> - Lead's phone number (required)</li>
                <li><strong>address</strong> - Lead's street address</li>
                <li><strong>city</strong> - Lead's city</li>
                <li><strong>state</strong> - Lead's state</li>
                <li><strong>zip</strong> - Lead's ZIP code</li>
                <li><strong>campaign_id</strong> - Campaign ID (optional if default selected)</li>
                <li><strong>notes</strong> - Additional notes</li>
            </ul>
            <p class="mt-3 font-medium">Make sure the first row contains column headers exactly as listed above.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('leads_file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        const label = document.querySelector('label[for="leads_file"]');
        label.innerHTML = `<i class="fas fa-file-csv mr-2"></i>${fileName}`;
    }
});
</script>
@endpush
@endsection

@extends('layouts.auth-fullwidth')
@section('title', 'Account Pending Approval - Acraltech Solutions')
@section('panel-title', 'Almost There!')
@section('panel-description', 'Your email has been verified successfully. Your account is now pending admin approval. You\'ll receive an email notification once your account is reviewed.')
@section('content')
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="mx-auto w-20 h-20 bg-gradient-to-r from-amber-500 to-orange-600 rounded-full flex items-center justify-center mb-6">
            <span class="material-icons text-white text-3xl">hourglass_top</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3 font-display">Email Verified Successfully!</h1>
        <div class="inline-flex items-center px-4 py-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-sm font-medium mb-4">
            <span class="material-icons text-sm mr-2">check_circle</span>
            Email Verification Complete
        </div>
    </div>

    <!-- Status Card -->
    <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-700 rounded-xl p-8 mb-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-amber-800 dark:text-amber-200 mb-3">
                Account Pending Admin Approval
            </h2>
            <p class="text-amber-700 dark:text-amber-300 text-lg">
                Your registration is almost complete! Please wait while our administrators review and approve your account.
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-6 mb-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-icons text-white">verified_user</span>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Step 1: Complete</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Email Verified</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-3 animate-pulse">
                    <span class="material-icons text-white">pending</span>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Step 2: In Progress</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Admin Review</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-icons text-gray-500">login</span>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Step 3: Pending</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Account Access</p>
            </div>
        </div>
    </div>

    <!-- What Happens Next -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
            <span class="material-icons text-blue-500 mr-2">info</span>
            What Happens Next?
        </h3>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3 mt-0.5">
                    <span class="text-blue-600 dark:text-blue-400 text-sm font-bold">1</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Admin Notification Sent</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Our administrators have been notified of your registration and will review your account.</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3 mt-0.5">
                    <span class="text-blue-600 dark:text-blue-400 text-sm font-bold">2</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Account Review Process</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Your application will be reviewed within 24-48 hours during business days.</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3 mt-0.5">
                    <span class="text-blue-600 dark:text-blue-400 text-sm font-bold">3</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Email Notification</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">You'll receive an email notification with your account status and next steps.</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3 mt-0.5">
                    <span class="text-blue-600 dark:text-blue-400 text-sm font-bold">4</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Account Activation</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Once approved, you can log in and start using all platform features.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-6 mb-8">
        <h3 class="text-lg font-bold text-blue-800 dark:text-blue-200 mb-3 flex items-center">
            <span class="material-icons mr-2">tips_and_updates</span>
            Important Notes
        </h3>
        <ul class="space-y-2 text-blue-700 dark:text-blue-300">
            <li class="flex items-start">
                <span class="material-icons text-sm mr-2 mt-0.5">check</span>
                <span>No further action is required from you at this time</span>
            </li>
            <li class="flex items-start">
                <span class="material-icons text-sm mr-2 mt-0.5">check</span>
                <span>You will receive email notifications about your account status</span>
            </li>
            <li class="flex items-start">
                <span class="material-icons text-sm mr-2 mt-0.5">check</span>
                <span>Keep this browser tab open or bookmark this page for future reference</span>
            </li>
            <li class="flex items-start">
                <span class="material-icons text-sm mr-2 mt-0.5">check</span>
                <span>Check your email (including spam folder) for approval notifications</span>
            </li>
        </ul>
    </div>

    <!-- Account Details -->
    <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Registration Details</h3>
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Email:</span>
                <span class="text-gray-900 dark:text-white ml-2">{{ session('registration_email') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Registration Date:</span>
                <span class="text-gray-900 dark:text-white ml-2">{{ now()->format('F j, Y') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Status:</span>
                <span class="inline-flex items-center px-2 py-1 bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300 rounded-md text-xs font-medium ml-2">
                    <span class="w-2 h-2 bg-amber-500 rounded-full mr-1"></span>
                    Pending Approval
                </span>
            </div>
            <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Default Role:</span>
                <span class="text-gray-900 dark:text-white ml-2">Agent</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="space-y-4">
        <!-- Back to Home -->
        <a href="{{ route('welcome') }}" 
           class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center gap-2">
            <span class="material-icons">home</span>
            Return to Homepage
        </a>
        
        <!-- Check Status -->
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Need to check your approval status?</p>
            <a href="{{ route('login') }}" 
               class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-semibold transition-colors">
                Try to Login →
            </a>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="mt-8 text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Have questions about the approval process? 
            <a href="mailto:support@{{ parse_url(config('app.url'), PHP_URL_HOST) }}" 
               class="text-blue-600 dark:text-blue-400 hover:underline">
                Contact Support
            </a>
        </p>
    </div>
@endsection

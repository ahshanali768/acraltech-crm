@extends('layouts.auth-fullwidth')
@section('title', 'Sign In - Acraltech Solutions')
@section('panel-title', 'Welcome Back!')
@section('panel-description', 'Sign in to access your lead generation dashboard. Track performance, manage campaigns, and grow your business with real-time analytics.')
@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">
        <!-- Session Status -->
        <x-auth-session-status class="mb-6" :status="session('status')" />
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 font-display">Sign In</h1>
            <p class="text-gray-600 dark:text-gray-400">Welcome back! Please sign in to continue</p>
        </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="loginForm()" @submit="handleSubmit">
        @csrf

        <!-- Email Address or Username -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Email or Username
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-lg">person</span>
                </div>
                <input id="email" 
                       class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('email') border-red-300 dark:border-red-500 @enderror" 
                       type="text" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Enter your email or username"
                       required 
                       autofocus 
                       autocomplete="username">
            </div>
            @error('email')
                <p class="text-red-500 text-sm flex items-center gap-1">
                    <span class="material-icons text-xs">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-lg">lock</span>
                </div>
                <input id="password" 
                       class="w-full pl-12 pr-12 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('password') border-red-300 dark:border-red-500 @enderror" 
                       type="password" 
                       name="password" 
                       placeholder="Enter your password"
                       required 
                       autocomplete="current-password">
                <button type="button" 
                        @click="showPassword = !showPassword; document.getElementById('password').type = showPassword ? 'text' : 'password'"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span x-show="!showPassword" class="material-icons text-lg">visibility</span>
                    <span x-show="showPassword" class="material-icons text-lg">visibility_off</span>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-sm flex items-center gap-1">
                    <span class="material-icons text-xs">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center space-x-2 cursor-pointer">
                <input id="remember_me" 
                       type="checkbox" 
                       class="w-4 h-4 text-purple-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-purple-500 dark:focus:ring-purple-400" 
                       name="remember">
                <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium transition-colors" 
                   href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center gap-2">
            <span x-show="!isLoading">Sign In</span>
            <span x-show="isLoading" class="flex items-center gap-2">
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                Signing in...
            </span>
        </button>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400">Don't have an account?</span>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center">
            <a href="{{ route('register') }}" 
               class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-semibold transition-colors">
                Create your account
            </a>
        </div>
    </form>

    @push('scripts')
    <script>
        function loginForm() {
            return {
                isLoading: false,
                showPassword: false,
                handleSubmit(event) {
                    this.isLoading = true;
                    // The form will submit normally, this just shows loading state
                }
            }
        }
    </script>
    @endpush
    </div>
</div>
@endsection

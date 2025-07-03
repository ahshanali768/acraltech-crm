@extends('layouts.auth-fullwidth')
@section('title', 'Create Account - Acraltech Solutions')
@section('panel-title', 'Join Our Platform!')
@section('panel-description', 'Create your account to start generating high-quality leads, manage campaigns, and track performance with our advanced analytics platform.')
@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 font-display">Create Account</h1>
            <p class="text-gray-600 dark:text-gray-400">Fill in your details to get started</p>
        </div>

    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-6" x-data="registerForm()" @submit="handleSubmit">
        @csrf

        <!-- Full Name -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Full Name
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-lg">person</span>
                </div>
                <input id="name" 
                       class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('name') border-red-300 dark:border-red-500 @enderror" 
                       type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
                       placeholder="Enter your full name"
                       required 
                       autofocus 
                       autocomplete="name">
            </div>
            @error('name')
                <p class="text-red-500 text-sm flex items-center gap-1">
                    <span class="material-icons text-xs">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Phone Number -->
        <div class="space-y-2">
            <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Phone Number
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-lg">phone</span>
                </div>
                <input id="phone" 
                       class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('phone') border-red-300 dark:border-red-500 @enderror" 
                       type="tel" 
                       name="phone" 
                       value="{{ old('phone') }}" 
                       placeholder="Enter your phone number (optional)"
                       autocomplete="tel">
            </div>
            @error('phone')
                <p class="text-red-500 text-sm flex items-center gap-1">
                    <span class="material-icons text-xs">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-lg">email</span>
                </div>
                <input id="email" 
                       class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('email') border-red-300 dark:border-red-500 @enderror" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Enter your email address"
                       required 
                       autocomplete="email">
            </div>
            @error('email')
                <p class="text-red-500 text-sm flex items-center gap-1">
                    <span class="material-icons text-xs">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Username -->
        <div class="space-y-2">
            <label for="username" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Username
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-lg">alternate_email</span>
                </div>
                <input id="username" 
                       class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('username') border-red-300 dark:border-red-500 @enderror" 
                       type="text" 
                       name="username" 
                       value="{{ old('username') }}" 
                       placeholder="Choose a username"
                       required 
                       autocomplete="username">
            </div>
            @error('username')
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
                       placeholder="Create a secure password"
                       required 
                       autocomplete="new-password"
                       @input="checkPasswordStrength($event.target.value)">
                <button type="button" 
                        @click="showPassword = !showPassword; document.getElementById('password').type = showPassword ? 'text' : 'password'"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span x-show="!showPassword" class="material-icons text-lg">visibility</span>
                    <span x-show="showPassword" class="material-icons text-lg">visibility_off</span>
                </button>
            </div>
            <!-- Password Strength Indicator -->
            <div x-show="passwordStrength.show" class="mt-2">
                <div class="flex items-center gap-2 mb-1">
                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full transition-all duration-300" 
                             :class="passwordStrength.color" 
                             :style="`width: ${passwordStrength.width}%`"></div>
                    </div>
                    <span class="text-xs font-medium" :class="passwordStrength.textColor" x-text="passwordStrength.text"></span>
                </div>
            </div>
            @error('password')
                <p class="text-red-500 text-sm flex items-center gap-1">
                    <span class="material-icons text-xs">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Confirm Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-lg">lock_outline</span>
                </div>
                <input id="password_confirmation" 
                       class="w-full pl-12 pr-12 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('password_confirmation') border-red-300 dark:border-red-500 @enderror" 
                       type="password" 
                       name="password_confirmation" 
                       placeholder="Confirm your password"
                       required 
                       autocomplete="new-password">
                <button type="button" 
                        @click="showConfirmPassword = !showConfirmPassword; document.getElementById('password_confirmation').type = showConfirmPassword ? 'text' : 'password'"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span x-show="!showConfirmPassword" class="material-icons text-lg">visibility</span>
                    <span x-show="showConfirmPassword" class="material-icons text-lg">visibility_off</span>
                </button>
            </div>
            @error('password_confirmation')
                <p class="text-red-500 text-sm flex items-center gap-1">
                    <span class="material-icons text-xs">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center gap-2">
            <span x-show="!isLoading">Create Account</span>
            <span x-show="isLoading" class="flex items-center gap-2">
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                Creating account...
            </span>
        </button>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400">Already have an account?</span>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" 
               class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-semibold transition-colors">
                Sign in to your account
            </a>
        </div>
    </form>

    @push('scripts')
    <script>
        function registerForm() {
            return {
                isLoading: false,
                showPassword: false,
                showConfirmPassword: false,
                passwordStrength: {
                    show: false,
                    width: 0,
                    color: '',
                    textColor: '',
                    text: ''
                },
                
                handleSubmit(event) {
                    this.isLoading = true;
                    // The form will submit normally, this just shows loading state
                },
                
                checkPasswordStrength(password) {
                    if (!password) {
                        this.passwordStrength.show = false;
                        return;
                    }
                    
                    this.passwordStrength.show = true;
                    let score = 0;
                    
                    // Length check
                    if (password.length >= 8) score += 25;
                    
                    // Uppercase check
                    if (/[A-Z]/.test(password)) score += 25;
                    
                    // Lowercase check
                    if (/[a-z]/.test(password)) score += 25;
                    
                    // Number or special character check
                    if (/[\d\W]/.test(password)) score += 25;
                    
                    this.passwordStrength.width = score;

                    if (score < 50) {
                        this.passwordStrength.color = 'bg-red-500';
                        this.passwordStrength.textColor = 'text-red-600 dark:text-red-400';
                        this.passwordStrength.text = 'Weak';
                    } else if (score < 75) {
                        this.passwordStrength.color = 'bg-yellow-500';
                        this.passwordStrength.textColor = 'text-yellow-600 dark:text-yellow-400';
                        this.passwordStrength.text = 'Fair';
                    } else if (score < 100) {
                        this.passwordStrength.color = 'bg-blue-500';
                        this.passwordStrength.textColor = 'text-blue-600 dark:text-blue-400';
                        this.passwordStrength.text = 'Good';
                    } else {
                        this.passwordStrength.color = 'bg-green-500';
                        this.passwordStrength.textColor = 'text-green-600 dark:text-green-400';
                        this.passwordStrength.text = 'Strong';
                    }
                }
            }
        }
    </script>
    @endpush
    </div>
</div>
@endsection

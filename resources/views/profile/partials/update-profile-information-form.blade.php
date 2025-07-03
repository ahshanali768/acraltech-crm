<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-user text-gray-400"></i>Full Name *
                </label>
                <input id="name" name="name" type="text" 
                       class="form-input @error('name') border-red-500 @enderror" 
                       value="{{ old('name', $user->name) }}" 
                       required autofocus autocomplete="name" 
                       placeholder="Enter your full name">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope text-gray-400"></i>Email Address *
                </label>
                <input id="email" name="email" type="email" 
                       class="form-input @error('email') border-red-500 @enderror" 
                       value="{{ old('email', $user->email) }}" 
                       required autocomplete="username" 
                       placeholder="Enter your email address">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-xl">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    Your email address is unverified.
                                </p>
                                <button form="send-verification" 
                                        class="mt-2 text-sm text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200 underline font-medium">
                                    Click here to re-send the verification email
                                </button>
                            </div>
                        </div>

                        @if (session('status') === 'verification-link-sent')
                            <div class="mt-3 p-3 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg">
                                <p class="text-sm text-green-800 dark:text-green-200 flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    A new verification link has been sent to your email address.
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
            <div>
                @if (session('status') === 'profile-updated')
                    <p class="flex items-center text-green-600 dark:text-green-400 text-sm font-medium"
                       x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 3000)">
                        <i class="fas fa-check-circle mr-2"></i>
                        Profile updated successfully!
                    </p>
                @endif
            </div>
            
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </form>
</section>

<style>
.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.dark .form-label {
    color: #d1d5db;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #d1d5db;
    background-color: white;
    color: #111827;
    transition: all 0.2s;
}

.dark .form-input {
    border-color: #4b5563;
    background-color: #1f2937;
    color: #f9fafb;
}

.form-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px #3b82f6;
    border-color: #3b82f6;
}
</style>

<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-6">
            <div class="form-group">
                <label for="update_password_current_password" class="form-label">
                    <i class="fas fa-key text-gray-400"></i>Current Password *
                </label>
                <input id="update_password_current_password" name="current_password" type="password" 
                       class="form-input @error('current_password', 'updatePassword') border-red-500 @enderror" 
                       autocomplete="current-password" 
                       placeholder="Enter your current password">
                @error('current_password', 'updatePassword')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="update_password_password" class="form-label">
                        <i class="fas fa-lock text-gray-400"></i>New Password *
                    </label>
                    <input id="update_password_password" name="password" type="password" 
                           class="form-input @error('password', 'updatePassword') border-red-500 @enderror" 
                           autocomplete="new-password" 
                           placeholder="Enter new password">
                    @error('password', 'updatePassword')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="update_password_password_confirmation" class="form-label">
                        <i class="fas fa-lock text-gray-400"></i>Confirm Password *
                    </label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                           class="form-input @error('password_confirmation', 'updatePassword') border-red-500 @enderror" 
                           autocomplete="new-password" 
                           placeholder="Confirm new password">
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>Password Requirements
                </h4>
                <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                    <li class="flex items-center"><i class="fas fa-check-circle text-blue-500 mr-2"></i>At least 8 characters long</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-blue-500 mr-2"></i>Include uppercase and lowercase letters</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-blue-500 mr-2"></i>Include at least one number</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-blue-500 mr-2"></i>Include at least one special character</li>
                </ul>
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
            <div>
                @if (session('status') === 'password-updated')
                    <p class="flex items-center text-green-600 dark:text-green-400 text-sm font-medium"
                       x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 3000)">
                        <i class="fas fa-check-circle mr-2"></i>
                        Password updated successfully!
                    </p>
                @endif
            </div>
            
            <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-shield-alt mr-2"></i>Update Password
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
    box-shadow: 0 0 0 2px #10b981;
    border-color: #10b981;
}
</style>

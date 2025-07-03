<section class="space-y-6">
    <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-2">Danger Zone</h3>
                <p class="text-sm text-red-700 dark:text-red-300 mb-4">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                </p>
                <button type="button" 
                        onclick="document.getElementById('deleteAccountModal').showModal()"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-trash-alt mr-2"></i>Delete Account
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <dialog id="deleteAccountModal" class="rounded-2xl p-0 w-full max-w-md backdrop:bg-black backdrop:bg-opacity-50">
        <form method="post" action="{{ route('profile.destroy') }}" class="bg-white dark:bg-gray-800 rounded-2xl p-8 space-y-6">
            @csrf
            @method('delete')

            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Delete Account</h2>
                <p class="text-gray-600 dark:text-gray-400">Are you sure you want to delete your account?</p>
            </div>

            <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-xl p-4">
                <p class="text-sm text-red-800 dark:text-red-200">
                    <i class="fas fa-warning mr-2"></i>
                    <strong>This action cannot be undone.</strong> All of your data, including leads, campaigns, and account information will be permanently deleted.
                </p>
            </div>

            <div class="form-group">
                <label for="password" class="form-label text-red-600 dark:text-red-400">
                    <i class="fas fa-key text-red-500"></i>Enter your password to confirm
                </label>
                <input id="password" name="password" type="password" 
                       class="form-input border-red-300 dark:border-red-600 focus:border-red-500 focus:ring-red-500 @error('password', 'userDeletion') border-red-500 @enderror" 
                       placeholder="Enter your password to confirm deletion" required>
                @error('password', 'userDeletion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                <button type="button" onclick="document.getElementById('deleteAccountModal').close()" 
                        class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-trash-alt mr-2"></i>Delete Account
                </button>
            </div>
        </form>
    </dialog>
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
    box-shadow: 0 0 0 2px #ef4444;
    border-color: #ef4444;
}

dialog::backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

dialog {
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
</style>

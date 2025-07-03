<div x-data="userManagement()" x-init="loadUsers()">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900">User Management</h3>
            <p class="text-sm text-gray-500">Manage system users and their permissions</p>
        </div>
        <button @click="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add User
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="user in users" :key="user.id">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900" x-text="user.name"></div>
                                    <div class="text-sm text-gray-500" x-text="user.email"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="user.phone || 'N/A'"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="user.username"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="user.plain_password || '••••••••'"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="{
                                      'bg-purple-100 text-purple-800': user.role === 'admin',
                                      'bg-green-100 text-green-800': user.role === 'agent',
                                      'bg-blue-100 text-blue-800': user.role === 'publisher'
                                  }"
                                  x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)">
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <template x-if="user.approval_status === 'approved'">
                                <select @change="changeUserStatus(user.id, $event.target.value)" 
                                        :value="user.status || 'active'"
                                        class="text-xs px-2 py-1 rounded-full border-0 focus:ring-2 focus:ring-blue-500"
                                        :class="{
                                            'bg-green-100 text-green-800': (user.status || 'active') === 'active',
                                            'bg-red-100 text-red-800': user.status === 'revoked'
                                        }">
                                    <option value="active">Active</option>
                                    <option value="revoked">Revoked</option>
                                </select>
                            </template>
                            <template x-if="user.approval_status !== 'approved'">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      :class="{
                                          'bg-yellow-100 text-yellow-800': user.approval_status === 'pending',
                                          'bg-red-100 text-red-800': user.approval_status === 'rejected'
                                      }"
                                      x-text="user.approval_status === 'pending' ? 'Pending' : 'Rejected'">
                                </span>
                            </template>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <template x-if="user.approval_status === 'pending'">
                                <div class="flex space-x-2">
                                    <button @click="openApprovalModal(user, 'approve')" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button @click="openApprovalModal(user, 'reject')" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </template>
                            <template x-if="user.approval_status !== 'pending'">
                                <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </template>
                        </td>
                    </tr>
                </template>
                <template x-if="users.length === 0">
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No users found</p>
                                <p class="text-sm">Get started by adding your first user.</p>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Add User Modal -->
    <div x-show="showModal" x-transition class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New User</h3>
                
                <form @submit.prevent="submitForm()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" x-model="formData.name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" x-model="formData.username" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" x-model="formData.email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" x-model="formData.phone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select x-model="formData.role" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="agent">Agent</option>
                            <option value="admin">Admin</option>
                            <option value="publisher">Publisher</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" x-model="formData.password" required minlength="6"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Minimum 6 characters">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showModal = false" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" :disabled="loading"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
                            <span x-show="!loading">Add User</span>
                            <span x-show="loading">Creating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Approval/Rejection Modal -->
    <div x-show="showApprovalModal" x-transition class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" 
                    x-text="approvalType === 'approve' ? 'Approve User' : 'Reject User'"></h3>
                
                <div class="mb-4" x-show="approvalUser">
                    <p class="text-sm text-gray-600" x-text="'User: ' + (approvalUser ? approvalUser.name : '')"></p>
                    <p class="text-sm text-gray-600" x-text="'Email: ' + (approvalUser ? approvalUser.email : '')"></p>
                </div>
                
                <form @submit.prevent="submitApproval()">
                    <div class="mb-4" x-show="approvalType === 'approve'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign Role</label>
                        <select x-model="approvalData.role" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="agent">Agent</option>
                            <option value="admin">Admin</option>
                            <option value="publisher">Publisher</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span x-text="approvalType === 'approve' ? 'Notes (optional)' : 'Rejection Reason'"></span>
                        </label>
                        <textarea x-model="approvalData.notes" 
                                  :required="approvalType === 'reject'"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showApprovalModal = false" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" :disabled="loading"
                                :class="approvalType === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
                                class="px-4 py-2 text-sm font-medium text-white rounded-md disabled:opacity-50">
                            <span x-show="!loading" x-text="approvalType === 'approve' ? 'Approve' : 'Reject'"></span>
                            <span x-show="loading">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function userManagement() {
    return {
        users: [],
        showModal: false,
        showApprovalModal: false,
        modalType: 'add',
        editingUser: null,
        approvalUser: null,
        approvalType: 'approve',
        formData: {
            name: '',
            username: '',
            email: '',
            phone: '',
            role: 'agent',
            password: ''
        },
        approvalData: {
            role: 'agent',
            notes: ''
        },
        loading: false,
        
        resetForm() {
            this.formData = {
                name: '',
                username: '',
                email: '',
                phone: '',
                role: 'agent',
                password: ''
            };
            this.editingUser = null;
        },
        
        resetApprovalForm() {
            this.approvalData = {
                role: 'agent',
                notes: ''
            };
            this.approvalUser = null;
        },
        
        openAddModal() {
            this.modalType = 'add';
            this.resetForm();
            this.showModal = true;
        },
        
        openApprovalModal(user, type) {
            this.approvalType = type;
            this.approvalUser = user;
            this.resetApprovalForm();
            this.showApprovalModal = true;
        },
        
        submitForm() {
            this.loading = true;
            const url = '/admin/manage_users';
            
            // Validate required fields client-side
            if (!this.formData.name?.trim()) {
                this.showErrorMessage('Name is required');
                this.loading = false;
                return;
            }
            if (!this.formData.username?.trim()) {
                this.showErrorMessage('Username is required');
                this.loading = false;
                return;
            }
            if (!this.formData.email?.trim()) {
                this.showErrorMessage('Email is required');
                this.loading = false;
                return;
            }
            if (!this.formData.password?.trim()) {
                this.showErrorMessage('Password is required');
                this.loading = false;
                return;
            }
            
            const formData = new FormData();
            
            // Only append non-empty values
            if (this.formData.name?.trim()) formData.append('name', this.formData.name.trim());
            if (this.formData.username?.trim()) formData.append('username', this.formData.username.trim());
            if (this.formData.email?.trim()) formData.append('email', this.formData.email.trim());
            if (this.formData.phone?.trim()) formData.append('phone', this.formData.phone.trim());
            if (this.formData.password?.trim()) formData.append('password', this.formData.password.trim());
            if (this.formData.role?.trim()) formData.append('role', this.formData.role.trim());
            
            console.log('Submitting form data:', Object.fromEntries(formData));
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                if (!response.ok) {
                    // Log the response text for debugging
                    return response.text().then(text => {
                        console.error('Error response text:', text);
                        try {
                            const errorData = JSON.parse(text);
                            throw errorData;
                        } catch (e) {
                            throw { message: 'Server error: ' + response.status + ' - ' + text };
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    this.showModal = false;
                    this.resetForm();
                    console.log('About to reload users...');
                    this.loadUsers();
                    this.showSuccessMessage(data.message);
                } else {
                    this.showErrorMessage(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.errors) {
                    // Handle validation errors
                    let errorMessage = 'Validation errors:\n';
                    Object.keys(error.errors).forEach(field => {
                        errorMessage += `${field}: ${error.errors[field].join(', ')}\n`;
                    });
                    this.showErrorMessage(errorMessage);
                } else {
                    this.showErrorMessage(error.message || 'An error occurred while creating user');
                }
            })
            .finally(() => {
                this.loading = false;
            });
        },
        
        submitApproval() {
            this.loading = true;
            const url = this.approvalType === 'approve' 
                ? `/admin/users/${this.approvalUser.id}/approve`
                : `/admin/users/${this.approvalUser.id}/reject`;
            
            const formData = new FormData();
            if (this.approvalType === 'approve') {
                formData.append('role', this.approvalData.role);
            }
            formData.append('notes', this.approvalData.notes || '');
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showApprovalModal = false;
                    this.resetApprovalForm();
                    this.loadUsers();
                    this.showSuccessMessage(data.message);
                } else {
                    this.showErrorMessage(data.error || 'An error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showErrorMessage('An error occurred');
            })
            .finally(() => {
                this.loading = false;
            });
        },
        
        deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/admin/manage_users/${userId}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.loadUsers();
                        this.showSuccessMessage(data.message);
                    } else {
                        this.showErrorMessage(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.showErrorMessage('An error occurred while deleting user');
                });
            }
        },
        
        changeUserStatus(userId, newStatus) {
            fetch(`/admin/manage_users/${userId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.loadUsers();
                    this.showSuccessMessage(data.message);
                } else {
                    this.showErrorMessage(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showErrorMessage('An error occurred while updating user status');
                // Reload users to reset the dropdown to the original value
                this.loadUsers();
            });
        },
        
        loadUsers() {
            console.log('Loading users...');
            fetch('/admin/manage_users', {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Users response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Users data received:', data);
                this.users = data.users || [];
                console.log('Users array updated:', this.users);
            })
            .catch(error => {
                console.error('Error loading users:', error);
            });
        },
        
        showSuccessMessage(message) {
            // You can implement a toast notification system here
            alert(message);
        },
        
        showErrorMessage(message) {
            alert(message);
        }
    }
}
</script>

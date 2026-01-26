@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<!-- Header Actions -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Users</h2>
        <p class="text-sm text-gray-600 mt-1">Manage user accounts, roles, and permissions</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-user-plus mr-2"></i>
            Add User
        </a>
        <button onclick="window.location.href='{{ route('admin.users.export') }}'" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            Export CSV
        </button>
        <div class="relative">
            <button onclick="document.getElementById('bulkActionsDropdown').classList.toggle('hidden')" 
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-cogs mr-2"></i>
                Bulk Actions
                <i class="fas fa-chevron-down ml-2"></i>
            </button>
            <!-- Dropdown Menu -->
            <div id="bulkActionsDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                <a href="#" onclick="bulkRoleUpdate('admin')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-shield mr-2 text-purple-600"></i>
                    Assign Admin Role
                </a>
                <a href="#" onclick="bulkRoleUpdate('manager')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-tie mr-2 text-blue-600"></i>
                    Assign Manager Role
                </a>
                <a href="#" onclick="bulkRoleUpdate('user')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user mr-2 text-green-600"></i>
                    Assign User Role
                </a>
                <div class="border-t border-gray-100"></div>
                <a href="#" onclick="bulkAction('activate')" class="block px-4 py-2 text-sm text-green-600 hover:bg-gray-100">
                    <i class="fas fa-check-circle mr-2"></i>
                    Activate Selected
                </a>
                <a href="#" onclick="bulkAction('deactivate')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                    <i class="fas fa-times-circle mr-2"></i>
                    Deactivate Selected
                </a>
                <a href="#" onclick="bulkAction('delete')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Selected
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search by name, email, or ID..." 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 pl-10">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Role Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                        <i class="fas fa-user-shield mr-2 text-purple-600"></i>
                        Administrator
                    </option>
                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>
                        <i class="fas fa-user-tie mr-2 text-blue-600"></i>
                        Manager
                    </option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>
                        <i class="fas fa-user mr-2 text-green-600"></i>
                        User
                    </option>
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                        Active
                    </option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                        <i class="fas fa-times-circle mr-2 text-red-600"></i>
                        Inactive
                    </option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>
                        <i class="fas fa-ban mr-2 text-orange-600"></i>
                        Suspended
                    </option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        <i class="fas fa-clock mr-2 text-yellow-600"></i>
                        Pending Verification
                    </option>
                </select>
            </div>
            
            <!-- Registration Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Registration Date</label>
                <select name="registration_period" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Time</option>
                    <option value="today" {{ request('registration_period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('registration_period') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('registration_period') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="quarter" {{ request('registration_period') == 'quarter' ? 'selected' : '' }}">This Quarter</option>
                    <option value="year" {{ request('registration_period') == 'year' ? 'selected' : '' }}">This Year</option>
                </select>
            </div>
        </div>
        
        <!-- Filter Actions -->
        <div class="flex items-center justify-between">
            <div class="flex space-x-3">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2.5 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Clear All
                </a>
            </div>
            
            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'role', 'status', 'registration_period']))
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Active:</span>
                    @if(request('search'))
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                            Search: "{{ request('search') }}"
                        </span>
                    @endif
                    @if(request('role'))
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                            {{ request('role') }}
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            {{ request('status') }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="space-y-4">
                                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">No users found</h3>
                                <p class="text-gray-500">Try adjusting your search or filters to see results.</p>
                            </div>
                        </td>
                    </tr>
                @empty
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="users[]" value="{{ $user->id }}" class="rounded border-gray-300">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($user->avatar)
                                        <img src="{{ asset($user->avatar) }}" 
                                             alt="{{ $user->name }}" 
                                             class="h-10 w-10 rounded-full object-cover mr-3">
                                    @else
                                        <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-600">@{{ $user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 
                                     ($user->role == 'manager' ? 'bg-blue-100 text-blue-800' : 
                                     'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($user->role ?? 'user') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 
                                     ($user->status == 'inactive' ? 'bg-red-100 text-red-800' : 
                                     ($user->status == 'suspended' ? 'bg-orange-100 text-orange-800' : 
                                     ($user->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                     'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($user->status ?? 'active') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y') : 'Never' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <button onclick="viewUser({{ $user->id }})" 
                                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                                            title="View User">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editUser({{ $user->id }})" 
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
                                            title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="toggleUserStatus({{ $user->id }})" 
                                            class="{{ $user->status == 'active' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }} p-1 rounded hover:bg-red-50"
                                            title="{{ $user->status == 'active' ? 'Deactivate User' : 'Activate User' }}"
                                            @if($user->id == auth()->id())
                                                disabled
                                            @endif>
                                        <i class="fas {{ $user->status == 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                    <button onclick="resetPassword({{ $user->id }})" 
                                            class="text-orange-600 hover:text-orange-900 p-1 rounded hover:bg-orange-50"
                                            title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button onclick="deleteUser({{ $user->id }})" 
                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                                            title="Delete User"
                                            @if($user->id == auth()->id())
                                                disabled
                                            @endif>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="bg-white px-6 py-3 flex items-center justify-between border-t border-gray-200">
        <div class="text-sm text-gray-600">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
        </div>
        {{ $users->links() }}
    </div>
</div>

<!-- User Details Modal -->
<div id="userDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex">
        <!-- Modal Header -->
        <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">User Details</h3>
            <button onclick="closeUserDetails()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto">
            <!-- User Info -->
            <div id="userInfoContent">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Form (Hidden) -->
<form id="bulkActionsForm" method="POST" action="{{ route('admin.users.bulkUpdate') }}" class="hidden">
    @csrf
    <input type="hidden" name="users" id="selectedUsers">
    <input type="hidden" name="action" id="bulkAction">
</form>

@section('scripts')
<script>
    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="users[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        updateSelectedUsers();
    });

    // Update selected users
    document.querySelectorAll('input[name="users[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedUsers);
    });

    function updateSelectedUsers() {
        const selected = Array.from(document.querySelectorAll('input[name="users[]"]:checked'))
            .map(cb => cb.value);
        document.getElementById('selectedUsers').value = JSON.stringify(selected);
    }

    // Bulk action handlers
    function bulkRoleUpdate(role) {
        document.getElementById('bulkAction').value = 'role_' + role;
        document.getElementById('bulkActionsForm').submit();
    }

    function bulkAction(action) {
        if (confirm(`Are you sure you want to ${action} selected users?`)) {
            document.getElementById('bulkAction').value = action;
            document.getElementById('bulkActionsForm').submit();
        }
    }

    // User management functions
    function viewUser(userId) {
        window.open(`{{ route('admin.users.show', ':id') }}`.replace(':id', userId), '_blank');
    }

    function editUser(userId) {
        window.location.href = `{{ route('admin.users.edit', ':id') }}`.replace(':id', userId);
    }

    function toggleUserStatus(userId) {
        fetch(`{{ route('admin.users.toggle', ':id') }}`.replace(':id', userId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error updating user: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error updating user: ' + error.message);
        });
    }

    function resetPassword(userId) {
        if (confirm('Are you sure you want to reset this user\'s password?')) {
            fetch(`{{ route('admin.users.resetPassword', ':id') }}`.replace(':id', userId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password reset email sent successfully!');
                } else {
                    alert('Error resetting password: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error resetting password: ' + error.message);
            });
        }
    }

    function deleteUser(userId) {
        if (confirm(`Are you sure you want to delete this user? This action cannot be undone.`)) {
            fetch(`{{ route('admin.users.destroy', ':id') }}`.replace(':id', userId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error deleting user: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error deleting user: ' + error.message);
            });
        }
    }

    // User details modal
    function showUserDetails(userId) {
        const modal = document.getElementById('userDetailsModal');
        const content = document.getElementById('userInfoContent');
        
        // Show loading state
        content.innerHTML = `
            <div class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-4xl text-indigo-600 mb-4"></i>
                <p class="text-gray-600">Loading user details...</p>
            </div>
        `;
        
        modal.classList.remove('hidden');
        
        // Fetch user details
        fetch(`{{ route('admin.users.show', ':id') }}`.replace(':id', userId), {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            content.innerHTML = data.html;
        })
        .catch(error => {
            content.innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600 mb-4"></i>
                    <p class="text-red-600">Error loading user details</p>
                    <p class="text-gray-500 text-sm">${error.message}</p>
                </div>
            `;
        });
    }

    function closeUserDetails() {
        const modal = document.getElementById('userDetailsModal');
        modal.classList.add('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('bulkActionsDropdown');
        const button = event.target.closest('button');
        if (!dropdown.contains(event.target) && !button) {
            dropdown.classList.add('hidden');
        }
    });

    // Close modal when clicking outside
    document.getElementById('userDetailsModal').addEventListener('click', function(event) {
        if (event.target === event.currentTarget) {
            closeUserDetails();
        }
    });
</script>
@endsection
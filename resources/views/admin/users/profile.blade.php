@extends('admin.layouts.app')

@section('title', 'User Profile')
@section('page-title', 'User Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">User Profile</h3>
        </div>
        
        <div class="p-6">
            <div class="flex items-center space-x-6 mb-6">
                <div class="w-20 h-20 bg-indigo-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ ucfirst($user->role ?? 'user') }}
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Account Information</h4>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Name:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Email:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Role:</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($user->role ?? 'user') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Joined:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->created_at->format('M j, Y') }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Activity</h4>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Last Login:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Email Verified:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->email_verified_at ? 'Yes' : 'No' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Status:</dt>
                            <dd class="text-sm text-gray-900">Active</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Users
                </a>
                <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 mr-2">
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                        <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        Update Role
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
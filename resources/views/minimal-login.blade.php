@extends('layouts.app')

@section('title', 'Minimal Login Test')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-50 py-12 px-4">
    <div class="w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Minimal Login Test</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('minimal.login.submit') }}" class="bg-white p-6 rounded shadow">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
                <input type="email" id="email" name="email" required
                       class="w-full border rounded px-3 py-2"
                       value="{{ old('email', 'admin@example.com') }}">
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password:</label>
                <input type="password" id="password" name="password" required
                       class="w-full border rounded px-3 py-2"
                       value="password">
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                Login
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="{{ route('debug-auth') }}" class="text-blue-500 hover:underline">Debug Authentication</a>
        </div>
        
        @auth
            <div class="mt-4 bg-green-50 p-4 rounded">
                <p class="text-green-800">✓ Authenticated as: {{ Auth::user()->email }}</p>
                <p class="text-sm text-gray-600">User ID: {{ Auth::id() }}</p>
                <p class="text-sm text-gray-600">Session ID: {{ session()->getId() }}</p>
            </div>
        @else
            <div class="mt-4 bg-red-50 p-4 rounded">
                <p class="text-red-800">✗ Not authenticated</p>
                <p class="text-sm text-gray-600">Session ID: {{ session()->getId() }}</p>
            </div>
        @endauth
    </div>
</div>
@endsection
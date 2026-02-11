@extends('layouts.app')

@section('title', 'Logout Test')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-50 py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-xl font-semibold mb-4">Logout Test</h1>
            
            @auth
                <div class="space-y-4">
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded">
                        <p class="font-medium">✓ You are logged in</p>
                        <p class="text-sm">User: {{ Auth::user()->email }}</p>
                        <p class="text-sm">Role: {{ Auth::user()->role }}</p>
                        <p class="text-sm">Session ID: {{ session()->getId() }}</p>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="bg-red-50 border border-red-200 p-4 rounded">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded">
                    <p class="font-medium">You are not logged in</p>
                    <p class="text-sm">Session ID: {{ session()->getId() }}</p>
                    
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('admin.login') }}" class="block bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 text-center">
                            Go to Admin Login
                        </a>
                        <a href="/minimal-working-login" class="block bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700 text-center">
                            Test Simple Login
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
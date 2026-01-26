<?php

use Illuminate\Support\Facades\Auth;

it('can authenticate user with correct credentials', function () {
    // Create a test user
    $user = \App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test-auth@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'customer',
    ]);

    // Attempt authentication
    $credentials = ['email' => 'test-auth@example.com', 'password' => 'password123'];
    $authenticated = Auth::attempt($credentials);

    expect($authenticated)->toBeTrue();
    expect(Auth::check())->toBeTrue();
    expect(Auth::user()->id)->toBe($user->id);
    expect(Auth::user()->email)->toBe('test-auth@example.com');

    // Logout
    Auth::logout();
    expect(Auth::check())->toBeFalse();
});

it('rejects authentication with wrong password', function () {
    // Create a test user
    $user = \App\Models\User::create([
        'name' => 'Test User 2',
        'email' => 'test-auth2@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('correctpassword'),
        'role' => 'customer',
    ]);

    // Attempt authentication with wrong password
    $credentials = ['email' => 'test-auth2@example.com', 'password' => 'wrongpassword'];
    $authenticated = Auth::attempt($credentials);

    expect($authenticated)->toBeFalse();
    expect(Auth::check())->toBeFalse();
});
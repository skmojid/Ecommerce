<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can update order status to shipped', function () {
    // Create admin user
    $admin = User::factory()->create(['role' => 'admin']);
    
    // Create a pending order
    $order = Order::factory()->create([
        'status' => 'pending',
        'user_id' => $admin->id,
    ]);

    // Test status update
    $this->actingAs($admin)
        ->put("/admin/orders/{$order->id}/status", [
            'status' => 'shipped',
        ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Order status updated successfully.');

    // Verify status changed
    $order->refresh();
    expect($order->status)->toBe('shipped');
    expect($order->shipped_at)->not->toBeNull();
});

test('admin can update order status to delivered', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    $order = Order::factory()->create([
        'status' => 'shipped',
        'shipped_at' => now()->subDay(),
        'user_id' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->put("/admin/orders/{$order->id}/status", [
            'status' => 'delivered',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $order->refresh();
    expect($order->status)->toBe('delivered');
    expect($order->delivered_at)->not->toBeNull();
});

test('cannot change status of delivered order', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    $order = Order::factory()->create([
        'status' => 'delivered',
        'delivered_at' => now(),
        'user_id' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->put("/admin/orders/{$order->id}/status", [
            'status' => 'shipped',
        ])
        ->assertRedirect()
        ->assertSessionHas('error', 'Cannot change status of delivered order.');
});

test('non-admin cannot update order status', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    
    $order = Order::factory()->create([
        'status' => 'pending',
        'user_id' => $customer->id,
    ]);

    $this->actingAs($customer)
        ->put("/admin/orders/{$order->id}/status", [
            'status' => 'shipped',
        ])
        ->assertStatus(403);
});
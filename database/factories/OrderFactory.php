<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'order_number' => 'ORD-' . fake()->unique()->numberBetween(100000, 999999),
            'status' => 'pending',
            'subtotal' => fake()->randomFloat(2, 10, 1000),
            'tax_amount' => fake()->randomFloat(2, 0, 100),
            'shipping_amount' => fake()->randomFloat(2, 0, 50),
            'discount_amount' => 0,
            'total' => fake()->randomFloat(2, 10, 1100),
            'currency' => 'USD',
            'payment_method' => fake()->randomElement(['cod', 'card', 'paypal']),
            'payment_status' => 'pending',
            'shipping_address' => json_encode([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => fake()->country(),
                'phone' => fake()->phoneNumber(),
            ]),
            'billing_address' => json_encode([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => fake()->country(),
                'phone' => fake()->phoneNumber(),
            ]),
            'notes' => fake()->sentence(),
        ];
    }
}

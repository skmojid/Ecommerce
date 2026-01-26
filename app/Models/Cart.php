<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total',
        'coupon_code',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            CartItem::class,
            'cart_id', // Foreign key on cart_items table
            'id',      // Foreign key on products table
            'id',      // Local key on carts table
            'product_id' // Local key on cart_items table
        );
    }

    /**
     * Calculate subtotal from items
     */
    public function calculateSubtotal(): float
    {
        return $this->items->sum('total_price');
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return '$'.number_format($this->calculateSubtotal(), 2);
    }

    /**
     * Get cart total
     */
    public function getTotalAttribute(): float
    {
        return $this->subtotal + $this->tax_amount + $this->shipping_amount - $this->discount_amount;
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute(): string
    {
        return '$'.number_format($this->getTotal(), 2);
    }

    /**
     * Get items count
     */
    public function getItemsCountAttribute(): int
    {
        return $this->items()->count();
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty(): bool
    {
        return $this->items()->count() === 0;
    }

    /**
     * Add product to cart
     */
    public function addItem(Product $product, int $quantity = 1, ?int $variationId = null): CartItem
    {
        $cartItem = $this->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update existing item
            $cartItem->increment('quantity', $quantity);
        } else {
            // Create new item
            $cartItem = CartItem::create([
                'cart_id' => $this->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'total_price' => $product->price * $quantity,
            ]);
        }

        $this->updateTotals();

        return $cartItem;
    }

    /**
     * Calculate all cart totals
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->calculateSubtotal();
        $taxAmount = $subtotal * 0.08; // 8% tax rate
        $shippingAmount = $subtotal > 0 ? ($subtotal > 100 ? 0 : 10) : 0; // Free shipping over $100

        $this->subtotal = $subtotal;
        $this->tax_amount = $taxAmount;
        $this->shipping_amount = $shippingAmount;
        $this->total = $subtotal + $taxAmount + $shippingAmount - $this->discount_amount;
        
        // Save to database
        $this->save();
    }

    /**
     * Update cart totals
     */
    public function updateTotals(): void
    {
        // Ensure items are loaded for calculation
        if (!$this->relationLoaded('items')) {
            $this->load('items');
        }
        
        $this->calculateTotals();
    }
}

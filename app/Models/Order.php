<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total',
        'currency',
        'payment_method',
        'payment_status',
        'shipping_address',
        'billing_address',
        'notes',
        'shipped_at',
        'delivered_at',
    ];
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function getFormattedSubtotalAttribute(): string
    {
        return '$'.number_format($this->subtotal, 2);
    }
    public function getFormattedTotalAttribute(): string
    {
        return '$'.number_format($this->total, 2);
    }
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    public function canBeCancelled(): bool
    {
        return $this->status === 'pending';
    }
    public function canBeShipped(): bool
    {
        return $this->status === 'pending';
    }
    public function canBeDelivered(): bool
    {
        return $this->status === 'shipped';
    }
    public function canBeRefunded(): bool
    {
        return in_array($this->status, ['pending', 'shipped']) && $this->payment_status === 'paid';
    }
    public function getShippingAddressAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
    public function getBillingAddressAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
    public function setShippingAddressAttribute($value)
    {
        $this->attributes['shipping_address'] = json_encode($value);
    }
    public function setBillingAddressAttribute($value)
    {
        $this->attributes['billing_address'] = json_encode($value);
    }
    public function markAsShipped(): void
    {
        $this->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);
    }
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }
    public function cancel(): void
    {
        if (! $this->canBeCancelled()) {
            throw new \Exception('Order cannot be cancelled');
        }
        $this->update([
            'status' => 'cancelled',
        ]);
    }
    public function refund(): void
    {
        if (! $this->canBeRefunded()) {
            throw new \Exception('Order cannot be refunded');
        }
        $this->update([
            'status' => 'refunded',
            'payment_status' => 'refunded',
        ]);
    }
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        });
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-'.str_pad(static::max('id') + 1, 8, '0', STR_PAD_LEFT);
            }
        });
    }
}
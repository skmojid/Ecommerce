<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'unit_price',
        'total_price',
    ];
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function getFormattedUnitPriceAttribute(): string
    {
        return '$'.number_format($this->unit_price, 2);
    }
    public function getFormattedTotalPriceAttribute(): string
    {
        return '$'.number_format($this->total_price, 2);
    }
    public function isProductStillAvailable(): bool
    {
        return $this->product && $this->product->isInStock() && $this->product->is_active;
    }
    public function getPriceDifference(): ?float
    {
        if (! $this->product) {
            return null;
        }
        return $this->product->price - $this->unit_price;
    }
    public function getPriceDifferencePercentage(): ?float
    {
        if (! $this->product || $this->unit_price == 0) {
            return null;
        }
        return round((($this->product->price - $this->unit_price) / $this->unit_price) * 100, 2);
    }
    public function scopeByOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
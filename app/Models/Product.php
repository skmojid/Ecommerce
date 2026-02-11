<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'cost_price',
        'sku',
        'barcode',
        'track_quantity',
        'quantity',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
        'weight',
    ];
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'weight' => 'decimal:3',
            'track_quantity' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'quantity' => 'integer',
        ];
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
    public function getPrimaryImageAttribute()
    {
        return $this->primaryImage()->first() ?? $this->images()->first();
    }
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('quantity', '<=', $threshold)->where('quantity', '>', 0);
    }
    public function scopeWithComparePrice($query)
    {
        return $query->whereNotNull('compare_price');
    }
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }
    public function getFormattedPriceAttribute(): string
    {
        return '$'.number_format($this->price, 2);
    }
    public function getFormattedComparePriceAttribute(): ?string
    {
        return $this->compare_price ? '$'.number_format($this->compare_price, 2) : null;
    }
    public function getIsOnSaleAttribute(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }
    public function isInStock(): bool
    {
        return ! $this->track_quantity || $this->quantity > 0;
    }
    public function getDiscountPercentageAttribute(): ?float
    {
        if (! $this->is_on_sale) {
            return null;
        }
        return round((($this->compare_price - $this->price) / $this->compare_price) * 100, 1);
    }
}
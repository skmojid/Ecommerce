<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_amount',
        'is_active',
        'expires_at',
        'usage_limit',
        'description',
    ];
    protected function casts(): array
    {
        return [
            'type' => 'string',
            'value' => 'decimal:2',
            'minimum_amount' => 'decimal:2',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'usage_limit' => 'integer',
        ];
    }
    public function getDiscountPercentageAttribute(): ?float
    {
        return $this->minimum_amount > 0 ? round(($this->value / $this->minimum_amount) * 100, 1) : 0;
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeValid($query)
    {
        return $query->where(function ($subQuery) {
            $subQuery->where('is_active', true)
                ->where(function ($innerQuery) {
                    return $innerQuery->where('expires_at', '>', now())
                        ->orWhereNull('expires_at');
                });
        });
    }
    public function scopeApplicableToAmount($query, $amount)
    {
        return $query->where('minimum_amount', '<=', $amount);
    }
}
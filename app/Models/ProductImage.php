<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'sort_order',
        'is_primary',
    ];
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
    public function getUrlAttribute()
    {
        $storagePath = storage_path('app/public/' . $this->image_path);
        if (file_exists($storagePath)) {
            return asset('storage/' . $this->image_path);
        }
        $uploadsFiles = glob(public_path("uploads/products/*{$this->product_id}*"));
        if (!empty($uploadsFiles)) {
            $filename = basename($uploadsFiles[0]);
            return asset("uploads/products/{$filename}");
        }
        $legacyMapping = [
            10 => ['cargo-pant_1769055543_NrSuadMs.png', 'cargo-pant_1769062025_TqFfLDxc.png', 'cargo-pant_1769062837_ti9B81hU.png'],
            11 => ['cargo-pant_1769063566_es4a5p9q.png', 'cargo-pant_1769063712_6BLY4zFx.png'],
            13 => ['cool_1769049604_odyXBjo6.png'],
        ];
        if (isset($legacyMapping[$this->product_id])) {
            foreach ($legacyMapping[$this->product_id] as $filename) {
                if (file_exists(public_path("uploads/products/{$filename}"))) {
                    return asset("uploads/products/{$filename}");
                }
            }
        }
        $allFiles = glob(public_path("uploads/products/*"));
        if (!empty($allFiles)) {
            $filename = basename($allFiles[0]);
            return asset("uploads/products/{$filename}");
        }
        return asset('storage/' . $this->image_path);
    }
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($image) {
            if ($image->is_primary) {
                static::where('product_id', $image->product_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_primary' => false]);
            }
        });
    }
}
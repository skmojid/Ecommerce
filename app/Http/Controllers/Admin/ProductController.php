<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    private function uploadProductImage($image, $productName, $productId)
    {
        $imageName = 'product_'.$productId.'_'.uniqid().'_'.Str::random(12).'.'.$image->getClientOriginalExtension();
        $path = $image->storeAs('products', $imageName, 'public');
        return $path;
    }
    private function deleteProductImage($imagePath)
    {
        if ($imagePath) {
            $fullPath = storage_path('app/public/' . $imagePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
                return true;
            }
        }
        return false;
    }
    public function index(Request $request)
    {
        $products = Product::with(['category', 'primaryImage'])
            ->when($request->category, function ($query, $categoryId) {
                return $query->byCategory($categoryId);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('is_active', $status === 'active');
            })
            ->latest()
            ->paginate(15);
        $categories = Category::active()->ordered()->get();
        return view('admin.products.index', compact('products', 'categories'));
    }
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.products.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'compare_price' => ['nullable', 'numeric', 'min:0.01', 'max:999999.99', 'gt:price'],
            'cost_price' => ['nullable', 'numeric', 'min:0.01', 'max:999999.99', 'lt:price'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'barcode' => ['nullable', 'string', 'max:100', 'unique:products,barcode'],
            'track_quantity' => ['boolean'],
            'quantity' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->boolean('track_quantity') && $value <= 0) {
                        $fail('The quantity field must be at least 1 when track quantity is enabled.');
                    }
                },
            ],
            'stock_alert_threshold' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999999.999'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'dimensions:min_width=300,min_height=300',
            ],
            'primary_image' => ['nullable', 'integer', 'min:0'],
        ], [
            'price.gt' => 'The price must be greater than cost price.',
            'compare_price.gt' => 'The compare price must be greater than regular price.',
            'cost_price.lt' => 'The cost price must be less than regular price.',
            'quantity.required_with_track' => 'Quantity is required when tracking inventory.',
        ]);
        $data = $request->except(['images', 'primary_image']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['track_quantity'] = $request->boolean('track_quantity');
        $product = Product::create($data);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $this->uploadProductImage($image, $product->name, $product->id);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'alt_text' => $product->name,
                    'sort_order' => $index,
                    'is_primary' => $index === ($request->integer('primary_image', 0)),
                ]);
            }
        }
        if (! $product->images()->where('is_primary', true)->exists() && $product->images()->exists()) {
            $product->images()->first()->update(['is_primary' => true]);
        }
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }
    public function show(Product $product)
    {
        $product->load(['category', 'images' => function ($query) {
            $query->ordered();
        }]);
        return view('admin.products.show', compact('product'));
    }
    public function edit(Product $product)
    {
        $categories = Category::active()->ordered()->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name,'.$product->id],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'compare_price' => ['nullable', 'numeric', 'min:0.01', 'max:999999.99', 'gt:price'],
            'cost_price' => ['nullable', 'numeric', 'min:0.01', 'max:999999.99', 'lt:price'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,'.$product->id],
            'barcode' => ['nullable', 'string', 'max:100', 'unique:products,barcode,'.$product->id],
            'track_quantity' => ['boolean'],
            'quantity' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->boolean('track_quantity') && $value <= 0) {
                        $fail('The quantity field must be at least 1 when track quantity is enabled.');
                    }
                },
            ],
            'stock_alert_threshold' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999999.999'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'dimensions:min_width=300,min_height=300',
            ],
            'primary_image' => ['nullable', 'integer', 'min:0'],
        ], [
            'price.gt' => 'The price must be greater than cost price.',
            'compare_price.gt' => 'The compare price must be greater than regular price.',
            'cost_price.lt' => 'The cost price must be less than regular price.',
            'quantity.required_with_track' => 'Quantity is required when tracking inventory.',
        ]);
        $data = $request->except(['images', 'primary_image', 'delete_images']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['track_quantity'] = $request->boolean('track_quantity');
        $product->update($data);
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    $this->deleteProductImage($image->image_path);
                    $image->delete();
                }
            }
        }
        if ($request->hasFile('images')) {
            $currentMaxSort = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $this->uploadProductImage($image, $product->name, $product->id);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'alt_text' => $product->name,
                    'sort_order' => $currentMaxSort + $index + 1,
                    'is_primary' => false,
                ]);
            }
        }
        if ($request->has('primary_image') && $request->integer('primary_image') > 0) {
            $product->images()->update(['is_primary' => false]);
            $primaryImage = $product->images()->find($request->integer('primary_image'));
            if ($primaryImage) {
                $primaryImage->update(['is_primary' => true]);
            }
        }
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }
    public function destroy(Product $product)
    {
        try {
            if ($product->orderItems()->exists()) {
                return back()->with('error', 'Cannot delete product that has been ordered.');
            }
            foreach ($product->images as $image) {
                $this->deleteProductImage($image->image_path);
                $image->delete();
            }
            $product->delete();
            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting product: '.$e->getMessage());
        }
    }
    public function uploadImage(Request $request, Product $product)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);
        $image = $request->file('image');
        $imagePath = $this->uploadProductImage($image, $product->name, $product->id);
        $currentMaxSort = $product->images()->max('sort_order') ?? 0;
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $imagePath,
            'alt_text' => $product->name,
            'sort_order' => $currentMaxSort + 1,
            'is_primary' => false,
        ]);
        return back()->with('success', 'Image uploaded successfully.');
    }
    public function deleteImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            abort(404);
        }
        $this->deleteProductImage($image->image_path);
        $image->delete();
        return back()->with('success', 'Image deleted successfully.');
    }
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);
        return back()->with('success', 'Product status updated successfully.');
    }
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => ! $product->is_featured]);
        return back()->with('success', 'Product featured status updated successfully.');
    }
    public function updateInventory(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
            'track_quantity' => ['boolean'],
        ]);
        $product->update([
            'quantity' => $request->integer('quantity'),
            'track_quantity' => $request->boolean('track_quantity'),
        ]);
        return back()->with('success', 'Inventory updated successfully.');
    }
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'products' => ['required', 'array'],
            'action' => ['required', 'in:activate,deactivate,delete,feature,unfeature'],
        ]);
        $action = $request->action;
        $productIds = $request->products;
        switch ($action) {
            case 'activate':
                Product::whereIn('id', $productIds)->update(['is_active' => true]);
                $message = 'Products activated successfully.';
                break;
            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['is_active' => false]);
                $message = 'Products deactivated successfully.';
                break;
            case 'feature':
                Product::whereIn('id', $productIds)->update(['is_featured' => true]);
                $message = 'Products featured successfully.';
                break;
            case 'unfeature':
                Product::whereIn('id', $productIds)->update(['is_featured' => false]);
                $message = 'Products unfeatured successfully.';
                break;
            case 'delete':
                $productsWithOrders = Product::whereIn('id', $productIds)
                    ->whereHas('orderItems')
                    ->count();
                if ($productsWithOrders > 0) {
                    return back()->with('error', 'Cannot delete products that have orders.');
                }
                Product::whereIn('id', $productIds)->delete();
                $message = 'Products deleted successfully.';
                break;
            default:
                $message = 'Invalid action.';
        }
        return back()->with('success', $message);
    }
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ]);
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        array_shift($data); // Remove header
        $imported = 0;
        $skipped = 0;
        foreach ($data as $row) {
            try {
                Product::create([
                    'name' => $row[0] ?? '',
                    'description' => $row[1] ?? '',
                    'category_id' => $row[2] ?? 1,
                    'price' => $row[3] ?? 0,
                    'sku' => $row[4] ?? '',
                    'quantity' => $row[5] ?? 0,
                    'is_active' => true,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $skipped++;
            }
        }
        return back()->with('success', "Imported {$imported} products. Skipped {$skipped}.");
    }
    public function export(Request $request)
    {
        $products = Product::with('category')
            ->when($request->category, function ($query, $categoryId) {
                return $query->byCategory($categoryId);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('is_active', $status === 'active');
            })
            ->latest()
            ->get();
        $filename = 'products_'.date('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];
        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Name', 'SKU', 'Category', 'Price', 'Quantity', 'Status', 'Created At',
            ]);
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    $product->sku,
                    $product->category->name ?? 'N/A',
                    $product->price,
                    $product->quantity,
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
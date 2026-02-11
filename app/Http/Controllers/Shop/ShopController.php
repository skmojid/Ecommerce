<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Cache::remember('categories.active', 3600, function () {
            return Category::active()->ordered()->get();
        });
        $productsQuery = Product::active()
            ->with([
                'category:id,name',
                'primaryImage',
                'images',
            ]);
        $productsQuery = $this->applyFilters($productsQuery, $request);
        $productsQuery = $this->applySorting($productsQuery, $request);
        $products = $productsQuery
            ->paginate(12);
        $featuredProducts = Cache::remember('featured.products', 1800, function () {
            return Product::active()
                ->where('is_featured', true)
                ->with('category')
                ->latest()
                ->limit(4)
                ->get();
        });
        return view('shop.index', compact('products', 'categories', 'featuredProducts'));
    }
    public function show(Product $product)
    {
        $product->load([
            'category:id,name',
            'images' => function ($query) {
                $query->ordered();
            },
        ]);
        $relatedProducts = Cache::remember(
            "product.related.{$product->id}",
            3600,
            function () use ($product) {
                return Product::active()
                    ->where('id', '!=', $product->id)
                    ->byCategory($product->category_id)
                    ->inRandomOrder()
                    ->limit(8)
                    ->get(['id', 'name', 'slug', 'price', 'primaryImage']);
            }
        );
        $reviews = $this->getProductReviews($product->id);
        $cartQuantity = $this->getProductCartQuantity($product->id);
        return view('shop.show', compact('product', 'relatedProducts', 'reviews', 'cartQuantity'));
    }
    public function contact()
    {
        return view('shop.contact');
    }
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);
        return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
    public function categories()
    {
        $categories = Cache::remember('categories.all', 3600, function () {
            return Category::active()
                ->withCount(['activeProducts' => function ($query) {
                    $query->where('is_active', true);
                }])
                ->ordered()
                ->get();
        });
        return view('shop.categories', compact('categories'));
    }
    public function category(Category $category)
    {
        $products = $category->activeProducts()
            ->with('primaryImage')
            ->latest()
            ->paginate(12);
        $subcategories = Cache::remember(
            "category.children.{$category->id}",
            1800,
            function () {
                return collect(); // No child categories for now
            }
        );
        return view('shop.category', compact('category', 'products', 'subcategories'));
    }
    private function applyFilters($query, Request $request)
    {
        return $query
            ->when($request->category, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->search, function ($query, $search) {
                if (config('database.default') === 'mysql') {
                    return $query->whereRaw(
                        'MATCH(name, description, sku) AGAINST (?)',
                        [$search]
                    );
                } else {
                    return $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
                }
            })
            ->when($request->min_price, function ($query, $minPrice) {
                return $query->where('price', '>=', $minPrice);
            })
            ->when($request->max_price, function ($query, $maxPrice) {
                return $query->where('price', '<=', $maxPrice);
            })
            ->when($request->in_stock, function ($query) {
                return $query->where(function ($subQuery) {
                    $subQuery->where('quantity', '>', 0)
                        ->orWhere('track_quantity', false);
                });
            })
            ->when($request->featured, function ($query) {
                return $query->where('is_featured', true);
            })
            ->when($request->on_sale, function ($query) {
                return $query->whereNotNull('compare_price')
                    ->whereColumn('compare_price', '>', 'price');
            });
    }
    private function applySorting($query, Request $request)
    {
        $sort = $request->get('sort', 'latest');
        $order = $request->get('order', 'desc');
        return $query->when($sort, function ($query, $sort) use ($order) {
            switch ($sort) {
                case 'price_low':
                    return $query->orderBy('price', 'asc');
                case 'price_high':
                    return $query->orderBy('price', 'desc');
                case 'name':
                    return $query->orderBy('name', $order);
                case 'created':
                    return $query->orderBy('created_at', $order);
                case 'popular':
                    return $query->withCount(['orderItems' => function ($query) {
                        $query->whereHas('order', function ($orderQuery) {
                            $orderQuery->where('created_at', '>=', now()->subDays(30));
                        });
                    }])
                        ->orderBy('order_items_count', 'desc');
                default:
                    return $query->latest();
            }
        });
    }
    private function getProductReviews($productId)
    {
        return Cache::remember(
            "product.reviews.{$productId}",
            3600,
            function () {
                return collect();
            }
        );
    }
    private function getProductCartQuantity($productId)
    {
        if (! auth()->check()) {
            return 0;
        }
        if (! auth()->check()) {
            return 0;
        }
        return Cache::remember(
            'cart.quantity.'.auth()->id().".{$productId}",
            600,
            function () use ($productId) {
                $cartItem = auth()->user()->cart?->items()
                    ->where('product_id', $productId)
                    ->first();
                return $cartItem ? $cartItem->quantity : 0;
            }
        );
    }
    public function searchSuggestions(Request $request)
    {
        $search = $request->get('q', '');
        if (strlen($search) < 2) {
            return response()->json([]);
        }
        $suggestions = Product::active()
            ->where('name', 'like', "{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'slug', 'price']);
        return response()->json($suggestions);
    }
    public function getByPriceRange(Request $request)
    {
        $request->validate([
            'min' => 'required|numeric|min:0',
            'max' => 'required|numeric|min:0',
        ]);
        $products = Product::active()
            ->whereBetween('price', [$request->min, $request->max])
            ->with('category')
            ->orderBy('price', 'asc')
            ->limit(20)
            ->get();
        return response()->json($products);
    }
}
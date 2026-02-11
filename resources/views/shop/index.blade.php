@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-green-600 to-green-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Shop Our Products</h1>
            <p class="text-xl md:text-2xl text-indigo-100 mb-8 max-w-3xl mx-auto">
                Discover amazing products at great prices
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="login" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Shop Now
                </a>
                
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section id="products" class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 sm:gap-0">
            <div class="text-center sm:text-left">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                    All Products
                </h2>
                <p class="text-lg text-gray-600 mt-1">({{ $products->total() }} items)</p>
            </div>
            
            <!-- View Toggle -->
            <div class="hidden lg:flex space-x-4">
                <button onclick="toggleView()" id="gridView" 
                        class="px-6 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-th mr-2"></i>
                    Grid View
                </button>
                <button onclick="toggleView()" id="listView" 
                        class="px-6 py-2.5 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    <i class="fas fa-list mr-2"></i>
                    List View
                </button>
            </div>
        </div>
        
        <!-- Products Container -->
        <div id="productsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group transform hover:scale-105">
                    <a href="{{ route('shop.show', $product) }}" class="block">
                        <div class="relative">
                            @if($product->is_on_sale)
                                <span class="absolute top-3 right-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full z-10 shadow-lg">
                                    <i class="fas fa-percentage mr-1"></i>
                                    Sale
                                </span>
                            @endif
                            
                            <div class="aspect-w-1 aspect-h-1 bg-gray-100 overflow-hidden">
                                @if($product->primary_image)
                                    <img src="{{ $product->primary_image->url }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex-grow">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-3 gap-3">
                                    <div class="flex-1 sm:flex-1-2">
                                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 mb-1">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                    </div>
                                    
                                    <div class="text-right sm:text-left">
                                        @if($product->is_on_sale)
                                            <div class="space-y-1">
                                                <span class="text-xl font-bold text-red-600 block">${{ number_format($product->price, 2) }}</span>
                                                <span class="text-sm text-gray-400 line-through block">${{ number_format($product->compare_price, 2) }}</span>
                                            </div>
                                        @else
                                            <span class="text-xl font-bold text-gray-900 block">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <p class="text-gray-700 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {{ $product->short_description ?: Str::limit($product->description, 100) }}
                                </p>
                            </div>
                            
                            <!-- Stock Status and Button - Always at Bottom -->
                            <div class="mt-auto space-y-3">
                                <!-- Stock Status -->
                                <div class="flex items-center">
                                    @if($product->track_quantity)
                                        @if($product->quantity <= 5)
                                            <span class="px-3 py-1.5 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                                Only {{ $product->quantity }} left!
                                            </span>
                                        @elseif($product->quantity <= 10)
                                            <span class="px-3 py-1.5 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                                Low Stock ({{ $product->quantity }})
                                            </span>
                                        @else
                                            <span class="px-3 py-1.5 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                In Stock
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                            Available
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Add to Cart Button -->
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="w-full bg-indigo-600 text-white px-5 py-2.5 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-all"
                                            @if(!$product->isInStock())
                                            disabled
                                        @endif>
                                        <span class="flex items-center justify-center">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            @if($product->isInStock())
                                                Add to Cart
                                            @else
                                                Out of Stock
                                            @endif
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="bg-white rounded-lg shadow-sm p-12">
                        <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                        <h3 class="text-2xl font-bold text-gray-700 mb-4">No products found matching your criteria.</h3>
                        <p class="text-gray-600 mb-6">Try adjusting your filters or browse all products.</p>
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Browse All Products
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-16 flex justify-center">
                <div class="inline-flex items-center space-x-1">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

@endsection

@section('scripts')
<script>
    function clearSearch() {
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
    
    function toggleView() {
        const container = document.getElementById('productsContainer');
        const gridBtn = document.getElementById('gridView');
        const listBtn = document.getElementById('listView');
        
        if (container.classList.contains('grid-cols-1')) {
            // Switch to list view
            container.className = 'space-y-6';
            gridBtn.classList.add('bg-indigo-600', 'text-white');
            gridBtn.classList.remove('bg-gray-300', 'text-gray-700');
            listBtn.classList.add('bg-indigo-600', 'text-white');
            listBtn.classList.remove('bg-gray-300', 'text-gray-700');
        } else {
            // Switch to grid view
            container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
            listBtn.classList.add('bg-indigo-600', 'text-white');
            listBtn.classList.remove('bg-gray-300', 'text-gray-700');
            gridBtn.classList.remove('bg-gray-300', 'text-gray-700');
            gridBtn.classList.add('bg-indigo-600', 'text-white');
        }
    }
    
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(alert => alert.style.display = 'none');
    }, 5000);
</script>
@endsection
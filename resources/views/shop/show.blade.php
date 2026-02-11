@extends('layouts.app')

@section('title', $product->name)

@section('content')
<!-- Product Header -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('shop.index') }}" class="text-gray-700 hover:text-indigo-600">
                        Shop
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('shop.category', $product->category) }}" class="ml-1 text-gray-700 hover:text-indigo-600 md:ml-3">
                            {{ $product->category->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-3 font-medium">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4">
                <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                    @if($product->primary_image)
                        <img id="mainImage" src="{{ $product->primary_image->url }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-96 flex items-center justify-center bg-gray-200">
                            <i class="fas fa-image text-gray-400 text-6xl"></i>
                        </div>
                    @endif
                </div>
                
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($product->images->sortBy('sort_order') as $image)
                            <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden cursor-pointer">
                                <img src="{{ $image->url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover hover:opacity-75 transition-opacity">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div>
                <div class="mb-6">
                    @if($product->is_featured)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mb-4">
                            <i class="fas fa-star mr-1"></i>
                            Featured
                        </span>
                    @endif
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    
                    <div class="flex items-center space-x-4 mb-4">
                        <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                        
                        @if($product->compare_price > $product->price)
                            <span class="text-lg text-gray-500 line-through">
                                ${{ number_format($product->compare_price, 2) }}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                Save ${{ number_format($product->compare_price - $product->price, 2) }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-6">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="ml-2">4.0 ({{ $reviews->count() }} reviews)</span>
                        </div>
                        
                        <div class="flex items-center">
                            @if($product->stock > 0)
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                <span class="text-green-600">In Stock ({{ $product->stock }} available)</span>
                            @else
                                <i class="fas fa-times-circle text-red-500 mr-1"></i>
                                <span class="text-red-600">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="prose max-w-none mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>

                <!-- Add to Cart Form -->
                @if($product->is_active && $product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <!-- Quantity Selector -->
                        <div class="flex items-center space-x-4">
                            <label for="quantity" class="text-sm font-medium text-gray-700">Quantity:</label>
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button type="button" onclick="decreaseQuantity()" 
                                        class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="{{ $cartQuantity ?? 1 }}" 
                                       min="1" max="{{ min(99, $product->stock) }}" 
                                       class="w-16 text-center border-0 focus:ring-0">
                                <button type="button" onclick="increaseQuantity()" 
                                        class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            
                            <span class="text-sm text-gray-500">
                                {{ $product->stock }} available
                            </span>
                        </div>

                        <!-- Add to Cart Button -->
                        <button type="submit" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Add to Cart
                        </button>
                    </form>
                @else
                    <div class="bg-gray-100 border border-gray-300 rounded-md p-4 text-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mb-2"></i>
                        <p class="text-gray-600 font-medium">
                            {{ $product->stock > 0 ? 'Product is not available' : 'Product is out of stock' }}
                        </p>
                    </div>
                @endif

                <!-- Product Features -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="text-center">
                            <i class="fas fa-truck text-indigo-600 text-2xl mb-2"></i>
                            <h4 class="text-sm font-medium text-gray-900">Free Shipping</h4>
                            <p class="text-xs text-gray-500">On orders over $100</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-undo text-indigo-600 text-2xl mb-2"></i>
                            <h4 class="text-sm font-medium text-gray-900">Easy Returns</h4>
                            <p class="text-xs text-gray-500">30-day return policy</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-shield-alt text-indigo-600 text-2xl mb-2"></i>
                            <h4 class="text-sm font-medium text-gray-900">Secure Payment</h4>
                            <p class="text-xs text-gray-500">100% secure transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Details Tabs -->
<section class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button onclick="showTab('description')" id="description-tab" 
                            class="py-4 px-6 border-b-2 border-indigo-500 font-medium text-sm text-indigo-600">
                        Description
                    </button>
                    <button onclick="showTab('reviews')" id="reviews-tab" 
                            class="py-4 px-6 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        Reviews ({{ $reviews->count() }})
                    </button>
                    <button onclick="showTab('shipping')" id="shipping-tab" 
                            class="py-4 px-6 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        Shipping & Returns
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Description Tab -->
                <div id="description-content" class="tab-content">
                    <div class="prose max-w-none">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Description</h3>
                        <div class="text-gray-600">
                            {{ $product->description ?? 'No detailed description available for this product.' }}
                        </div>
                        
                        @if($product->specifications)
                            <h4 class="text-md font-semibold text-gray-900 mt-6 mb-3">Specifications</h4>
                            <div class="text-gray-600">
                                {{ $product->specifications }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="reviews-content" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Reviews</h3>
                    
                    @if($reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($reviews as $review)
                                <div class="border-b border-gray-200 pb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600 text-sm"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $review->name }}</p>
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <p class="text-gray-600">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-comment-dots text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                        </div>
                    @endif
                </div>

                <!-- Shipping Tab -->
                <div id="shipping-content" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Information</h3>
                            <div class="space-y-3 text-gray-600">
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium">Standard Shipping</p>
                                        <p class="text-sm">5-7 business days - $10.00</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium">Express Shipping</p>
                                        <p class="text-sm">2-3 business days - $25.00</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium">Free Shipping</p>
                                        <p class="text-sm">On orders over $100</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Return Policy</h3>
                            <div class="space-y-3 text-gray-600">
                                <div class="flex items-start">
                                    <i class="fas fa-undo text-indigo-600 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium">30-Day Returns</p>
                                        <p class="text-sm">Return within 30 days for a full refund</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-shield-alt text-indigo-600 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium">Item Condition</p>
                                        <p class="text-sm">Items must be unused and in original packaging</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-truck text-indigo-600 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium">Return Shipping</p>
                                        <p class="text-sm">Free return shipping on defective items</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Related Products</h2>
            <p class="text-gray-600 mt-2">You might also like these products</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="relative">
                        @if($relatedProduct->primary_image)
                            <img src="{{ $relatedProduct->primary_image->url }}" 
                                 alt="{{ $relatedProduct->name }}" 
                                 class="w-full h-48 object-cover rounded-t-lg">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('shop.show', $relatedProduct) }}" class="hover:text-indigo-600">
                                {{ $relatedProduct->name }}
                            </a>
                        </h3>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">
                                ${{ number_format($relatedProduct->price, 2) }}
                            </span>
                            
                            <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="bg-indigo-600 text-white p-2 rounded-md hover:bg-indigo-700">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
function showTab(tabName) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('[id$="-tab"]').forEach(tab => {
        tab.classList.remove('border-indigo-500', 'text-indigo-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-indigo-500', 'text-indigo-600');
}

function changeMainImage(imageSrc) {
    document.getElementById('mainImage').src = imageSrc;
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    
    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const min = parseInt(input.getAttribute('min'));
    const current = parseInt(input.value);
    
    if (current > min) {
        input.value = current - 1;
    }
}
</script>
@endpush
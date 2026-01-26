@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Browse Categories</h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto">
                Explore our wide range of product categories
            </p>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('shop.category', $category) }}" 
                       class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <div class="relative h-48 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="text-center">
                                    <i class="fas fa-th-large text-6xl text-indigo-400 mb-3"></i>
                                </div>
                            @endif>
                            
                            @if($category->active_products_count > 0)
                                <span class="absolute top-4 right-4 bg-indigo-600 text-white text-xs px-2 py-1 rounded-full">
                                    {{ $category->active_products_count }} products
                                </span>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
                                {{ $category->name }}
                            </h3>
                            
                            @if($category->description)
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ Str::limit($category->description, 80) }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <span class="text-indigo-600 font-medium text-sm">
                                    {{ $category->active_products_count }} items
                                </span>
                                <span class="text-indigo-600 group-hover:text-indigo-700">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-th-large text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No Categories Available</h3>
                <p class="text-gray-600">Check back later for new product categories.</p>
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="bg-gray-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Can't Find What You're Looking For?</h2>
        <p class="text-lg text-gray-600 mb-8">
            Browse our full product catalog or contact us for special requests
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop.index') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                <i class="fas fa-shopping-bag mr-2"></i>
                All Products
            </a>
            <a href="{{ route('contact') }}" class="border-2 border-indigo-600 text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-600 hover:text-white transition-colors">
                <i class="fas fa-envelope mr-2"></i>
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection
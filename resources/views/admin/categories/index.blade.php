@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Manage Categories')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Categories</h2>
        <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add New Category
        </a>
    </div>

    <!-- Categories Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <!-- Success Message -->
            @if(session()->has('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if(session()->has('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($categories as $category)
                    <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-lg transition-shadow duration-200">
                        <!-- Category Image -->
                        <div class="flex items-center justify-center mb-4">
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-24 h-24 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <i class="fas fa-tag text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Category Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                            
                            <!-- Status Badge -->
                            <div class="flex items-center mb-2">
                                <span class="text-sm text-gray-500">Status:</span>
                                @if($category->is_active)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-circle text-green-500 mr-1" style="font-size: 6px;"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-circle text-red-500 mr-1" style="font-size: 6px;"></i>
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-4">
                                {{ Str::limit($category->description ?? 'No description available', 100) }}
                            </p>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Products:</span>
                                    <span class="font-semibold text-gray-900">{{ $category->products_count ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Sort Order:</span>
                                    <span class="font-semibold text-gray-900">{{ $category->sort_order ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="text-indigo-600 hover:text-indigo-700 font-medium">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-700 font-medium">
                                        <i class="fas fa-trash mr-1"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500">
                                    ID: #{{ $category->id }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class="fas fa-tag text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No Categories Found</h3>
                        <p class="text-gray-500 mb-6">Get started by creating your first category.</p>
                        <a href="{{ route('admin.categories.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Create Category
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($categories->hasPages())
                <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-700">
                        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} 
                        of {{ $categories->total() }} categories
                    </div>
                    <div class="flex items-center space-x-2">
                        @when($categories->currentPage > 1, function ($page) use ($categories) {
                            <a href="{{ $categories->previousPageUrl($page) }}" 
                               class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        })
                        
                        <span class="px-4 py-2 text-sm text-gray-700">
                            Page {{ $categories->currentPage() }}
                        </span>
                        
                        @when($categories->currentPage < $categories->lastPage(), function ($page) use ($categories) {
                            <a href="{{ $categories->nextPageUrl($page) }}" 
                               class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Next
                                <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        })
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product: ' . $product->name)

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Basic Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- SKU -->
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('sku')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Barcode -->
            <div>
                <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">Barcode</label>
                <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $product->barcode) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('barcode')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select name="category_id" id="category_id" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" id="description" rows="6" required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Short Description -->
            <div>
                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                <textarea name="short_description" id="short_description" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('short_description', $product->short_description) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Used for product listings and social sharing</p>
                @error('short_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pricing -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Pricing</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Regular Price *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" name="price" id="price" step="0.01" min="0" required
                                   class="w-full pl-8 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('price', $product->price) }}">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="compare_price" class="block text-sm font-medium text-gray-700 mb-2">Compare Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" name="compare_price" id="compare_price" step="0.01" min="0"
                                   class="w-full pl-8 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('compare_price', $product->compare_price) }}">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Show as "was" price</p>
                        @error('compare_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">Cost Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" name="cost_price" id="cost_price" step="0.01" min="0"
                                   class="w-full pl-8 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('cost_price', $product->cost_price) }}">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">For internal calculations</p>
                        @error('cost_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Inventory</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                        <input type="number" name="quantity" id="quantity" min="0" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('quantity', $product->quantity) }}">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock_alert_threshold" class="block text-sm font-medium text-gray-700 mb-2">Low Stock Alert</label>
                        <input type="number" name="stock_alert_threshold" id="stock_alert_threshold" min="0"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('stock_alert_threshold', $product->stock_alert_threshold ?? 5) }}"
                               placeholder="Alert when stock reaches this amount">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="track_quantity" id="track_quantity" value="1"
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                           {{ old('track_quantity', $product->track_quantity) ? 'checked' : '' }}>
                    <label for="track_quantity" class="ml-2 text-sm text-gray-700">Track quantity</label>
                </div>
            </div>

            <!-- Status -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Status</h3>
                
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label for="is_featured" class="ml-2 text-sm text-gray-700">Featured Product</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Images & SEO -->
        <div class="space-y-6">
            <!-- Product Images -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Product Images</h3>
                
                <!-- Current Images -->
                <div class="space-y-4 mb-6">
                    @foreach($product->images->sortBy('sort_order') as $image)
                        <div class="relative group">
                            <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" 
                                 class="w-full h-32 object-cover rounded-lg">
                            
                            @if($image->is_primary)
                                <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                                    Primary
                                </span>
                            @endif

                            <div class="mt-2 flex items-center space-x-2">
                                <button type="button" onclick="setPrimary({{ $image->id }})" 
                                        class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                    Set Primary
                                </button>
                                
                                <button type="button" onclick="removeImage({{ $image->id }})" 
                                        class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                    Remove
                                </button>
                            </div>

                            <input type="radio" name="primary_image" value="{{ $image->id }}" 
                                   {{ $image->is_primary ? 'checked' : '' }} class="hidden">
                        </div>
                    @endforeach
                </div>

                <!-- Upload New Images -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Upload New Images</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-500">
                        Maximum 10 images. JPG, PNG, GIF, WebP formats.
                    </p>
                    @error('images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" maxlength="255"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('meta_title', $product->meta_title) }}"
                               placeholder="{{ $product->name }} | Your Store">
                        <p class="mt-1 text-xs text-gray-500">Recommended: 50-60 characters</p>
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" maxlength="500"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="{{ Str::limit(strip_tags($product->description), 160) }}">{{ old('meta_description', $product->meta_description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="mt-8 flex justify-end space-x-3">
        <a href="{{ route('admin.products.index') }}" 
           class="bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400">
            Cancel
        </a>
        <button type="submit" 
                class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-save mr-2"></i>
            Update Product
        </button>
    </div>
</form>

@section('scripts')
<script>
    function setPrimary(imageId) {
        document.querySelectorAll('input[name="primary_image"]').forEach(radio => {
            radio.checked = radio.value == imageId;
        });
    }

    function removeImage(imageId) {
        if (confirm('Are you sure you want to remove this image?')) {
            // Create hidden input for deletion
            const form = document.querySelector('form');
            const existingInput = form.querySelector('input[name="delete_images[]"]');
            
            if (existingInput) {
                existingInput.value = imageId;
            } else {
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_images[]';
                deleteInput.value = imageId;
                form.appendChild(deleteInput);
            }
        }
    }
</script>
@endsection
@extends('admin.layouts.app')

@section('title', 'Create Product')
@section('page-title', 'Create New Product')

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    
    <!-- Basic Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-info-circle mr-3 text-indigo-600"></i>
            Basic Information
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Product Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}" 
                       required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                       placeholder="Enter product name">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- SKU -->
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                    SKU <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="sku" 
                       id="sku" 
                       value="{{ old('sku') }}" 
                       required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                       placeholder="Enter SKU">
                @error('sku')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Description Fields -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Short Description -->
            <div>
                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                    Short Description
                </label>
                <textarea name="short_description" 
                          id="short_description" 
                          rows="3"
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                          placeholder="Brief description for product listings">{{ old('short_description') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Used in product listings and social sharing (max 255 characters)</p>
            </div>
            
            <!-- Full Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          required
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                          placeholder="Detailed product description">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Category Selection -->
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                Category <span class="text-red-500">*</span>
            </label>
            <select name="category_id" 
                    id="category_id" 
                    required
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                <option value="">Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Pricing & Inventory -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pricing -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-dollar-sign mr-3 text-green-600"></i>
                Pricing
            </h2>
            
            <div class="space-y-4">
                <!-- Regular Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        Regular Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500 text-sm">$</span>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               step="0.01" 
                               min="0" 
                               required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 pl-8"
                               placeholder="0.00"
                               value="{{ old('price') }}">
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Compare Price -->
                <div>
                    <label for="compare_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Compare Price
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500 text-sm">$</span>
                        <input type="number" 
                               name="compare_price" 
                               id="compare_price" 
                               step="0.01" 
                               min="0"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 pl-8"
                               placeholder="0.00"
                               value="{{ old('compare_price') }}">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Show as "was" price on sale</p>
                    @error('compare_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Inventory -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-boxes mr-3 text-purple-600"></i>
                Inventory & Stock
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Stock Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        Stock Quantity <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="quantity" 
                           id="quantity" 
                           min="0"
                           required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                           placeholder="0"
                           value="{{ old('quantity', 0) }}">
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Stock Alert Threshold -->
                <div>
                    <label for="stock_alert_threshold" class="block text-sm font-medium text-gray-700 mb-2">
                        Low Stock Alert
                    </label>
                    <input type="number" 
                           name="stock_alert_threshold" 
                           id="stock_alert_threshold" 
                           min="0"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                           placeholder="Alert when stock reaches this amount"
                           value="{{ old('stock_alert_threshold', 5) }}">
                    <p class="mt-1 text-xs text-gray-500">Send notification when stock falls below this number</p>
                    @error('stock_alert_threshold')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Stock Tracking Toggle -->
            <div class="md:col-span-2">
                <div class="flex items-center space-x-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="track_quantity" 
                               id="track_quantity" 
                               value="1"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               {{ old('track_quantity') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm font-medium text-gray-700">Track stock quantity</span>
                    </label>
                    
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="allow_backorders" 
                               id="allow_backorders" 
                               value="1"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               {{ old('allow_backorders') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm font-medium text-gray-700">Allow backorders</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Images -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-image mr-3 text-blue-600"></i>
            Product Images
        </h2>
        
        <!-- Image Upload Area -->
        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-400 transition-colors">
            <div class="space-y-4">
                <!-- Upload Button -->
                <div>
                    <label for="images" class="cursor-pointer">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-indigo-200 transition-colors">
                                <i class="fas fa-cloud-upload-alt text-indigo-600 text-2xl"></i>
                            </div>
                            <span class="text-lg font-medium text-gray-700">Click to upload images</span>
                            <span class="text-sm text-gray-500">or drag and drop</span>
                        </div>
                        <input type="file" 
                               name="images[]" 
                               id="images" 
                               multiple 
                               accept="image/*"
                               class="hidden">
                        @error('images')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </label>
                </div>
                
                <!-- Image Preview Area -->
                <div id="imagePreview" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Image previews will be inserted here by JavaScript -->
                </div>
                
                <!-- Upload Guidelines -->
                <div class="text-left">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Upload Guidelines:</strong>
                    </p>
                    <ul class="text-sm text-gray-500 mt-2 space-y-1 list-disc list-inside">
                        <li>Maximum 10 images per product</li>
                        <li>Supported formats: JPG, PNG, GIF, WebP</li>
                        <li>Recommended size: 1000x1000px</li>
                        <li>File size limit: 5MB per image</li>
                        <li>First image will be set as primary</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Existing Images (for edit mode) -->
        @if(isset($product) && $product->images && $product->images->count() > 0)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($product->images as $image)
                        <div class="relative group">
                            <img src="{{ $image->url }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-24 object-cover rounded-lg">
                            
                            @if($image->is_primary)
                                <span class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                    Primary
                                </span>
                            @endif
                            
                            <button type="button" 
                                    onclick="removeExistingImage({{ $image->id }})" 
                                    class="absolute top-2 left-2 bg-red-500 text-white rounded-full w-6 h-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Product Status -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-toggle-on mr-3 text-orange-600"></i>
            Product Status
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Active Status -->
            <div>
                <div class="flex items-center space-x-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               value="1"
                               class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-2"
                               {{ old('is_active') ? 'checked' : '' }}>
                        <span class="ml-3 text-sm font-medium text-gray-700">Product Active</span>
                    </label>
                    <div class="ml-auto">
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            Visible to customers
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Featured Status -->
            <div>
                <div class="flex items-center space-x-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="is_featured" 
                               id="is_featured" 
                               value="1"
                               class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-2"
                               {{ old('is_featured') ? 'checked' : '' }}>
                        <span class="ml-3 text-sm font-medium text-gray-700">Featured Product</span>
                    </label>
                    <div class="ml-auto">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                            Shown on homepage
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Digital Product -->
            <div>
                <div class="flex items-center space-x-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="is_digital" 
                               id="is_digital" 
                               value="1"
                               class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-2"
                               {{ old('is_digital') ? 'checked' : '' }}>
                        <span class="ml-3 text-sm font-medium text-gray-700">Digital Product</span>
                    </label>
                    <div class="ml-auto">
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                            No shipping required
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status Explanations -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="text-sm text-gray-600 space-y-2">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                    <div>
                        <strong>Active:</strong> Product is visible and available for purchase
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-star text-yellow-600 mr-2 mt-1"></i>
                    <div>
                        <strong>Featured:</strong> Product will be highlighted and shown in featured sections
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-download text-purple-600 mr-2 mt-1"></i>
                    <div>
                        <strong>Digital:</strong> Downloadable product with no shipping required
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-4 pt-8">
        <a href="{{ route('admin.products.index') }}" 
           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-times mr-2"></i>
            Cancel
        </a>
        <button type="submit" 
                class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
            <i class="fas fa-save mr-2"></i>
            {{ isset($product) ? 'Update Product' : 'Create Product' }}
        </button>
    </div>
</form>

@section('scripts')
<script>
    // Image upload and preview functionality
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const files = Array.from(e.target.files);
        
        // Clear existing previews
        preview.innerHTML = '';
        
        // Limit to 10 images
        const maxFiles = 10;
        const filesToProcess = files.slice(0, maxFiles);
        
        filesToProcess.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'relative group';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-32 object-cover rounded-lg';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 opacity-0 group-hover:opacity-100 transition-opacity';
                    removeBtn.innerHTML = '<i class="fas fa-times text-xs"></i>';
                    removeBtn.onclick = function() {
                        previewContainer.remove();
                    };
                    
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'mt-2 text-xs text-gray-600';
                    fileInfo.innerHTML = `
                        <div class="font-medium">${file.name}</div>
                        <div>${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                    `;
                    
                    previewContainer.appendChild(img);
                    previewContainer.appendChild(removeBtn);
                    previewContainer.appendChild(fileInfo);
                    preview.appendChild(previewContainer);
                };
                
                reader.readAsDataURL(file);
            }
        });
    });

    // Drag and drop functionality
    const dropZone = document.querySelector('[for="images"]');
    
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('border-indigo-400', 'bg-indigo-50');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-indigo-400', 'bg-indigo-50');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-indigo-400', 'bg-indigo-50');
        
        const files = Array.from(e.dataTransfer.files);
        const input = document.getElementById('images');
        input.files = files;
        
        // Trigger change event to show previews
        const event = new Event('change', { bubbles: true });
        input.dispatchEvent(event);
    });

    function removeExistingImage(imageId) {
        // This would need to be implemented based on your specific requirements
        console.log('Remove image:', imageId);
    }
</script>
@endsection
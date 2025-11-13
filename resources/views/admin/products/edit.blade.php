@extends('layouts.admin')

@section('page-title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product: {{ $product->name }}</h1>
        <div>
            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i>View Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <!-- Product Form -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
            <div class="d-flex gap-2">
                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }} fs-6">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>
                <span class="badge bg-{{ $product->is_featured ? 'warning' : 'secondary' }} fs-6">
                    {{ $product->is_featured ? 'Featured' : 'Regular' }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                       id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                       id="stock_quantity" name="stock_quantity"
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                       min="0" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Regular Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price"
                                           value="{{ old('price', $product->price) }}"
                                           step="0.01" min="0" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                                           id="sale_price" name="sale_price"
                                           value="{{ old('sale_price', $product->sale_price) }}"
                                           step="0.01" min="0">
                                </div>
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty if no sale price</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror"
                                      id="short_description" name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Brief description for product listings (max 500 characters)</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sidebar Options -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Product Options</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Product
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                               value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Product
                                        </label>
                                    </div>
                                </div>

                                                                @if($product->main_image)
                                <div class="mb-3">
                                    <label class="form-label">Current Main Image</label>
                                    <div class="border rounded p-2">
                                        <img src="{{ $product->main_image }}" alt="Current main image"
                                             class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label for="main_image_file" class="form-label">Upload New Main Image</label>
                                    <input type="file" class="form-control @error('main_image_file') is-invalid @enderror"
                                           id="main_image_file" name="main_image_file" accept="image/*">
                                    @error('main_image_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Upload a JPG, PNG, GIF (max 2MB) to replace current image</small>
                                </div>

                                

                                                                @if($product->gallery_images && count($product->gallery_images) > 0)
                                <div class="mb-3">
                                    <label class="form-label">Current Gallery Images</label>
                                    <div class="row">
                                        @foreach($product->gallery_images as $image)
                                        <div class="col-md-4 mb-2">
                                            <img src="{{ $image }}" alt="Gallery image"
                                                 class="img-thumbnail" style="height: 80px; object-fit: cover;">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label for="gallery_images_files" class="form-label">Add More Gallery Images</label>
                                    <input type="file" class="form-control @error('gallery_images_files') is-invalid @enderror"
                                           id="gallery_images_files" name="gallery_images_files[]" accept="image/*" multiple>
                                    @error('gallery_images_files')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Upload multiple JPG, PNG, GIF files (max 2MB each)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="gallery_images" class="form-label">Gallery Images URLs</label>
                                    <textarea class="form-control @error('gallery_images') is-invalid @enderror"
                                              id="gallery_images" name="gallery_images" rows="3"
                                              placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg">{{ old('gallery_images', is_array($product->gallery_images) ? implode("\n", $product->gallery_images) : $product->gallery_images) }}</textarea>
                                    @error('gallery_images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">One URL per line (this will replace existing images)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="specifications" class="form-label">Specifications (JSON)</label>
                                    <textarea class="form-control @error('specifications') is-invalid @enderror"
                                              id="specifications" name="specifications" rows="4"
                                              placeholder='{"vase_included": true, "care_instructions": "Keep in cool place"}'>{{ old('specifications', is_array($product->specifications) ? json_encode($product->specifications, JSON_PRETTY_PRINT) : $product->specifications) }}</textarea>
                                    @error('specifications')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Enter valid JSON format</small>
                                </div>
                            </div>
                        </div>

                        <!-- Product Stats -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Product Statistics</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="text-primary fw-bold">{{ $product->stock_quantity }}</div>
                                        <small class="text-muted">In Stock</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-success fw-bold">₱{{ number_format($product->current_price, 2) }}</div>
                                        <small class="text-muted">Current Price</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Last updated: {{ $product->updated_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.index') }}" class="btn-action btn-secondary btn-action-lg">
                                    <i class="fas fa-times"></i>
                                    <span>Cancel</span>
                                </a>
                                <button type="submit" class="btn-action btn-primary btn-action-lg">
                                    <i class="fas fa-save"></i>
                                    <span>Update Product</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Validate sale price is less than regular price
document.getElementById('sale_price').addEventListener('input', function() {
    const regularPrice = parseFloat(document.getElementById('price').value) || 0;
    const salePrice = parseFloat(this.value) || 0;

    if (salePrice > 0 && salePrice >= regularPrice) {
        this.setCustomValidity('Sale price must be less than regular price');
    } else {
        this.setCustomValidity('');
    }
});

// Validate JSON format for specifications
document.getElementById('specifications').addEventListener('blur', function() {
    const value = this.value.trim();
    if (value) {
        try {
            JSON.parse(value);
            this.setCustomValidity('');
        } catch (e) {
            this.setCustomValidity('Invalid JSON format');
        }
    } else {
        this.setCustomValidity('');
    }
});

// Auto-update stock status
document.getElementById('stock_quantity').addEventListener('input', function() {
    const quantity = parseInt(this.value) || 0;
    const stockStatus = document.querySelector('.text-primary.fw-bold');
    if (stockStatus) {
        stockStatus.textContent = quantity;
        stockStatus.className = quantity > 0 ? 'text-success fw-bold' : 'text-danger fw-bold';
    }
});
</script>
@endpush
@endsection

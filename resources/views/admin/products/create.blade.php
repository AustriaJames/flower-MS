@extends('layouts.admin')

@section('page-title', 'Create New Product')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Create New Product</h1>
            <a href="{{ route('admin.products.index') }}" class="btn-action btn-secondary btn-action-lg">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Products</span>
            </a>
        </div>
    </div>

    <!-- Product Form -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                       id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}"
                                       min="0" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="price" class="form-label">Regular Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}"
                                           step="0.01" min="0" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                                           id="sale_price" name="sale_price" value="{{ old('sale_price') }}"
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
                                      id="short_description" name="short_description" rows="2">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Brief description for product listings (max 500 characters)</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
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
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Product
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                               value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Product
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="main_image_file" class="form-label">Main Image Upload</label>
                                    <input type="file" class="form-control @error('main_image_file') is-invalid @enderror"
                                           id="main_image_file" name="main_image_file" accept="image/*">
                                    @error('main_image_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Upload a JPG, PNG, GIF (max 2MB) or use URL below</small>
                                </div>

                                <div class="mb-3">
                                    <label for="gallery_images_files" class="form-label">Gallery Images Upload</label>
                                    <input type="file" class="form-control @error('gallery_images_files') is-invalid @enderror"
                                           id="gallery_images_files" name="gallery_images_files[]" accept="image/*" multiple>
                                    @error('gallery_images_files')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Upload multiple JPG, PNG, GIF files (max 2MB each)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="gallery_images" class="form-label">Gallery Images URLs (Alternative)</label>
                                    <textarea class="form-control @error('gallery_images') is-invalid @enderror"
                                              id="gallery_images" name="gallery_images" rows="3"
                                              placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg">{{ old('gallery_images') }}</textarea>
                                    @error('gallery_images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">One URL per line (use this for external image links)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="specifications" class="form-label">Specifications (JSON)</label>
                                    <textarea class="form-control @error('specifications') is-invalid @enderror"
                                              id="specifications" name="specifications" rows="4"
                                              placeholder='{"vase_included": true, "care_instructions": "Keep in cool place"}'>{{ old('specifications') }}</textarea>
                                    @error('specifications')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Enter valid JSON format</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.products.index') }}" class="btn-action btn-secondary btn-action-lg">
                                <i class="fas fa-times"></i>
                                <span>Cancel</span>
                            </a>
                            <button type="submit" class="btn-action btn-primary btn-action-lg">
                                <i class="fas fa-save"></i>
                                <span>Create Product</span>
                            </button>
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

// Image preview functionality
document.getElementById('main_image_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const existingPreview = document.getElementById('main_image_preview');
            if (existingPreview) existingPreview.remove();

            const previewDiv = document.createElement('div');
            previewDiv.id = 'main_image_preview';
            previewDiv.className = 'mt-2 text-center';
            previewDiv.innerHTML = `
                <div class="border rounded p-2">
                    <img src="${e.target.result}" alt="Main image preview"
                         class="img-thumbnail" style="max-height: 150px;">
                    <div class="small text-muted mt-1">Preview</div>
                </div>
            `;
            e.target.parentNode.insertBefore(previewDiv, e.target.nextSibling);
        };
        reader.readAsDataURL(file);
    }
});

// Gallery images preview
document.getElementById('gallery_images_files').addEventListener('change', function(e) {
    const files = e.target.files;
    if (files.length > 0) {
        const existingPreview = document.getElementById('gallery_images_preview');
        if (existingPreview) existingPreview.remove();

        const previewDiv = document.createElement('div');
        previewDiv.id = 'gallery_images_preview';
        previewDiv.className = 'mt-2';
        previewDiv.innerHTML = '<div class="small text-muted mb-2">Gallery Preview:</div><div class="row"></div>';

        const row = previewDiv.querySelector('.row');

        Array.from(files).forEach((file, index) => {
            if (index < 6) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-2 col-4 mb-2';
                    col.innerHTML = `
                        <div class="border rounded p-1">
                            <img src="${e.target.result}" alt="Gallery preview ${index + 1}"
                                 class="img-thumbnail w-100" style="height: 60px; object-fit: cover;">
                        </div>
                    `;
                    row.appendChild(col);
                };
                reader.readAsDataURL(file);
            }
        });

        if (files.length > 6) {
            const col = document.createElement('div');
            col.className = 'col-md-2 col-4 mb-2';
            col.innerHTML = `
                <div class="border rounded p-1 d-flex align-items-center justify-content-center bg-light" style="height: 60px;">
                    <small class="text-muted">+${files.length - 6} more</small>
                </div>
            `;
            row.appendChild(col);
        }

        e.target.parentNode.insertBefore(previewDiv, e.target.nextSibling);
    }
});
</script>
@endpush
@endsection

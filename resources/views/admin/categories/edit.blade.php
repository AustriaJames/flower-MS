@extends('layouts.admin')

@section('page-title', 'Edit Category')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Category: {{ $category->name }}</h1>
        <div>
            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i>View Category
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <!-- Category Form -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
            <div class="d-flex gap-2">
                <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }} fs-6">
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                </span>
                <span class="badge bg-info fs-6">{{ $category->products_count }} Products</span>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4" required>{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sidebar Options -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Category Options</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Icon <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                               id="icon" name="icon" value="{{ old('icon', $category->icon) }}" required>
                                        <button type="button" class="btn btn-outline-secondary" id="iconPreview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Font Awesome icon class (e.g., fas fa-heart)</small>

                                    <!-- Icon Preview -->
                                    <div class="mt-2 text-center">
                                        <div id="iconDisplay" class="p-3 border rounded">
                                            <i class="{{ $category->icon }} fa-3x text-primary"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                           id="sort_order" name="sort_order"
                                           value="{{ old('sort_order', $category->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Category
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Category Stats -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Category Statistics</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="text-primary fw-bold">{{ $category->products_count }}</div>
                                        <small class="text-muted">Products</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-success fw-bold">{{ $category->sort_order }}</div>
                                        <small class="text-muted">Sort Order</small>
                                    </div>
                                </div>

                                <hr>

                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="text-info fw-bold">{{ $category->products->where('is_active', true)->count() }}</div>
                                        <small class="text-muted">Active Products</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-warning fw-bold">{{ $category->products->where('is_featured', true)->count() }}</div>
                                        <small class="text-muted">Featured Products</small>
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
                                <small class="text-muted">Last updated: {{ $category->updated_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Category
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
// Live icon preview
document.getElementById('icon').addEventListener('input', function() {
    const iconClass = this.value.trim();
    const iconDisplay = document.getElementById('iconDisplay');

    if (iconClass) {
        iconDisplay.innerHTML = `<i class="${iconClass} fa-3x text-primary"></i>`;
    } else {
        iconDisplay.innerHTML = '<i class="fas fa-question fa-3x text-muted"></i>';
    }
});

// Icon preview button
document.getElementById('iconPreview').addEventListener('click', function() {
    const iconInput = document.getElementById('icon');
    const iconClass = iconInput.value.trim();

    if (iconClass) {
        const iconDisplay = document.getElementById('iconDisplay');
        iconDisplay.innerHTML = `<i class="${iconClass} fa-3x text-primary"></i>`;
    }
});

// Auto-update sort order display
document.getElementById('sort_order').addEventListener('input', function() {
    const sortOrder = parseInt(this.value) || 0;
    const sortOrderDisplay = document.querySelector('.text-success.fw-bold');
    if (sortOrderDisplay) {
        sortOrderDisplay.textContent = sortOrder;
    }
});

// Auto-update category name in page title
document.getElementById('name').addEventListener('input', function() {
    const name = this.value.trim();
    const pageTitle = document.querySelector('h1');
    if (pageTitle) {
        pageTitle.textContent = 'Edit Category: ' + (name || 'Category');
    }
});
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('page-title', 'Category Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Category Details: {{ $category->name }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit Category
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <!-- Category Information -->
    <div class="row">
        <!-- Main Category Details -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
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
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary">{{ $category->name }}</h5>
                            <p class="text-muted mb-2">Slug: {{ $category->slug }}</p>
                            <p class="text-muted mb-3">{{ $category->description }}</p>

                            <div class="mb-3">
                                <strong>Sort Order:</strong>
                                <span class="badge bg-secondary ms-2">{{ $category->sort_order }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Created:</strong>
                                <span class="text-muted ms-2">{{ $category->created_at->format('M d, Y \a\t h:i A') }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Last Updated:</strong>
                                <span class="text-muted ms-2">{{ $category->updated_at->format('M d, Y \a\t h:i A') }}</span>
                            </div>
                        </div>

                        <div class="col-md-4 text-center">
                            <div class="p-4 border rounded">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} fs-1 text-primary"></i>
                                    <p class="mt-2 mb-0 text-muted">{{ $category->icon }}</p>
                                @else
                                    <i class="bi bi-folder fs-1 text-primary"></i>
                                    <p class="mt-2 mb-0 text-muted">No icon set</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products in this Category -->
            @if($category->products->count() > 0)
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Products in this Category</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products->take(10) as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->main_image)
                                                <img src="{{ $product->main_image }}" alt="{{ $product->name }}"
                                                     class="img-thumbnail me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center me-3"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->is_featured)
                                                    <span class="badge bg-warning ms-2">Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $product->sku }}</code>
                                    </td>
                                    <td>
                                        @if($product->is_on_sale)
                                            <span class="text-decoration-line-through text-muted">₱{{ number_format($product->price, 2) }}</span>
                                            <br>
                                            <strong class="text-danger">₱{{ number_format($product->current_price, 2) }}</strong>
                                        @else
                                            <strong>₱{{ number_format($product->current_price, 2) }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product) }}"
                                           class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($category->products->count() > 10)
                    <div class="text-center mt-3">
                        <p class="text-muted">Showing first 10 products. Total: {{ $category->products->count() }} products</p>
                        <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" class="btn btn-primary">
                            View All Products
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Products Found</h5>
                    <p class="text-muted">This category doesn't have any products yet.</p>
                    <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Product
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Category Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-primary fw-bold fs-4">{{ $category->products_count }}</div>
                            <small class="text-muted">Total Products</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-success fw-bold fs-4">{{ $category->sort_order }}</div>
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

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $category->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} me-2"></i>
                                {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-info w-100">
                            <i class="fas fa-edit me-2"></i>Edit Category
                        </a>

                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-success w-100">
                            <i class="fas fa-plus me-2"></i>Add Product
                        </a>

                        @if($category->products_count == 0)
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Category
                            </button>
                        </form>
                        @else
                        <button type="button" class="btn btn-danger w-100" disabled title="Cannot delete category with products">
                            <i class="fas fa-trash me-2"></i>Delete Category
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Category Icon Preview -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Icon Preview</h6>
                </div>
                <div class="card-body text-center">
                    <div class="p-4 border rounded">
                        <i class="{{ $category->icon }} fa-5x text-primary"></i>
                        <p class="mt-3 mb-0 text-muted">{{ $category->icon }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

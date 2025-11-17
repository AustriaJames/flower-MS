@extends('layouts.admin')

@section('page-title', 'Product Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product Details: {{ $product->name }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <!-- Product Information -->
    <div class="row">
        <!-- Main Product Details -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }} fs-6">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="badge bg-{{ $product->is_featured ? 'warning' : 'secondary' }} fs-6">
                            {{ $product->is_featured ? 'Featured' : 'Regular' }}
                        </span>
                        <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }} fs-6">
                            {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">{{ $product->name }}</h5>
                            <p class="text-muted mb-3">{{ $product->short_description }}</p>

                            <div class="mb-3">
                                <strong>Category:</strong>
                                <span class="badge bg-info ms-2">{{ $product->category->name }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Price:</strong>
                                @if($product->is_on_sale)
                                    <span class="text-decoration-line-through text-muted ms-2">₱{{ number_format($product->price, 2) }}</span>
                                    <span class="text-danger fw-bold ms-2">₱{{ number_format($product->current_price, 2) }}</span>
                                    <span class="badge bg-danger ms-2">{{ $product->discount_percentage }}% OFF</span>
                                @else
                                    <span class="text-success fw-bold ms-2">₱{{ number_format($product->current_price, 2) }}</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>Stock:</strong>
                                <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }} ms-2">
                                    {{ $product->stock_quantity }} units
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            @if($product->main_image)
                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}"
                                     class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                     style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-primary">Description</h6>
                        <p>{{ $product->description }}</p>
                    </div>

                    @if($product->specifications && is_array($product->specifications))
                    <div class="mt-4">
                        <h6 class="text-primary">Specifications</h6>
                        <div class="row">
                            @foreach($product->specifications as $key => $value)
                            <div class="col-md-6 mb-2">
                                <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                <span class="ms-2">
                                    @if(is_bool($value))
                                        <span class="badge bg-{{ $value ? 'success' : 'secondary' }}">
                                            {{ $value ? 'Yes' : 'No' }}
                                        </span>
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Gallery Images -->
            @if($product->gallery_images && count($product->gallery_images) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Gallery Images</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($product->gallery_image_urls as $image)
                        <div class="col-md-4 mb-3">
                            <img src="{{ $image }}" alt="Gallery Image"
                                 class="img-fluid rounded shadow-sm" style="height: 150px; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Product Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-primary fw-bold fs-4">{{ $product->stock_quantity }}</div>
                            <small class="text-muted">In Stock</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-success fw-bold fs-4">₱{{ number_format($product->current_price, 2) }}</div>
                            <small class="text-muted">Current Price</small>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-info fw-bold">{{ $product->reviews->count() }}</div>
                            <small class="text-muted">Reviews</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-warning fw-bold">{{ $product->orderItems->count() }}</div>
                            <small class="text-muted">Orders</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Creator Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Created By</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-3"
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <strong>{{ $product->creator->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $product->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $product->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $product->is_active ? 'pause' : 'play' }} me-2"></i>
                                {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $product->is_featured ? 'outline-warning' : 'warning' }} w-100">
                                <i class="fas fa-star me-2"></i>
                                {{ $product->is_featured ? 'Remove Featured' : 'Make Featured' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-info w-100">
                            <i class="fas fa-edit me-2"></i>Edit Product
                        </a>

                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    @if($product->reviews->count() > 0)
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Customer Reviews</h6>
        </div>
        <div class="card-body">
            @foreach($product->reviews as $review)
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>{{ $review->user->name }}</strong>
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                            <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                        </div>
                    </div>
                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                </div>
                <p class="mt-2 mb-0">{{ $review->comment }}</p>
                @if($review->is_verified_purchase)
                    <span class="badge bg-success mt-2">Verified Purchase</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

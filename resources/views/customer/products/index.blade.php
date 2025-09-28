@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Filters</h5>

                    <!-- Search -->
                    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" style="color: #5D2B4C;">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search flowers..." value="{{ request('search') }}">
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" style="color: #5D2B4C;">Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" style="color: #5D2B4C;">Price Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" name="price_min" class="form-control" placeholder="Min" value="{{ request('price_min') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="price_max" class="form-control" placeholder="Max" value="{{ request('price_max') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Sort -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" style="color: #5D2B4C;">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold" style="color: #5D2B4C;">Order</label>
                            <select name="direction" class="form-select">
                                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>

                        <button type="submit" class="btn w-100 fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                    </form>

                    <!-- Clear Filters -->
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100" style="border-radius: 12px;">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold" style="color: #5D2B4C;">All Products</h2>
                <span class="text-muted">{{ $products->total() }} products found</span>
            </div>

            @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div class="product-image bg-light rounded p-4 mb-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-flower-tulip" style="font-size: 4rem; color: #CFB8BE;"></i>
                                    @endif
                                </div>
                                <h5 class="fw-bold" style="color: #5D2B4C;">{{ $product->name }}</h5>
                                <p class="text-muted small">{{ Str::limit($product->description, 50) }}</p>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $product->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-1 text-muted small">({{ $product->reviews->count() }})</span>
                                </div>
                                <h6 class="fw-bold" style="color: #5D2B4C;">₱{{ number_format($product->price, 2) }}</h6>
                            </div>

                            <div class="d-grid mb-3">
                                <a href="{{ route('products.show', $product) }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </div>

                            <div class="d-flex gap-2 mb-3 flex-wrap">
                                <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" onclick="addToWishlist({{ $product->id }})">
                                    <i class="fas fa-heart me-1"></i>Wishlist
                                </button>
                                <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" onclick="addToCart({{ $product->id }})">
                                    <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                                </button>
                            </div>

                            <div class="small text-muted">
                                <div>Category: {{ $product->category->name ?? 'N/A' }}</div>
                                <div class="text-success">In Stock</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-flower-tulip" style="font-size: 4rem; color: #CFB8BE;"></i>
                <h4 class="mt-3" style="color: #5D2B4C;">No products found</h4>
                <p class="text-muted">Try adjusting your filters or search terms.</p>
                <a href="{{ route('products.index') }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                    <i class="fas fa-refresh me-2"></i>Clear Filters
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    @guest
        // Show login modal for guests
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    @else
        // Add to cart for authenticated users
        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                console.log('Add to cart success:', data);
                if (data.success) {
                    showNotification('Product added to cart successfully!', 'success');
                    // Update cart count in navigation
                    updateCartCount();
                } else {
                    showNotification('Error adding product to cart: ' + (data.message || 'Unknown error'), 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Add to cart error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);

                if (xhr.status === 404) {
                    showNotification('Product not found. Please refresh the page.', 'error');
                } else if (xhr.status === 403) {
                    showNotification('Access denied. Please log in again.', 'error');
                } else if (xhr.status === 500) {
                    showNotification('Server error occurred. Please try again.', 'error');
                } else {
                    showNotification('Error adding product to cart. Please try again.', 'error');
                }
            }
        });
    @endguest
}

function addToWishlist(productId) {
    @guest
        // Show login modal for guests
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    @else
        // Add to wishlist for authenticated users
        $.ajax({
            url: '{{ route("wishlist.add") }}',
            type: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                console.log('Add to wishlist success:', data);
                if (data.success) {
                    showNotification('Product added to wishlist!', 'success');
                } else {
                    showNotification('Error adding product to wishlist: ' + (data.message || 'Unknown error'), 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Add to wishlist error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);

                if (xhr.status === 404) {
                    showNotification('Product not found. Please refresh the page.', 'error');
                } else if (xhr.status === 403) {
                    showNotification('Access denied. Please log in again.', 'error');
                } else if (xhr.status === 500) {
                    showNotification('Server error occurred. Please try again.', 'error');
                } else {
                    showNotification('Error adding product to wishlist. Please try again.', 'error');
                }
            }
        });
    @endguest
}

// updateCartCount function is now available globally from layouts/app.blade.php
</script>
@endsection

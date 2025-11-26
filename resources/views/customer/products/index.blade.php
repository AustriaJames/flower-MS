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
                        <input type="hidden" name="type" value="{{ $type ?? 'all' }}">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" style="color: #5D2B4C;">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search flowers..." value="{{ request('search') }}">
                        </div>
                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" style="color: #5D2B4C;">Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @endif
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
                    <a href="{{ route('products.index', ['type' => $type ?? 'regular']) }}" class="btn btn-outline-secondary w-100" style="border-radius: 12px;">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="dropdown">
                    <button class="btn fw-bold dropdown-toggle" type="button" id="productsDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 2rem; color: #5D2B4C; background: none; border: none;">
                        @if(($type ?? 'all') === 'all')
                            All Products
                        @elseif(($type ?? 'all') === 'regular')
                            Products
                        @elseif(($type ?? 'all') === 'occasions')
                            Occasions
                        @else
                            All Products
                        @endif
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                        <li><a class="dropdown-item" href="{{ route('products.index', ['type' => 'all']) }}">All Products</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['type' => 'regular']) }}">Products</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['type' => 'occasions']) }}">Occasions</a></li>
                    </ul>
                </div>
                @if(($type ?? 'regular') === 'occasions')
                    <span class="text-muted">
                        {{ isset($productsByCategory) ? collect($productsByCategory)->flatten(1)->count() : 0 }} products found
                    </span>
                @elseif(($type ?? 'all') === 'all')
                    <span class="text-muted">{{ $products->total() }} products found</span>
                @else
                    <span class="text-muted">{{ $products->total() }} products found</span>
                @endif
            </div>

            @if(($type ?? 'regular') === 'occasions')
                @if(isset($allOccasionProducts) && $allOccasionProducts->count())
                    <div class="carousel-wrapper position-relative mb-5">
                        <button class="carousel-arrow left" onclick="scrollCarousel(this, -1)"><i class="fas fa-chevron-left"></i></button>
                        <div class="product-carousel flex-nowrap overflow-auto" style="scroll-behavior: smooth;">
                            @foreach($allOccasionProducts as $product)
                            <div class="carousel-item-card">
                                <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px; min-width: 300px; max-width: 320px; margin: 0 10px;">
                                    <div class="card-body p-4">
                                        <div class="text-center mb-3">
                                            <div class="product-image bg-light rounded p-4 mb-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                                @if($product->main_image)
                                                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
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
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary product-action-btn">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                            <button type="button" class="btn btn-outline-secondary product-action-btn" onclick="addToWishlist({{ $product->id }})">
                                                <i class="fas fa-heart me-1"></i>Wishlist
                                            </button>
                                            <button type="button" class="btn btn-outline-success product-action-btn" onclick="addToCart({{ $product->id }})">
                                                <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-arrow right" onclick="scrollCarousel(this, 1)"><i class="fas fa-chevron-right"></i></button>
                    </div>
                @else
                    <div class="alert alert-info">No occasion products found.</div>
                @endif
            @else
                @if(isset($products) && $products->count() > 0)
                <div class="carousel-wrapper position-relative mb-5">
                    <button class="carousel-arrow left" onclick="scrollCarousel(this, -1)"><i class="fas fa-chevron-left"></i></button>
                    <div class="product-carousel flex-nowrap overflow-auto" style="scroll-behavior: smooth;">
                        @foreach($products as $product)
                        <div class="carousel-item-card">
                            <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px; min-width: 300px; max-width: 320px; margin: 0 10px;">
                                <div class="card-body p-4">
                                    <div class="text-center mb-3">
                                        <div class="product-image bg-light rounded p-4 mb-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                            @if($product->main_image)
                                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
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
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary product-action-btn">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                        <button type="button" class="btn btn-outline-secondary product-action-btn" onclick="addToWishlist({{ $product->id }})">
                                            <i class="fas fa-heart me-1"></i>Wishlist
                                        </button>
                                        <button type="button" class="btn btn-outline-success product-action-btn" onclick="addToCart({{ $product->id }})">
                                            <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-arrow right" onclick="scrollCarousel(this, 1)"><i class="fas fa-chevron-right"></i></button>
                </div>
                @endif
            @endif
    </div>
</div>

<style>
.carousel-wrapper {
    display: flex;
    align-items: center;
    position: relative;
}
.carousel-arrow {
    background: #fff;
    border: 1px solid #5D2B4C;
    color: #5D2B4C;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    box-shadow: 0 2px 8px rgba(93,43,76,0.08);
    transition: background 0.2s, color 0.2s;
}
.carousel-arrow.left {
    left: -20px;
}
.carousel-arrow.right {
    right: -20px;
}
.carousel-arrow:hover {
    background: #5D2B4C;
    color: #fff;
}
.product-carousel {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding-bottom: 10px;
    margin: 0 30px;
}
.carousel-item-card {
    flex: 0 0 auto;
    min-width: 300px;
    max-width: 320px;
    margin: 0 10px;
}
@media (max-width: 900px) {
    .carousel-item-card {
        min-width: 240px;
        max-width: 260px;
    }
    .carousel-arrow.left {
        left: -10px;
    }
    .carousel-arrow.right {
        right: -10px;
    }
    .product-carousel {
        margin: 0 10px;
    }
}
.product-action-btn {
    border-radius: 8px !important;
    min-width: 110px;
    min-height: 40px;
    margin: 0 2px 8px 2px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: box-shadow 0.2s;
    white-space: nowrap;
}
.product-action-btn i {
    font-size: 1.1rem;
}
.d-flex.justify-content-between.align-items-center.mt-3 {
    gap: 0.5rem;
    flex-wrap: wrap;
    max-width: 100%;
    justify-content: center !important;
}
@media (max-width: 600px) {
    .d-flex.justify-content-between.align-items-center.mt-3 {
        flex-direction: column;
        align-items: center !important;
        gap: 0.25rem;
    }
    .product-action-btn {
        width: 100%;
        margin: 0 0 8px 0;
    }
}
</style>
</style>
<script>
function scrollCarousel(btn, direction) {
    const wrapper = btn.closest('.carousel-wrapper');
    const carousel = wrapper.querySelector('.product-carousel');
    const card = carousel.querySelector('.carousel-item-card');
    if (!card) return;
    const scrollAmount = card.offsetWidth + 20; // card width + margin
    carousel.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
}

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

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Category Header -->
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3" style="color: #5D2B4C;">
                    <i class="fas fa-tags me-2"></i>{{ $category->name }}
                </h2>
                <p class="lead text-muted">{{ $category->description }}</p>
                <p class="text-muted">{{ $category->products->count() }} products available</p>
            </div>

            @if($category->products->count() > 0)
            <div class="row g-4">
                @foreach($category->products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px;">
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
                                <div class="text-success">In Stock</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Back to Categories -->
            <div class="text-center mt-5">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                    <i class="fas fa-arrow-left me-2"></i>Back to All Categories
                </a>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-flower-tulip" style="font-size: 4rem; color: #CFB8BE;"></i>
                <h4 class="mt-3" style="color: #5D2B4C;">No products in this category</h4>
                <p class="text-muted">We're working on adding products to this category. Check back soon!</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                        <i class="fas fa-tags me-2"></i>Browse Other Categories
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                        <i class="fas fa-th-large me-2"></i>Browse All Products
                    </a>
                </div>
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
                    alert('Product added to cart successfully!');
                    location.reload();
                } else {
                    alert('Error adding product to cart: ' + (data.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Add to cart error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);

                if (xhr.status === 404) {
                    alert('Product not found. Please refresh the page.');
                } else if (xhr.status === 403) {
                    alert('Access denied. Please log in again.');
                } else if (xhr.status === 500) {
                    alert('Server error occurred. Please try again.');
                } else {
                    alert('Error adding product to cart. Please try again.');
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
                    alert('Product added to wishlist!');
                } else {
                    alert('Error adding product to wishlist: ' + (data.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Add to wishlist error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);

                if (xhr.status === 404) {
                    alert('Product not found. Please refresh the page.');
                } else if (xhr.status === 403) {
                    alert('Access denied. Please log in again.');
                } else if (xhr.status === 500) {
                    alert('Server error occurred. Please try again.');
                } else {
                    alert('Error adding product to wishlist. Please try again.');
                }
            }
        });
    @endguest
}
</script>
@endsection

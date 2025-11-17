@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #5D2B4C;">
                <i class="fas fa-heart me-2"></i>My Wishlist
            </h2>

            @if($wishlistItems->count() > 0)
            <div class="row g-4">
                @foreach($wishlistItems as $item)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div class="product-image bg-light rounded p-4 mb-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                        @if($item->product->main_image)
                                            <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                                        @else
                                        <i class="fas fa-flower-tulip" style="font-size: 4rem; color: #CFB8BE;"></i>
                                    @endif
                                </div>
                                <h5 class="fw-bold" style="color: #5D2B4C;">{{ $item->product->name }}</h5>
                                <p class="text-muted small">{{ Str::limit($item->product->description, 50) }}</p>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $item->product->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-1 text-muted small">({{ $item->product->reviews->count() }})</span>
                                </div>
                                <h6 class="fw-bold" style="color: #5D2B4C;">₱{{ number_format($item->product->price, 2) }}</h6>
                            </div>

                            <div class="d-grid mb-3">
                                <a href="{{ route('products.show', $item->product) }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </div>

                            <div class="d-flex gap-2 mb-3 flex-wrap">
                                <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" onclick="addToCart({{ $item->product->id }})">
                                    <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                                </button>
                                <button class="btn btn-outline-danger flex-fill" style="border-radius: 12px; min-width: 120px;" onclick="removeFromWishlist({{ $item->id }})">
                                    <i class="fas fa-heart-broken me-1"></i>Remove
                                </button>
                            </div>

                            <div class="small text-muted">
                                <div>Category: {{ $item->product->category->name ?? 'N/A' }}</div>
                                <div class="text-success">In Stock</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <button class="btn btn-outline-danger" style="border-radius: 12px;" onclick="clearWishlist()">
                    <i class="fas fa-trash me-2"></i>Clear Wishlist
                </button>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-heart" style="font-size: 4rem; color: #CFB8BE;"></i>
                <h4 class="mt-3" style="color: #5D2B4C;">Your wishlist is empty</h4>
                <p class="text-muted">Start adding products to your wishlist to save them for later!</p>
                <a href="{{ route('products.index') }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
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
            if (data.success) {
                showNotification('Product added to cart successfully!', 'success');
                updateCartCount();
            } else {
                showNotification('Error adding product to cart: ' + (data.message || 'Unknown error'), 'error');
            }
        },
        error: function(xhr) {
            showNotification('Error adding product to cart. Please try again.', 'error');
        }
    });
}

// --------------------------------------------------
// ONLY THIS PART IS FIXED (REMOVE WISHLIST WORKING)
// --------------------------------------------------

function removeFromWishlist(wishlistId) {
    if (!confirm("Are you sure you want to remove this item from your wishlist?")) {
        return;
    }

    $.ajax({
        url: "{{ url('/wishlist/remove') }}/" + wishlistId,
        type: "POST",
        data: {
            _method: "DELETE",
            _token: "{{ csrf_token() }}"
        },
        success: function(data) {
            if (data.success) {
                showNotification("Item removed successfully!", "success");
                location.reload();
            } else {
                showNotification("Failed to remove item.", "error");
            }
        },
        error: function(xhr) {
            showNotification("Error removing wishlist item.", "error");
        }
    });
}

// --------------------------------------------------

function clearWishlist() {
    showNotification('Clear wishlist functionality coming soon!', 'info');
}

// updateCartCount function from app layout
</script>
@endsection

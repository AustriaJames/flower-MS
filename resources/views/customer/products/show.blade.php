@extends('layouts.app')

@push('styles')
<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input[type="radio"]:checked ~ label {
    color: #ffc107;
}

.rating-input input[type="radio"]:checked ~ label i {
    font-weight: 900;
}

.rating-input label i {
    transition: all 0.2s;
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="product-image" style="height: 400px; background: #F0F2F5; display: flex; align-items: center; justify-content: center;">
                    @if($product->main_image)
                        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                    @else
                        <i class="fas fa-flower-tulip" style="font-size: 8rem; color: #CFB8BE;"></i>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-3" style="color: #5D2B4C;">{{ $product->name }}</h2>

                    <div class="mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $product->rating)
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                        <span class="ms-2 text-muted">({{ $product->reviews->count() }} reviews)</span>
                    </div>

                    <h3 class="fw-bold mb-4" style="color: #5D2B4C;">₱{{ number_format($product->price, 2) }}</h3>

                    <p class="text-muted mb-4">{{ $product->description }}</p>

                    <div class="mb-4">
                        <h6 class="fw-semibold" style="color: #5D2B4C;">Category:</h6>
                        <p class="text-muted">{{ $product->category->name ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold" style="color: #5D2B4C;">Availability:</h6>
                        <span class="badge bg-success fs-6">In Stock</span>
                    </div>

                    <!-- Add to Cart Section -->
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2" style="color: #5D2B4C;">Quantity:</h6>
                        <div class="input-group mb-3" style="max-width: 200px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">-</button>
                            <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="99">
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px; padding: 12px;" onclick="addToCart()">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px;" onclick="addToWishlist()">
                            <i class="fas fa-heart me-1"></i>Add to Wishlist
                        </button>
                        <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px;" onclick="shareProduct()">
                            <i class="fas fa-share me-1"></i>Share
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <ul class="nav nav-tabs border-0" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" style="color: #5D2B4C;">
                                <i class="fas fa-info-circle me-2"></i>Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" style="color: #5D2B4C;">
                                <i class="fas fa-star me-2"></i>Reviews ({{ $product->reviews->count() }})
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabsContent">
                        <!-- Details Tab -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-semibold" style="color: #5D2B4C;">Product Information</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</li>
                                        <li class="mb-2"><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</li>
                                        <li class="mb-2"><strong>Price:</strong> ₱{{ number_format($product->price, 2) }}</li>
                                        <li class="mb-2"><strong>Rating:</strong> {{ $product->rating ?? 'No ratings yet' }}/5</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-semibold" style="color: #5D2B4C;">Care Instructions</h6>
                                    <p class="text-muted">Keep flowers in a cool place away from direct sunlight. Change water every 2-3 days and trim stems regularly for best results.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            @auth
                            <!-- Add Review Form -->
                            @php
                                $userHasReviewed = $product->reviews->where('user_id', auth()->id())->count() > 0;
                            @endphp
                            
                            @if(!$userHasReviewed)
                            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3" style="color: #5D2B4C;">Write a Review</h6>
                                    <form action="{{ route('reviews.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div class="mb-3">
                                            <label class="form-label">Rating</label>
                                            <div class="rating-input">
                                                @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                                <label for="star{{ $i }}"><i class="far fa-star"></i></label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Comment</label>
                                            <textarea class="form-control" name="comment" rows="3" required placeholder="Share your experience with this product..."></textarea>
                                        </div>

                                        <button type="submit" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Review
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>You have already reviewed this product.
                            </div>
                            @endif
                            @endauth

                            <!-- Reviews List -->
                            @if($product->reviews->count() > 0)
                                @foreach($product->reviews as $review)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $review->user->name }}</h6>
                                            <div class="mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <p class="mb-0">{{ $review->comment }}</p>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center py-3">No reviews yet. Be the first to review this product!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold mb-4" style="color: #5D2B4C;">Related Products</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div class="product-image bg-light rounded p-3 mb-3" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                    @if($relatedProduct->main_image)
                                        <img src="{{ $relatedProduct->main_image_url }}" alt="{{ $relatedProduct->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-flower-tulip" style="font-size: 3rem; color: #CFB8BE;"></i>
                                    @endif
                                </div>
                                <h6 class="fw-bold" style="color: #5D2B4C;">{{ $relatedProduct->name }}</h6>
                                <p class="text-muted small">{{ Str::limit($relatedProduct->description, 40) }}</p>
                                <h6 class="fw-bold" style="color: #5D2B4C;">₱{{ number_format($relatedProduct->price, 2) }}</h6>
                            </div>

                            <div class="d-grid">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    gap: 5px;
}

.rating-input input {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
}

.rating-input input:checked ~ label,
.rating-input label:hover,
.rating-input label:hover ~ label {
    color: #ffc107;
}

.rating-input input:checked ~ label {
    color: #ffc107;
}
</style>

<script>
function changeQuantity(delta) {
    const quantityInput = document.getElementById('quantity');
    let newQuantity = parseInt(quantityInput.value) + delta;
    if (newQuantity < 1) newQuantity = 1;
    if (newQuantity > 99) newQuantity = 99;
    quantityInput.value = newQuantity;
}

function addToCart() {
    @guest
        // Show login modal for guests
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    @else
        // Add to cart for authenticated users
        const quantity = document.getElementById('quantity').value;
                $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: {
                product_id: {{ $product->id }},
                quantity: parseInt(quantity),
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                console.log('Add to cart success:', data);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart',
                        text: 'Product added to cart successfully!',
                        confirmButtonColor: '#5D2B4C'
                    }).then(() => {
                        // Reload page to update cart count
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Add to Cart Failed',
                        text: data.message || 'Error adding product to cart. Please try again.',
                        confirmButtonColor: '#5D2B4C'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Add to cart error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);

                let message = 'Error adding product to cart. Please try again.';
                if (xhr.status === 404) {
                    message = 'Product not found. Please refresh the page.';
                } else if (xhr.status === 403) {
                    message = 'Access denied. Please log in again.';
                } else if (xhr.status === 500) {
                    message = 'Server error occurred. Please try again.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Add to Cart Failed',
                    text: message,
                    confirmButtonColor: '#5D2B4C'
                });
            }
        });
    @endguest
}

function addToWishlist() {
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
                product_id: {{ $product->id }},
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                console.log('Add to wishlist success:', data);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Wishlist',
                        text: 'Product added to wishlist!',
                        confirmButtonColor: '#5D2B4C'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Wishlist Failed',
                        text: data.message || 'Error adding product to wishlist. Please try again.',
                        confirmButtonColor: '#5D2B4C'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Add to wishlist error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);

                let message = 'Error adding product to wishlist. Please try again.';
                if (xhr.status === 404) {
                    message = 'Product not found. Please refresh the page.';
                } else if (xhr.status === 403) {
                    message = 'Access denied. Please log in again.';
                } else if (xhr.status === 500) {
                    message = 'Server error occurred. Please try again.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Wishlist Failed',
                    text: message,
                    confirmButtonColor: '#5D2B4C'
                });
            }
        });
    @endguest
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name }}',
            text: '{{ $product->description }}',
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        navigator.clipboard.writeText(window.location.href).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Link Copied',
                text: 'Product link copied to clipboard!',
                timer: 2000,
                showConfirmButton: false
            });
        }).catch(function() {
            Swal.fire({
                icon: 'error',
                title: 'Copy Failed',
                text: 'Could not copy the product link. Please try again.',
                confirmButtonColor: '#5D2B4C'
            });
        });
    }
}
</script>
@endsection

@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, #5D2B4C 0%, #CFB8BE 100%); min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold text-white mb-4" style="font-size: clamp(2rem, 5vw, 3.5rem);">Create Magical Moments with Our Exquisite Blooms</h1>
                <p class="lead text-white mb-4" style="font-size: clamp(1rem, 2.5vw, 1.25rem);">Discover a world of floral elegance. Hand-picked, fresh, and delivered with love for every special occasion. Experience the art of gifting with Bona's Flower Shop.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('products.index') }}" class="btn btn-lg fw-semibold text-white border-white" style="background: transparent; border-radius: 12px; padding: 15px 30px; white-space: nowrap;">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </a>
                    <a href="{{ route('occasions.index') }}" class="btn btn-lg fw-semibold" style="background: #F5EEE4; color: #5D2B4C; border-radius: 12px; padding: 15px 30px; white-space: nowrap;">
                        <i class="fas fa-calendar-alt me-2"></i>Book Event
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 text-center">
                <i class="fas fa-flower-tulip hero-icon" style="font-size: clamp(8rem, 20vw, 15rem); color: rgba(255,255,255,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<!-- Flower of the Week Section -->
@if($flowerOfTheWeek)
<section class="py-5" style="background: #F5EEE4;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">🌸 Flower of the Week 🌸</h2>
                <p class="lead" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">Special spotlight on our most beautiful arrangement this week</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="product-image" style="height: 400px; background: #F0F2F5; display: flex; align-items: center; justify-content: center;">
                                @if($flowerOfTheWeek->image)
                                    <img src="{{ asset('storage/' . $flowerOfTheWeek->image) }}" alt="{{ $flowerOfTheWeek->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-flower-tulip" style="font-size: 8rem; color: #CFB8BE;"></i>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card-body p-4">
                                <h3 class="fw-bold mb-3" style="color: #5D2B4C;">{{ $flowerOfTheWeek->name }}</h3>
                                <p class="text-muted mb-3">{{ $flowerOfTheWeek->description }}</p>

                                <div class="mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $flowerOfTheWeek->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-2 text-muted">({{ $flowerOfTheWeek->reviews->count() }} reviews)</span>
                                </div>

                                <h4 class="fw-bold mb-4" style="color: #5D2B4C;">₱{{ number_format($flowerOfTheWeek->price, 2) }}</h4>

                                <div class="d-grid mb-3">
                                    <a href="{{ route('products.show', $flowerOfTheWeek) }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px; padding: 12px;">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a>
                                </div>

                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px;" onclick="addToWishlist({{ $flowerOfTheWeek->id }})">
                                        <i class="fas fa-heart me-1"></i>Wishlist
                                    </button>
                                    <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px;" onclick="addToCart({{ $flowerOfTheWeek->id }})">
                                        <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
<section class="py-5" style="background: #F0F2F5;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">Featured Products</h2>
                <p class="lead" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">Explore our most popular floral arrangements.</p>
                <a href="{{ route('products.index') }}" class="btn btn-lg fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px; padding: 15px 30px; white-space: nowrap;">
                    View All Products
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($featuredProducts as $product)
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
                            <div>SKU: {{ $product->sku ?? 'N/A' }}</div>
                            <div class="text-success">In Stock</div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No featured products available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Shop by Category -->
<section class="py-5" style="background: #F5EEE4;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">Shop by Category</h2>
                <p class="lead" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">Find the perfect flowers for any occasion.</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($categories as $category)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 text-center category-card" style="border-radius: 20px;">
                    <div class="category-image" style="height: 200px; background: #F0F2F5; border-radius: 20px 20px 0 0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        @if($category->image)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-flower-tulip" style="font-size: 4rem; color: #CFB8BE;"></i>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold" style="color: #5D2B4C;">{{ $category->name }}</h5>
                        <p class="text-muted small">{{ Str::limit($category->description, 80) }}</p>
                        <div class="text-muted small mb-3">{{ $category->products_count ?? 0 }}+ Products</div>
                        <a href="{{ route('products.byCategory', $category) }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                            View Category
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No categories available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Testimonials Section -->
@if($testimonials->count() > 0)
<section class="py-5" style="background: #F0F2F5;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">What Our Customers Say</h2>
                <p class="lead" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">Don't just take our word for it.</p>
            </div>
        </div>

        <div class="row g-4">
            @foreach($testimonials as $review)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 testimonial-card" style="border-radius: 20px;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-quote-left" style="font-size: clamp(1.5rem, 4vw, 2rem); color: #CFB8BE;"></i>
                        </div>
                        <p class="mb-3">"{{ Str::limit($review->comment, 150) }}"</p>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <h6 class="fw-bold" style="color: #5D2B4C;">{{ $review->user->name }}</h6>
                        <small class="text-muted">Happy Customer</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5" style="background: #5D2B4C;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-12 mb-4 mb-lg-0">
                <h2 class="display-5 fw-bold text-white mb-4" style="font-size: clamp(2rem, 4vw, 3rem);">Ready to create something beautiful?</h2>
                <p class="lead text-white mb-4" style="font-size: clamp(1rem, 2.5vw, 1.25rem);">Start shopping now or book your special event with our expert florists.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('products.index') }}" class="btn btn-lg fw-semibold text-white border-white" style="background: transparent; border-radius: 12px; padding: 15px 30px; white-space: nowrap;">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </a>
                    @auth
                        <a href="{{ route('bookings.create') }}" class="btn btn-lg fw-semibold" style="background: white; color: #5D2B4C; border-radius: 12px; padding: 15px 30px; white-space: nowrap;">
                            <i class="fas fa-calendar-plus me-2"></i>Book Event
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-lg fw-semibold" style="background: white; color: #5D2B4C; border-radius: 12px; padding: 15px 30px; white-space: nowrap;">
                            <i class="fas fa-calendar-plus me-2"></i>Book Event
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-4 col-md-12 text-center">
                <i class="fas fa-gift" style="font-size: clamp(6rem, 15vw, 10rem); color: rgba(255,255,255,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<!-- Location Map Section -->
<section class="py-5" style="background: #F0F2F5;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                <h2 class="display-6 fw-bold mb-3" style="color: #5D2B4C;">Visit Our Shop</h2>
                <p class="lead mb-3" style="color: #6c757d;">We are located at Silang Public Market, Cavite. Drop by to see our fresh flowers and arrangements in person.</p>
                <p class="mb-2" style="color: #5D2B4C;"><i class="fas fa-map-marker-alt me-2"></i>Bona's Flower Shop, Silang Public Market, Cavite</p>
                <p class="mb-2" style="color: #5D2B4C;"><i class="fas fa-phone me-2"></i>+0955 644 6048</p>
                <p class="mb-0" style="color: #5D2B4C;"><i class="fas fa-clock me-2"></i>Mon - Fri: 9:00 AM - 6:00 PM</p>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="ratio ratio-16x9 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <iframe
                        src="https://www.google.com/maps?q=Silang+Cavite+Public+Market&output=embed"
                        style="border:0;" allowfullscreen loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>



<style>
.hero-section {
    position: relative;
    overflow: hidden;
}

.hero-icon {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.feature-card, .product-card, .category-card, .testimonial-card {
    transition: all 0.3s ease;
    height: 100%;
}

.feature-card:hover, .product-card:hover, .category-card:hover, .testimonial-card:hover {
    transform: translateY(-10px);
}

.btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .hero-section {
        min-height: 60vh;
        text-align: center;
    }

    .hero-section .row {
        flex-direction: column-reverse;
    }

    .hero-section .col-lg-6:first-child {
        order: 2;
    }

    .hero-section .col-lg-6:last-child {
        order: 1;
        margin-bottom: 2rem;
    }

    .d-flex.gap-3.flex-wrap {
        justify-content: center;
    }

    .btn-lg {
        padding: 12px 20px !important;
        font-size: 1rem !important;
    }
}

@media (max-width: 575.98px) {
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }

    .card-body {
        padding: 1rem !important;
    }
}
</style>

<script>
function addToCart(productId) {
    @guest
        // Redirect guests to login page
        window.location.href = '{{ route('login') }}';
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart',
                        text: 'Product added to cart successfully!',
                        confirmButtonColor: '#5D2B4C'
                    }).then(() => {
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

function addToWishlist(productId) {
    @guest
        // Redirect guests to login page
        window.location.href = '{{ route('login') }}';
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
</script>
@endsection

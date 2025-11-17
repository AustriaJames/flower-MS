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
                    <button class="btn btn-lg fw-semibold text-white border-white" style="background: transparent; border-radius: 12px; padding: 15px 30px; white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                    <button class="btn btn-lg fw-semibold" style="background: #F5EEE4; color: #5D2B4C; border-radius: 12px; padding: 15px 30px; white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#registerModal">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </button>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 text-center">
                <i class="fas fa-flower-tulip hero-icon" style="font-size: clamp(8rem, 20vw, 15rem); color: rgba(255,255,255,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<!-- Excellence Section -->
<section class="py-5" style="background: #F5EEE4;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">Excellence in Every Petal</h2>
                <p class="lead" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">We're committed to delivering exceptional floral experiences that exceed expectations.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="text-center feature-card">
                    <div class="mb-3">
                        <i class="fas fa-truck" style="font-size: clamp(2.5rem, 6vw, 3rem); color: #5D2B4C;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #5D2B4C;">Lightning Fast Delivery</h5>
                    <p class="text-muted">Same-day delivery for urgent orders</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="text-center feature-card">
                    <div class="mb-3">
                        <i class="fas fa-seedling" style="font-size: clamp(2.5rem, 6vw, 3rem); color: #5D2B4C;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #5D2B4C;">Fresh Flowers Daily</h5>
                    <p class="text-muted">Hand-picked from local farms</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="text-center feature-card">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt" style="font-size: clamp(2.5rem, 6vw, 3rem); color: #5D2B4C;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #5D2B4C;">Quality Guarantee</h5>
                    <p class="text-muted">100% satisfaction guaranteed</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="text-center feature-card">
                    <div class="mb-3">
                        <i class="fas fa-headset" style="font-size: clamp(2.5rem, 6vw, 3rem); color: #5D2B4C;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #5D2B4C;">Expert Florists</h5>
                    <p class="text-muted">Professional design consultation</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5" style="background: #F5EEE4;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">Featured Products</h2>
                <p class="lead" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">Explore our most popular floral arrangements.</p>
                <button class="btn btn-lg fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px; padding: 15px 30px; white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#loginModal">
                    View All Products
                </button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <div class="bg-light rounded p-4 mb-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-muted">Product Image</span>
                            </div>
                            <h5 class="fw-bold" style="color: #5D2B4C;">JAMES</h5>
                            <p class="text-muted small">Beautiful flower arrangement</p>
                            <div class="mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="far fa-star text-warning"></i>
                            </div>
                            <h6 class="fw-bold" style="color: #5D2B4C;">P2,500.00</h6>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Quantity:</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button">-</button>
                                <input type="number" class="form-control text-center" value="1" min="1">
                                <button class="btn btn-outline-secondary" type="button">+</button>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        </div>

                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-heart me-1"></i>Wishlist
                            </button>
                            <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-exchange-alt me-1"></i>Compare
                            </button>
                        </div>

                        <div class="small text-muted">
                            <div>Stock: In Stock</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <div class="bg-light rounded p-4 mb-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-muted">Product Image</span>
                            </div>
                            <h5 class="fw-bold" style="color: #5D2B4C;">ROSE GARDEN</h5>
                            <p class="text-muted small">Elegant rose bouquet</p>
                            <div class="mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <h6 class="fw-bold" style="color: #5D2B4C;">P3,200.00</h6>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Quantity:</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button">-</button>
                                <input type="number" class="form-control text-center" value="1" min="1">
                                <button class="btn btn-outline-secondary" type="button">+</button>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        </div>

                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-heart me-1"></i>Wishlist
                            </button>
                            <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-exchange-alt me-1"></i>Compare
                            </button>
                        </div>

                        <div class="small text-muted">
                            <div>Stock: In Stock</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <div class="bg-light rounded p-4 mb-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-muted">Product Image</span>
                            </div>
                            <h5 class="fw-bold" style="color: #5D2B4C;">SUNSHINE MIX</h5>
                            <p class="text-muted small">Colorful summer flowers</p>
                            <div class="mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="far fa-star text-warning"></i>
                            </div>
                            <h6 class="fw-bold" style="color: #5D2B4C;">P2,800.00</h6>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Quantity:</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button">-</button>
                                <input type="number" class="form-control text-center" value="1" min="1">
                                <button class="btn btn-outline-secondary" type="button">+</button>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        </div>

                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-heart me-1"></i>Wishlist
                            </button>
                            <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-exchange-alt me-1"></i>Compare
                            </button>
                        </div>

                        <div class="small text-muted">
                            <div>Stock: In Stock</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 product-card" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <div class="bg-light rounded p-4 mb-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-muted">Product Image</span>
                            </div>
                            <h5 class="fw-bold" style="color: #5D2B4C;">WEDDING DREAM</h5>
                            <p class="text-muted small">Perfect for special occasions</p>
                            <div class="mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <h6 class="fw-bold" style="color: #5D2B4C;">P4,500.00</h6>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Quantity:</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button">-</button>
                                <input type="number" class="form-control text-center" value="1" min="1">
                                <button class="btn btn-outline-secondary" type="button">+</button>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        </div>

                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-heart me-1"></i>Wishlist
                            </button>
                            <button class="btn btn-outline-secondary flex-fill" style="border-radius: 12px; min-width: 120px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-exchange-alt me-1"></i>Compare
                            </button>
                        </div>

                        <div class="-004</div>
                            <div>Stock: In Stock</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop by Category -->
<section class="py-5" style="background: #F0F2F5;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">Shop by Category</h2>
                <p class="lead" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">Find the perfect flowers for any occasion.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 text-center category-card" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-flower-tulip" style="font-size: clamp(2.5rem, 6vw, 3rem); color: #5D2B4C;"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #5D2B4C;">Bouquets</h5>
                        <p class="text-muted small">Beautiful flower arrangements for every occasion</p>
                        <div class="text-muted small mb-3">50+ Products</div>
                        <button class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                            View Category
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 text-center category-card" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-seedling" style="font-size: clamp(2.5rem, 6vw, 3rem); color: #5D2B4C;"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #5D2B4C;">Single Flowers</h5>
                        <p class="text-muted small">Individual blooms for custom arrangements</p>
                        <div class="text-muted small mb-3">30+ Products</div>
                        <button class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                            View Category
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 text-center category-card" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-star" style="font-size: clamp(2.5rem, 6vw, 3rem); color: #5D2B4C;"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #5D2B4C;">Arrangements</h5>
                        <p class="text-muted small">Artistically designed floral displays</p>
                        <div class="text-muted small mb-3">40+ Products</div>
                        <button class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                            View Category
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 text-center category-card" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-heart" style="font-size: clamp(2.5rem, 6vw, 3rem); color: #5D2B4C;"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #5D2B4C;">Wedding Flowers</h5>
                        <p class="text-muted small">Perfect blooms for your special day</p>
                        <div class="text-muted small mb-3">25+ Products</div>
                        <button class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#loginModal">
                            View Category
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5" style="background: #F5EEE4;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                <h2 class="display-5 fw-bold mb-4" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">About Bona's Flower Shop</h2>
                <p class="lead mb-4" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">Founded with a passion for creating beautiful moments, Bona's Flower Shop has been serving our community with love and dedication for over a decade.</p>
                <p class="mb-4" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">We believe that every flower tells a story, and we're committed to helping you tell yours through our carefully curated collections and personalized service.</p>

                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span style="color: #5D2B4C;">Fresh flowers daily</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span style="color: #5D2B4C;">Same-day delivery</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span style="color: #5D2B4C;">Expert florists</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span style="color: #5D2B4C;">Quality guarantee</span>
                    </div>
                </div>

                <button class="btn btn-lg fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px; padding: 15px 30px; white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Learn More About Us
                </button>
            </div>
            <div class="col-lg-6 col-md-12 text-center">
                <i class="fas fa-flower-tulip" style="font-size: clamp(8rem, 20vw, 15rem); color: #5D2B4C;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5" style="background: #F0F2F5;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">What Our Customers Say</h2>
                <p class="lead" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">Don't just take our word for it.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 testimonial-card" style="border-radius: 20px;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-quote-left" style="font-size: clamp(1.5rem, 4vw, 2rem); color: #CFB8BE;"></i>
                        </div>
                        <p class="mb-3">"Amazing flowers and excellent service! The delivery was on time and the arrangement was even more beautiful than expected."</p>
                        <h6 class="fw-bold" style="color: #5D2B4C;">Sarah Johnson</h6>
                        <small class="text-muted">Happy Customer</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 testimonial-card" style="border-radius: 20px;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-quote-left" style="font-size: clamp(1.5rem, 4vw, 2rem); color: #CFB8BE;"></i>
                        </div>
                        <p class="mb-3">"The wedding flowers were absolutely perfect! Our guests couldn't stop complimenting the beautiful arrangements."</p>
                        <h6 class="fw-bold" style="color: #5D2B4C;">Michael & Emma</h6>
                        <small class="text-muted">Newlyweds</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-lg h-100 testimonial-card" style="border-radius: 20px;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-quote-left" style="font-size: clamp(1.5rem, 4vw, 2rem); color: #CFB8BE;"></i>
                        </div>
                        <p class="mb-3">"I've been ordering from Bona's for years. Their quality and service never disappoint. Highly recommended!"</p>
                        <h6 class="fw-bold" style="color: #5D2B4C;">Lisa Rodriguez</h6>
                        <small class="text-muted">Loyal Customer</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: #5D2B4C;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-12 mb-4 mb-lg-0">
                <h2 class="display-5 fw-bold text-white mb-4" style="font-size: clamp(2rem, 4vw, 3rem);">Join thousands of satisfied customers who trust us for their floral needs.</h2>
                <p class="lead text-white mb-4" style="font-size: clamp(1rem, 2.5vw, 1.25rem);">Order now and experience the difference that fresh, beautiful flowers can make.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <button class="btn btn-lg fw-semibold" style="background: white; color: #5D2B4C; border-radius: 12px; padding: 15px 30px; white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Contact Us
                    </button>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 text-center">
                <i class="fas fa-gift" style="font-size: clamp(6rem, 15vw, 10rem); color: rgba(255,255,255,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5" style="background: #F5EEE4;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12 text-center">
                <h2 class="display-5 fw-bold mb-4" style="color: #5D2B4C; font-size: clamp(2rem, 4vw, 3rem);">Stay Updated</h2>
                <p class="lead mb-4" style="color: #6c757d; font-size: clamp(1rem, 2vw, 1.25rem);">Subscribe to our newsletter for exclusive offers, flower care tips, and seasonal updates.</p>

                <div class="row justify-content-center">
                    <div class="col-md-8 col-sm-12">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control border-0" placeholder="Enter your email address" style="border-radius: 12px 0 0 12px; padding: 15px;">
                            <button class="btn fw-semibold text-white" type="button" style="background: #CFB8BE; color: #5D2B4C; border-radius: 0 12px 12px 0; padding: 15px 30px;">
                                Subscribe
                            </button>
                        </div>
                        <p class="small text-muted">We respect your privacy. Unsubscribe at any time.</p>
                    </div>
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

.input-group .form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(93, 43, 76, 0.25);
    border-color: #5D2B4C;
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

    .input-group {
        flex-direction: column;
    }

    .input-group .form-control {
        border-radius: 12px !important;
        margin-bottom: 0.5rem;
    }

    .input-group .btn {
        border-radius: 12px !important;
        width: 100%;
    }
}
</style>
@endsection

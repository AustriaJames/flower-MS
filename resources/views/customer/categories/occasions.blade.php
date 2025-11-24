@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #5D2B4C;">
                <i class="bi bi-calendar-event me-2"></i>Special Occasions
            </h2>
            <p class="lead text-muted mb-5">Make your special moments unforgettable with our custom floral arrangements and event planning services.</p>

            @if($occasionCategories->count() > 0)
            <div class="row g-4">
                @foreach($occasionCategories as $category)
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card border-0 shadow-lg h-100 category-card" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center">
                                    <div class="category-image" style="height: 120px; background: #F0F2F5; border-radius: 15px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if($category->image)
                                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="bi bi-calendar-heart" style="font-size: 2.5rem; color: #CFB8BE;"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="fw-bold" style="color: #5D2B4C;">{{ $category->name }}</h5>
                                    <p class="text-muted small">{{ Str::limit($category->description, 100) }}</p>
                                    <div class="text-muted small mb-3">{{ $category->products_count ?? 0 }}+ Products Available</div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('products.byCategory', $category) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 8px;">
                                            <i class="bi bi-eye me-1"></i>View Products
                                        </a>
                                        @auth
                                            <a href="{{ route('bookings.create') }}" class="btn fw-semibold text-white btn-sm" style="background: #5D2B4C; border-radius: 8px;">
                                                <i class="bi bi-calendar-plus me-1"></i>Schedule Event
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn fw-semibold text-white btn-sm" style="background: #5D2B4C; border-radius: 8px;">
                                                <i class="bi bi-calendar-plus me-1"></i>Schedule Event
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Schedule Event -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-lg" style="border-radius: 20px; background: linear-gradient(135deg, #5D2B4C 0%, #CFB8BE 100%);">
                        <div class="card-body p-5 text-center text-white">
                            <h3 class="fw-bold mb-3">Ready to Plan Your Special Event?</h3>
                            <p class="lead mb-4">Our expert florists are here to help you create the perfect floral arrangements for your special occasion.</p>
                            @auth
                                <a href="{{ route('bookings.create') }}" class="btn btn-lg fw-semibold text-white border-white" style="background: transparent; border-radius: 12px; padding: 15px 30px;">
                                    <i class="bi bi-calendar-plus me-2"></i>Book Your Event Now
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-lg fw-semibold text-white border-white" style="background: transparent; border-radius: 12px; padding: 15px 30px;">
                                    <i class="bi bi-calendar-plus me-2"></i>Book Your Event Now
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-calendar-event" style="font-size: 4rem; color: #CFB8BE;"></i>
                <h4 class="mt-3" style="color: #5D2B4C;">No occasion categories available</h4>
                <p class="text-muted">We're working on organizing our special occasion services. Check back soon!</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                        <i class="bi bi-grid me-2"></i>Browse All Products
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                        <i class="bi bi-tags me-2"></i>Browse Categories
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #5D2B4C;">
                <i class="bi bi-calendar-event me-2"></i>Occasion Products
            </h2>
            <p class="lead text-muted mb-5">All products for every special event and occasion.</p>

            @forelse($productsByCategory as $categoryName => $products)
                @if($products->count())
                <h4 class="fw-bold mt-5 mb-3" style="color: #5D2B4C;">{{ $categoryName }}</h4>
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
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
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary" style="border-radius: 8px;">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                    <form action="{{ route('wishlist.add', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary" style="border-radius: 8px;">
                                            <i class="fas fa-heart me-1"></i>Wishlist
                                        </button>
                                    </form>
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" style="border-radius: 8px;">
                                            <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            @empty
                <div class="alert alert-info">No occasion products found.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

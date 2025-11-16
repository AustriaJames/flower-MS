@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #5D2B4C;">
                <i class="fas fa-tags me-2"></i>Product Categories
            </h2>
            <p class="lead text-muted mb-5">Browse our beautiful flowers by category to find exactly what you're looking for.</p>

            @if($categories->count() > 0)
            <div class="row g-4">
                @foreach($categories as $category)
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
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-tags" style="font-size: 4rem; color: #CFB8BE;"></i>
                <h4 class="mt-3" style="color: #5D2B4C;">No categories available</h4>
                <p class="text-muted">We're working on organizing our products into categories. Check back soon!</p>
                <a href="{{ route('products.index') }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                    <i class="fas fa-th-large me-2"></i>Browse All Products
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

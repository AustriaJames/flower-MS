
@extends('layouts.app')

@section('content')

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #5D2B4C;">Leave Reviews for Order #{{ $order->order_number }}</h2>
            <div class="card border-0 shadow-lg" style="border-radius: 12px;">
                <div class="card-body">
                    <form method="POST" action="{{ route('orders.submitReviews', $order) }}">
                        @csrf
                        @foreach($order->orderItems as $item)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                        <small class="text-muted">Quantity: {{ $item->quantity }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="star-rating" data-product-id="{{ $item->product->id }}">
                                            @for($s = 1; $s <= 5; $s++)
                                                <span class="star" data-value="{{ $s }}">☆</span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="order_item_ids[{{ $item->product->id }}]" value="{{ $item->id }}">
                                <input type="hidden" name="ratings[{{ $item->product->id }}]" value="" class="rating-input-{{ $item->product->id }}">
                                <textarea name="comments[{{ $item->product->id }}]" class="form-control mt-2" rows="2" placeholder="Write an optional comment..."></textarea>
                            </div>
                            <hr>
                        @endforeach
                        <div class="text-end">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary me-2">Back to Orders</a>
                            <button type="submit" class="btn btn-primary">Submit Reviews</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    .star-rating .star {
        font-size: 2.2rem;
        cursor: pointer;
        color: #ccc;
        transition: color 0.2s;
        margin-right: 2px;
    }
    .star-rating .star.selected,
    .star-rating .star:hover,
    .star-rating .star.selected ~ .star {
        color: #ffc107 !important;
    }
    .star-rating .star.selected {
        color: #ffc107;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.star-rating').forEach(function(wrapper) {
        const productId = wrapper.getAttribute('data-product-id');
        const input = document.querySelector('.rating-input-' + productId);
        wrapper.querySelectorAll('.star').forEach(function(star, idx, stars) {
            star.addEventListener('click', function() {
                const value = parseInt(this.getAttribute('data-value'));
                input.value = value;
                stars.forEach(function(s, i) {
                    s.textContent = (i < value) ? '★' : '☆';
                    if (i < value) {
                        s.classList.add('selected');
                    } else {
                        s.classList.remove('selected');
                    }
                });
            });
            star.addEventListener('mouseover', function() {
                const v = parseInt(this.getAttribute('data-value'));
                stars.forEach(function(s, i) {
                    s.textContent = (i < v) ? '★' : '☆';
                    if (i < v) {
                        s.classList.add('selected');
                    } else {
                        s.classList.remove('selected');
                    }
                });
            });
            star.addEventListener('mouseout', function() {
                const val = parseInt(input.value) || 0;
                stars.forEach(function(s, i) {
                    s.textContent = (i < val) ? '★' : '☆';
                    if (i < val) {
                        s.classList.add('selected');
                    } else {
                        s.classList.remove('selected');
                    }
                });
            });
        });
    });
});
</script>

@endsection

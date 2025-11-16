@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #5D2B4C;">
                <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
            </h2>

            @if($cartItems->count() > 0)
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="card-body">
                            @foreach($cartItems as $item)
                            <div class="row align-items-center py-3 border-bottom cart-item">
                                <div class="col-md-2">
                                    <div class="product-image bg-light rounded p-2" style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                        @if($item->product->main_image)
                                            <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-flower-tulip" style="font-size: 2rem; color: #CFB8BE;"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="product-title">{{ $item->product->name }}</h6>
                                    <p class="product-description">{{ Str::limit($item->product->description, 50) }}</p>
                                    <p class="product-price">₱{{ number_format($item->product->price, 2) }} each</p>
                                </div>
                                <div class="col-md-2">
                                    <form method="POST" action="{{ route('cart.update', $item) }}" class="quantity-form" data-cart-item-id="{{ $item->id }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="quantity-controls">
                                            <button class="btn btn-outline-secondary btn-sm quantity-btn" type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" data-cart-item-id="{{ $item->id }}">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" class="form-control text-center quantity-input" value="{{ $item->quantity }}" min="1" onchange="this.form.submit()">
                                            <button class="btn btn-outline-secondary btn-sm quantity-btn" type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" data-cart-item-id="{{ $item->id }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h6 class="price-display">₱{{ number_format($item->product->price * $item->quantity, 2) }}</h6>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn remove-btn"
                                            data-bs-toggle="modal" data-bs-target="#removeCartModal{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-lg cart-summary" style="border-radius: 20px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Cart Summary</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₱{{ number_format($total, 2) }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Delivery Fee:</span>
                                <span class="text-success">Free</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Total:</span>
                                <span class="fw-bold" style="color: #5D2B4C;">₱{{ number_format($total, 2) }}</span>
                            </div>

                            <div class="d-grid mb-3">
                                <a href="{{ route('orders.create') }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px; padding: 12px;">
                                    <i class="fas fa-shopping-bag me-2"></i>Proceed to Checkout
                                </a>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #CFB8BE;"></i>
                <h4 class="mt-3" style="color: #5D2B4C;">Your cart is empty</h4>
                <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('products.index') }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Professional Cart Styling */
.cart-item {
    transition: all 0.3s ease;
    border-radius: 12px;
    margin-bottom: 1rem;
}

.cart-item:hover {
    box-shadow: 0 4px 15px rgba(93, 43, 76, 0.1);
    transform: translateY(-2px);
}

.product-image {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    max-width: 120px;
}

.quantity-controls .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid #CFB8BE;
    background: white;
    color: #5D2B4C;
    transition: all 0.2s ease;
}

.quantity-controls .btn:hover {
    background: #5D2B4C;
    color: white;
    border-color: #5D2B4C;
    transform: scale(1.05);
}

.quantity-controls .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.quantity-input {
    width: 50px !important;
    height: 32px;
    text-align: center;
    border: 1px solid #CFB8BE;
    border-radius: 6px;
    background: white;
    color: #5D2B4C;
    font-weight: 500;
    padding: 0.25rem;
}

.quantity-input:focus {
    border-color: #5D2B4C;
    box-shadow: 0 0 0 0.2rem rgba(93, 43, 76, 0.25);
    outline: none;
}

.quantity-input.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.remove-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid #dc3545;
    background: white;
    color: #dc3545;
    transition: all 0.2s ease;
}

.remove-btn:hover {
    background: #dc3545;
    color: white;
    transform: scale(1.05);
}

.cart-summary {
    background: linear-gradient(135deg, #F5EEE4 0%, #F8F4F0 100%);
    border-radius: 16px;
    border: 1px solid #CFB8BE;
}

.price-display {
    font-size: 1.1rem;
    font-weight: 600;
    color: #5D2B4C;
}

.product-title {
    font-weight: 600;
    color: #5D2B4C;
    margin-bottom: 0.25rem;
}

.product-description {
    color: #6c757d;
    font-size: 0.875rem;
    line-height: 1.4;
}

.product-price {
    color: #5D2B4C;
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .quantity-controls {
        max-width: 100px;
    }

    .quantity-input {
        width: 40px !important;
    }

    .quantity-controls .btn {
        width: 28px;
        height: 28px;
    }

    .remove-btn {
        width: 28px;
        height: 28px;
    }
}
</style>

<script>
function updateQuantity(cartItemId, newQuantity) {
    if (newQuantity < 1) return;

    // Find the form for this specific cart item using data attribute
    const form = document.querySelector(`form[data-cart-item-id="${cartItemId}"]`);
    if (form) {
        const quantityInput = form.querySelector('input[name="quantity"]');
        const quantityBtns = form.querySelectorAll('.quantity-btn');

        if (quantityInput) {
            // Show loading state on buttons
            quantityBtns.forEach(function(btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            });

            // Update quantity and submit
            quantityInput.value = newQuantity;
            form.submit();
        }
    } else {
        console.error('Form not found for cart item:', cartItemId);
        showNotification('Error updating quantity. Please try again.', 'error');
    }
}

// Enhanced form handling
document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity input changes
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const newQuantity = parseInt(this.value);
            if (newQuantity < 1) {
                this.value = 1;
            }
        });

        // Handle input validation
        input.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value < 1) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Handle form submissions with loading states
    const quantityForms = document.querySelectorAll('.quantity-form');
    quantityForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const quantityInput = this.querySelector('input[name="quantity"]');
            const quantityBtns = this.querySelectorAll('.quantity-btn');

            if (quantityInput && parseInt(quantityInput.value) < 1) {
                e.preventDefault();
                quantityInput.value = 1;
                showNotification('Quantity cannot be less than 1.', 'warning');
                return;
            }

            // Show loading state
            quantityBtns.forEach(function(btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            });
        });
    });
});
</script>

<!-- Remove Cart Item Modals -->
@foreach($cartItems as $item)
<div class="modal fade" id="removeCartModal{{ $item->id }}" tabindex="-1" aria-labelledby="removeCartModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeCartModalLabel{{ $item->id }}">
                    <i class="fas fa-trash text-danger me-2"></i>
                    Remove Item from Cart
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this item from your cart?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Item Details:</strong><br>
                    <small>{{ $item->product->name }} - Quantity: {{ $item->quantity }}</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form method="POST" action="{{ route('cart.remove', $item) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Remove Item
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection


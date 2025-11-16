@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h4 class="fw-bold mb-0" style="color: #5D2B4C;">
                        <i class="fas fa-shopping-cart me-2"></i>Checkout
                    </h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->has('general'))
                        <div class="alert alert-danger">
                            {{ $errors->first('general') }}
                        </div>
                    @endif

                    @if ($errors->has('cart'))
                        <div class="alert alert-warning">
                            {{ $errors->first('cart') }}
                        </div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <!-- Customer Information -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Customer Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-bold">Phone Number *</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Delivery Options -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Delivery Options</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Delivery Type *</label>
                                    <div class="form-check">
                                        <input class="form-check-input @error('delivery_type') is-invalid @enderror"
                                               type="radio" name="delivery_type" id="delivery" value="delivery"
                                               {{ old('delivery_type', 'delivery') == 'delivery' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="delivery">
                                            <i class="fas fa-truck me-2"></i>Home Delivery
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input @error('delivery_type') is-invalid @enderror"
                                               type="radio" name="delivery_type" id="pickup" value="pickup"
                                               {{ old('delivery_type') == 'pickup' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pickup">
                                            <i class="fas fa-store me-2"></i>Store Pickup
                                        </label>
                                    </div>
                                    @error('delivery_type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                                                <div class="col-md-6 mb-3">
                                    <label for="delivery_date" class="form-label fw-bold">Preferred Date *</label>
                                    <input type="date" class="form-control @error('delivery_date') is-invalid @enderror"
                                           id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    @error('delivery_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">No deliveries on Sundays</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_time" class="form-label fw-bold">Preferred Time *</label>
                                    <select class="form-select @error('delivery_time') is-invalid @enderror"
                                            id="delivery_time" name="delivery_time" required>
                                        <option value="">Select time</option>
                                        <option value="09:00" {{ old('delivery_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                        <option value="10:00" {{ old('delivery_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                        <option value="11:00" {{ old('delivery_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                        <option value="12:00" {{ old('delivery_time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                        <option value="13:00" {{ old('delivery_time') == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                                        <option value="14:00" {{ old('delivery_time') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                        <option value="15:00" {{ old('delivery_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                        <option value="16:00" {{ old('delivery_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                        <option value="17:00" {{ old('delivery_time') == '17:00' ? 'selected' : '' }}>5:00 PM</option>
                                    </select>
                                    @error('delivery_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pickup_time" class="form-label fw-bold">Pickup Time</label>
                                    <select class="form-select @error('pickup_time') is-invalid @enderror"
                                            id="pickup_time" name="pickup_time">
                                        <option value="">Select time</option>
                                        <option value="09:00" {{ old('pickup_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                        <option value="10:00" {{ old('pickup_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                        <option value="11:00" {{ old('pickup_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                        <option value="12:00" {{ old('pickup_time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                        <option value="13:00" {{ old('pickup_time') == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                                        <option value="14:00" {{ old('pickup_time') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                        <option value="15:00" {{ old('pickup_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                        <option value="16:00" {{ old('pickup_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                    </select>
                                    @error('pickup_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Address -->
                        <div class="mb-4" id="delivery-address-section">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Delivery Address</h5>
                            <div class="mb-3">
                                <label for="address" class="form-label fw-bold">Street Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                       id="address" name="address" value="{{ old('address') }}"
                                       placeholder="Enter your complete address">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label fw-bold">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                           id="city" name="city" value="{{ old('city') }}" placeholder="Enter city">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="postal_code" class="form-label fw-bold">Postal Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                           id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                           placeholder="Enter postal code">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Special Instructions -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Special Instructions</h5>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="3"
                                          placeholder="Any special instructions for delivery or pickup?">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Payment Method</h5>
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror"
                                       type="radio" name="payment_method" id="cod" value="cod"
                                       {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cod">
                                    <i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery (COD)
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Payment will be collected upon delivery or pickup</small>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror"
                                       type="checkbox" id="terms" name="terms" value="1"
                                       {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" style="color: #5D2B4C;">Terms and Conditions</a> and <a href="#" style="color: #5D2B4C;">Privacy Policy</a> *
                                </label>
                            </div>
                            @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100" style="background-color: #5D2B4C; border-color: #5D2B4C;">
                            <i class="fas fa-lock me-2"></i>Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="d-flex align-items-center mb-3">
                        <div class="product-image bg-light rounded p-2 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            @if($item->product->main_image)
                                <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                            @else
                                <i class="fas fa-flower-tulip" style="font-size: 1.5rem; color: #CFB8BE;"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1" style="color: #5D2B4C;">{{ $item->product->name }}</h6>
                            <p class="text-muted small mb-0">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                        </div>
                    </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span id="delivery-fee-label">Delivery Fee:</span>
                        <span id="delivery-fee-amount">₱{{ number_format($deliveryFee, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total:</span>
                        <span class="fw-bold fs-5" style="color: #5D2B4C;">₱{{ number_format($total, 2) }}</span>
                    </div>

                    <div class="alert alert-info border-0" style="background-color: #F5EEE4; border-left: 4px solid #5D2B4C;">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Free delivery for orders above ₱1,000. Standard delivery fee: ₱150.</small>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="card border-0 shadow-lg mt-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-bold mb-0" style="color: #5D2B4C;">Important Notes</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><i class="fas fa-clock me-2" style="color: #5D2B4C;"></i>No deliveries on Sundays</li>
                        <li class="mb-2"><i class="fas fa-calendar me-2" style="color: #5D2B4C;"></i>Orders placed after 2 PM will be delivered the next business day</li>
                        <li class="mb-2"><i class="fas fa-phone me-2" style="color: #5D2B4C;"></i>We'll call you 30 minutes before delivery</li>
                        <li><i class="fas fa-exclamation-triangle me-2" style="color: #5D2B4C;"></i>Please ensure someone is available to receive the order</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Hide delivery address section by default for pickup */
#delivery-address-section {
    display: block;
}

/* Style for form validation errors */
.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .col-lg-8, .col-lg-4 {
        margin-bottom: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliveryTypeRadios = document.querySelectorAll('input[name="delivery_type"]');
    const deliveryAddressSection = document.getElementById('delivery-address-section');
    const deliveryTimeInput = document.getElementById('delivery_time');
    const pickupTimeInput = document.getElementById('pickup_time');
    const deliveryFeeLabel = document.getElementById('delivery-fee-label');
    const deliveryFeeAmount = document.getElementById('delivery-fee-amount');
    const totalAmount = document.querySelector('.d-flex.justify-content-between.mb-3 .fw-bold.fs-5');

    // Get subtotal from the page
    const subtotalText = document.querySelector('.d-flex.justify-content-between.mb-2 span:last-child').textContent;
    const subtotal = parseFloat(subtotalText.replace('₱', '').replace(',', ''));

    // Function to update delivery fee and total
    function updateDeliveryFee(deliveryType) {
        let deliveryFee = 0;
        let feeLabel = 'Delivery Fee:';

        if (deliveryType === 'delivery') {
            deliveryFee = subtotal >= 1000 ? 0 : 150;
            feeLabel = 'Delivery Fee:';
        } else {
            deliveryFee = 0;
            feeLabel = 'Pickup Fee:';
        }

        const total = subtotal + deliveryFee;

        deliveryFeeLabel.textContent = feeLabel;
        deliveryFeeAmount.textContent = `₱${deliveryFee.toFixed(2)}`;
        totalAmount.textContent = `₱${total.toFixed(2)}`;
    }

    // Handle delivery type change
    deliveryTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'delivery') {
                deliveryAddressSection.style.display = 'block';
                deliveryTimeInput.disabled = false;
                pickupTimeInput.disabled = true;
                deliveryTimeInput.required = true;
                pickupTimeInput.value = '';
                pickupTimeInput.required = false;
                // Make delivery fields required
                document.querySelectorAll('#delivery-address-section input, #delivery-address-section select').forEach(field => {
                    field.required = true;
                });
            } else {
                deliveryAddressSection.style.display = 'none';
                deliveryTimeInput.disabled = true;
                pickupTimeInput.disabled = false;
                deliveryTimeInput.value = '';
                pickupTimeInput.required = true;
                // Make delivery fields not required and clear them
                document.querySelectorAll('#delivery-address-section input, #delivery-address-section select').forEach(field => {
                    field.required = false;
                    field.value = '';
                });
            }

            // Update delivery fee and total
            updateDeliveryFee(this.value);
        });
    });

    // Initialize the form based on current selection
    const currentDeliveryType = document.querySelector('input[name="delivery_type"]:checked');
    if (currentDeliveryType) {
        currentDeliveryType.dispatchEvent(new Event('change'));
    } else {
        // Default to delivery if nothing is selected
        const deliveryRadio = document.querySelector('input[name="delivery_type"][value="delivery"]');
        if (deliveryRadio) {
            deliveryRadio.checked = true;
            deliveryRadio.dispatchEvent(new Event('change'));
        }
    }

    // Initialize delivery fee display
    const initialDeliveryType = document.querySelector('input[name="delivery_type"]:checked')?.value || 'delivery';
    updateDeliveryFee(initialDeliveryType);

    // Prevent Sunday selection (optional client-side validation)
    const deliveryDateInput = document.getElementById('delivery_date');
    deliveryDateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        if (selectedDate.getDay() === 0) { // Sunday
            alert('Sorry, we do not deliver on Sundays. Please select another date.');
            this.value = '';
        }
    });
});
</script>
@endsection

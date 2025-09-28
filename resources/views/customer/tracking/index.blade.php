@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3" style="color: #5D2B4C;">
                    <i class="fas fa-truck me-2"></i>Track Your Order
                </h2>
                <p class="lead text-muted">Enter your tracking number to check your order status</p>
            </div>

            <!-- Tracking Form -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body p-5">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('tracking.track') }}" method="GET" id="trackingForm">
                        <div class="mb-4">
                            <label for="tracking_number" class="form-label fw-semibold" style="color: #5D2B4C;">Tracking Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4;">
                                    <i class="fas fa-truck" style="color: #5D2B4C;"></i>
                                </span>
                                <input type="text"
                                       class="form-control border-0 @error('tracking_number') is-invalid @enderror"
                                       id="tracking_number"
                                       name="tracking_number"
                                       style="background: #F5EEE4; color: #5D2B4C;"
                                       placeholder="Enter your tracking number (e.g., TRK-ABC12345)"
                                       value="{{ old('tracking_number') }}"
                                       required>
                            </div>
                            @error('tracking_number')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                You can find your tracking number in your order confirmation email or order details
                            </small>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn fw-semibold text-white" id="trackSubmitBtn" style="background: #5D2B4C; border-radius: 12px; padding: 12px;">
                                <i class="fas fa-search me-2"></i>Track Order
                            </button>
                        </div>
                    </form>

                    <!-- Help Section -->
                    <div class="text-center">
                        <hr class="my-4">
                        <h6 class="fw-bold" style="color: #5D2B4C;">Need Help?</h6>
                        <p class="text-muted small mb-3">If you can't find your tracking number or have questions:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('chat.index') }}" class="btn btn-outline-primary btn-sm" style="border-radius: 8px;">
                                    <i class="fas fa-comments me-1"></i>Chat Support
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="tel:+12345678900" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
                                    <i class="fas fa-phone me-1"></i>Call Us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Cards -->
            <div class="row mt-5">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
                        <div class="card-body">
                            <i class="fas fa-envelope" style="font-size: 2rem; color: #5D2B4C;"></i>
                            <h6 class="mt-3 fw-bold" style="color: #5D2B4C;">Order Confirmation</h6>
                            <p class="text-muted small">Check your email for the tracking number</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
                        <div class="card-body">
                            <i class="fas fa-clock" style="font-size: 2rem; color: #5D2B4C;"></i>
                            <h6 class="mt-3 fw-bold" style="color: #5D2B4C;">Real-time Updates</h6>
                            <p class="text-muted small">Get live updates on your order status</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
                        <div class="card-body">
                            <i class="fas fa-shipping-fast" style="font-size: 2rem; color: #5D2B4C;"></i>
                            <h6 class="mt-3 fw-bold" style="color: #5D2B4C;">Fast Delivery</h6>
                            <p class="text-muted small">Track your order from processing to delivery</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.input-group-text {
    border-radius: 12px 0 0 12px;
}

.input-group .form-control {
    border-radius: 0 12px 12px 0;
}

.input-group .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(93, 43, 76, 0.25);
    border-color: #5D2B4C;
}

.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const trackingForm = document.getElementById('trackingForm');
    const trackingInput = document.getElementById('tracking_number');
    const trackSubmitBtn = document.getElementById('trackSubmitBtn');

    if (trackingForm) {
        trackingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const trackingNumber = trackingInput.value.trim();

            // Remove any previous error states
            trackingInput.classList.remove('is-invalid');

            if (trackingNumber) {
                // Show loading state
                const originalText = trackSubmitBtn.innerHTML;
                trackSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Tracking...';
                trackSubmitBtn.disabled = true;

                // Submit the form
                this.submit();
            } else {
                // Show error if no tracking number entered
                trackingInput.classList.add('is-invalid');
                trackingInput.focus();
            }
        });
    }

    // Clear error state when user starts typing
    if (trackingInput) {
        trackingInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }

    // Auto-focus on tracking input
    trackingInput.focus();
});
</script>
@endsection

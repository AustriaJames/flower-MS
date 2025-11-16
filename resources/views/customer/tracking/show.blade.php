@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0" style="color: #5D2B4C;">
                    <i class="fas fa-truck me-2"></i>Order Tracking
                </h2>
                <a href="{{ route('tracking.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                    <i class="fas fa-arrow-left me-2"></i>Track Another Order
                </a>
            </div>
            <div class="text-center mb-5">
                <p class="lead text-muted">Track your order in real-time</p>
            </div>

            <!-- Order Information -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                            <p><strong>Tracking Number:</strong> {{ $order->tracking?->tracking_number ?? 'Not assigned yet' }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Delivery Type:</strong> {{ ucfirst($order->delivery_type) }}</p>
                            @if($order->delivery_type === 'delivery')
                                <p><strong>Delivery Date:</strong> {{ $order->delivery_date }}</p>
                                <p><strong>Delivery Time:</strong> {{ $order->delivery_time }}</p>
                            @else
                                <p><strong>Pickup Time:</strong> {{ $order->pickup_time }}</p>
                            @endif
                            <p><strong>Total Amount:</strong> <span class="fw-bold" style="color: #5D2B4C;">₱{{ number_format($order->total_amount, 2) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Current Status</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'processing' ? 'warning' : 'info') }} fs-4 px-4 py-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <p class="text-muted">Your order is currently {{ $order->status }}</p>
                </div>
            </div>

            <!-- Tracking Timeline -->
            @if($tracking->count() > 0)
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Tracking Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($tracking as $track)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold" style="color: #5D2B4C;">{{ $track->status }}</h6>
                                <p class="text-muted mb-1">{{ $track->description }}</p>
                                <small class="text-muted">{{ $track->created_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-body text-center py-4">
                    <i class="fas fa-clock" style="font-size: 3rem; color: #CFB8BE;"></i>
                    <h5 class="mt-3" style="color: #5D2B4C;">No tracking updates yet</h5>
                    <p class="text-muted">We'll update the tracking information as your order progresses.</p>
                </div>
            </div>
            @endif

            <!-- Order Items Summary -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                    <div class="row align-items-center py-2 border-bottom">
                        <div class="col-md-2">
                            <div class="product-image bg-light rounded p-2" style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                @if($item->product->main_image)
                                    <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-flower-tulip" style="font-size: 1.5rem; color: #CFB8BE;"></i>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold" style="color: #5D2B4C;">{{ $item->product->name }}</h6>
                            <p class="text-muted small mb-0">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="fw-bold">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Need Help?</h5>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-3">If you have any questions about your order, please contact us:</p>
                    <div class="row">
                        <div class="col-md-4">
                            <i class="fas fa-phone" style="font-size: 2rem; color: #5D2B4C;"></i>
                            <p class="mt-2">+0955 644 6048</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-envelope" style="font-size: 2rem; color: #5D2B4C;"></i>
                            <p class="mt-2">info@bonasflowershop.com</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-clock" style="font-size: 2rem; color: #5D2B4C;"></i>
                            <p class="mt-2">Mon-Fri: 9AM-6PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #5D2B4C;
    border: 4px solid #F5EEE4;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    width: 2px;
    height: calc(100% + 10px);
    background: #CFB8BE;
}

.timeline-content {
    padding-left: 20px;
}
</style>
@endsection

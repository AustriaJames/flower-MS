@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold" style="color: #5D2B4C;">
                    <i class="fas fa-shopping-bag me-2"></i>Order Details
                </h2>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                    <i class="fas fa-arrow-left me-2"></i>Back to Orders
                </a>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                            <p><strong>Tracking Number:</strong> {{ $order->tracking_number ?? 'Not assigned yet' }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                            <p><strong>Status:</strong>
                                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'processing' ? 'warning' : 'info') }} fs-6">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
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

            <!-- Order Items -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                    <div class="row align-items-center py-3 border-bottom">
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
                            <h6 class="fw-bold" style="color: #5D2B4C;">{{ $item->product->name }}</h6>
                            <p class="text-muted small mb-1">{{ Str::limit($item->product->description, 50) }}</p>
                            <p class="text-muted small mb-0">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Price:</strong> ₱{{ number_format($item->price, 2) }}</p>
                            <p class="mb-0"><strong>Subtotal:</strong> ₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                        </div>
                        <div class="col-md-3">
                            @if($item->notes)
                                <p class="mb-1"><strong>Message:</strong></p>
                                <p class="text-muted small">{{ $item->notes }}</p>
                            @endif
                            @if($item->options && isset($item->options['add_ons']) && count($item->options['add_ons']) > 0)
                                <p class="mb-1"><strong>Add-ons:</strong></p>
                                <p class="text-muted small">{{ implode(', ', $item->options['add_ons']) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Delivery Information -->
            @if($order->shippingAddress)
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Delivery Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Contact Person:</strong> {{ $order->shippingAddress->full_name }}</p>
                    <p><strong>Contact Phone:</strong> {{ $order->shippingAddress->phone }}</p>
                    <p><strong>Contact Email:</strong> {{ $order->shippingAddress->email }}</p>
                    <p><strong>Delivery Address:</strong> {{ $order->shippingAddress->full_address }}</p>
                </div>
            </div>
            @endif

            <!-- Order Tracking -->
            @if($order->tracking)
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Order Tracking</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold" style="color: #5D2B4C;">{{ $order->tracking->status }}</h6>
                                <p class="text-muted mb-1">{{ $order->tracking->description }}</p>
                                <small class="text-muted">{{ $order->tracking->created_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Actions -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body text-center">
                    @if($order->status === 'pending')
                        <button type="button" class="btn btn-outline-danger me-2" style="border-radius: 12px;"
                                data-bs-toggle="modal" data-bs-target="#cancellationModal">
                            <i class="fas fa-times me-2"></i>Cancel Order
                        </button>
                    @elseif($order->status === 'confirmed')
                        <button type="button" class="btn btn-outline-warning me-2" style="border-radius: 12px;"
                                data-bs-toggle="modal" data-bs-target="#cancellationModal">
                            <i class="fas fa-exclamation-triangle me-2"></i>Request Cancellation
                        </button>
                    @endif

                    @if($order->tracking_number)
                    <a href="{{ route('tracking.track.number', $order->tracking_number) }}" class="btn btn-outline-primary" style="border-radius: 12px;">
                        <i class="fas fa-truck me-2"></i>Track Order
                    </a>
                    @endif
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

<!-- Cancellation Modal -->
<div class="modal fade" id="cancellationModal" tabindex="-1" aria-labelledby="cancellationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancellationModalLabel">
                    @if($order->status === 'pending')
                        <i class="fas fa-times text-danger me-2"></i>
                        Confirm Order Cancellation
                    @else
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Confirm Cancellation Request
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($order->status === 'pending')
                    <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Immediate Cancellation:</strong><br>
                        <small>This order will be cancelled immediately without admin confirmation.</small>
                    </div>
                @else
                    <p>Are you sure you want to request cancellation for this order?</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Cancellation Request:</strong><br>
                        <small>We will review your request and contact you within 24 hours.</small>
                    </div>
                @endif
                <div class="alert alert-secondary">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Order Details:</strong><br>
                    <small>Order #{{ $order->order_number }} - {{ ucfirst($order->status) }}</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form method="POST" action="{{ route('orders.requestCancellation', $order) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn {{ $order->status === 'pending' ? 'btn-danger' : 'btn-warning' }}">
                        <i class="fas fa-check me-1"></i>
                        @if($order->status === 'pending')
                            Confirm Cancellation
                        @else
                            Submit Request
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

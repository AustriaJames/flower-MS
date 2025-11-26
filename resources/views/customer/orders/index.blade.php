@extends('layouts.app')

@section('content')
<div class="container py-5">
    @php
        $userId = auth()->id();
        $orderDebug = $orders->map(function($o) use ($userId) {
            $shouldShowReviewButton = $o->status === 'delivered';
            $hasReviewed = $o->isFullyReviewedBy($userId);
            return [
                'order_number' => $o->order_number,
                'id' => $o->id,
                'delivery_type' => $o->delivery_type,
                'status' => $o->status,
                'shouldShowReviewButton' => $shouldShowReviewButton,
                'hasReviewed' => $hasReviewed,
            ];
        });
    @endphp

    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #5D2B4C;">
                <i class="fas fa-shopping-bag me-2"></i>My Orders
            </h2>

            @if($orders->count() > 0)
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body">
                    @foreach($orders as $order)
                    <div class="row align-items-center py-4 border-bottom">
                        <div class="col-md-2">
                            <div class="text-center">
                                <i class="fas fa-shopping-bag" style="font-size: 2rem; color: #CFB8BE;"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h6 class="fw-bold" style="color: #5D2B4C;">Order #{{ $order->order_number }}</h6>
                            <p class="text-muted small mb-1">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                            <p class="text-muted small mb-0">{{ $order->orderItems->count() }} item(s)</p>
                        </div>
                        <div class="col-md-2">
                            @php
                                $isPickup = $order->delivery_type === 'pickup';
                                $displayStatus = $order->status;
                                if ($isPickup) {
                                    $displayStatus = match($order->status) {
                                        'pending' => 'Pending',
                                        'confirmed' => 'Approved',
                                        'processing' => 'For Preparation',
                                        'ready_for_pickup' => 'Ready for Pick Up',
                                        'delivered' => 'Delivered',
                                        default => ucfirst(str_replace('_', ' ', $order->status)),
                                    };
                                } else {
                                    $displayStatus = ucfirst(str_replace('_', ' ', $order->status));
                                }
                                $statusColor = match($order->status) {
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'processing' => 'primary',
                                    'shipped' => 'info',
                                    'ready_for_pickup' => 'success',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }} fs-6">
                                {{ $displayStatus }}
                            </span>
                            @if($order->cancellation_requested && $order->status === 'confirmed')
                                <br><small class="text-warning"><i class="fas fa-clock me-1"></i>Cancellation Requested</small>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <span class="fw-bold" style="color: #5D2B4C;">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary me-2" style="border-radius: 8px;">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                            @php
                                // Only show review button for delivered orders (not ready_for_pickup), and not yet reviewed
                                $shouldShowReviewButton = $order->status === 'delivered' && !$order->is_reviewed && $order->status !== 'ready_for_pickup';
                                $isReviewedStatus = $order->is_reviewed;
                                $hasReviewed = $order->isFullyReviewedBy(auth()->id());
                            @endphp
                            @if($shouldShowReviewButton)
                                @if(!$hasReviewed)
                                    <a href="{{ route('orders.review', $order) }}" class="btn btn-outline-success me-2" style="border-radius: 8px;">
                                        <i class="fas fa-star me-1"></i>Review Items
                                    </a>
                                @endif
                            @elseif($isReviewedStatus)
                                <button type="button" class="btn btn-outline-secondary me-2" style="border-radius: 8px;" disabled title="Order already reviewed">
                                    <i class="fas fa-star me-1"></i>Order Reviewed
                                </button>
                            @endif
                            {{-- Removed duplicate $showReviewBtn logic and button. Only the new logic above is used. --}}
                            @if($order->status === 'pending')
                            <button type="button" class="btn btn-outline-danger" style="border-radius: 8px;"
                                    data-bs-toggle="modal" data-bs-target="#cancellationModal{{ $order->id }}">
                                <i class="fas fa-times me-1"></i>Cancel
                            </button>
                            @elseif($order->status === 'confirmed' && !$order->cancellation_requested)
                            <button type="button" class="btn btn-outline-warning" style="border-radius: 8px;"
                                    data-bs-toggle="modal" data-bs-target="#cancellationModal{{ $order->id }}">
                                <i class="fas fa-exclamation-triangle me-1"></i>Request Cancel
                            </button>
                            @elseif($order->status === 'confirmed' && $order->cancellation_requested)
                            <span class="text-warning small">
                                <i class="fas fa-clock me-1"></i>Cancellation Pending
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #CFB8BE;"></i>
                <h4 class="mt-3" style="color: #5D2B4C;">No orders yet</h4>
                <p class="text-muted">You haven't placed any orders yet. Start shopping to see your order history here.</p>
                <a href="{{ route('products.index') }}" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancellation Modals for each order -->
@foreach($orders as $order)
    @if($order->status === 'pending' || ($order->status === 'confirmed' && !$order->cancellation_requested))
    <div class="modal fade" id="cancellationModal{{ $order->id }}" tabindex="-1" aria-labelledby="cancellationModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancellationModalLabel{{ $order->id }}">
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
                        <small>Order #{{ $order->order_number }} - {{ $displayStatus }}</small>
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
    @endif
@endforeach

@endsection


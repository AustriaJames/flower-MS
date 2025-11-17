@extends('layouts.admin')

@section('page-title', 'Order Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order Details: {{ $order->order_number }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Order
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
        </div>
    </div>

    <!-- Order Information -->
    <div class="row">
        <!-- Main Order Details -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
                    <div class="d-flex gap-2">
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'confirmed' => 'info',
                                'processing' => 'primary',
                                'shipped' => 'info',
                                'delivered' => 'success',
                                'cancelled' => 'danger'
                            ];

                            $statusColor = $statusColors[$order->status] ?? 'secondary';
                            $isPickup = $order->delivery_type === 'pickup';
                            $displayStatus = $order->status;

                            if ($isPickup) {
                                $displayStatus = match($order->status) {
                                    'confirmed' => 'Approved',
                                    'processing' => 'For Preparation',
                                    'delivered' => 'Ready for Pickup',
                                    default => ucfirst($order->status),
                                };
                            } else {
                                $displayStatus = ucfirst($order->status);
                            }
                        @endphp
                        <span class="badge bg-{{ $statusColor }} fs-6">{{ $displayStatus }}</span>
                        <span class="badge bg-info fs-6">{{ $order->orderItems->count() }} Items</span>
                        @if($order->cancellation_requested)
                            <span class="badge bg-warning fs-6">Cancellation Requested</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">Order #{{ $order->order_number }}</h5>
                            <p class="text-muted mb-2">Customer: {{ $order->user->name }}</p>
                            <p class="text-muted mb-3">{{ $order->user->email }}</p>

                            <div class="mb-3">
                                <strong>Order Date:</strong>
                                <span class="text-muted ms-2">{{ $order->order_date->format('M d, Y \a\t h:i A') }}</span>
                            </div>

                            @if($order->estimated_delivery)
                            <div class="mb-3">
                                <strong>Estimated Delivery:</strong>
                                <span class="text-muted ms-2">{{ $order->estimated_delivery->format('M d, Y') }}</span>
                            </div>
                            @endif

                            @if(!$isPickup && $order->delivered_at)
                            <div class="mb-3">
                                <strong>Delivered:</strong>
                                <span class="text-success ms-2">{{ $order->delivered_at->format('M d, Y \a\t h:i A') }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="text-end">
                                <h4 class="text-success mb-2">₱{{ number_format($order->total_amount, 2) }}</h4>
                                <p class="text-muted mb-1">Subtotal: ₱{{ number_format($order->subtotal, 2) }}</p>
                                @if($order->tax_amount > 0)
                                    <p class="text-muted mb-1">Tax: ₱{{ number_format($order->tax_amount, 2) }}</p>
                                @endif
                                @if($order->shipping_amount > 0)
                                    <p class="text-muted mb-1">Shipping: ₱{{ number_format($order->shipping_amount, 2) }}</p>
                                @endif
                                @if($order->discount_amount > 0)
                                    <p class="text-danger mb-1">Discount: -₱{{ number_format($order->discount_amount, 2) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($order->notes)
                    <div class="mt-4">
                        <h6 class="text-primary">Order Notes</h6>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->main_image)
                                                <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}"
                                                     class="img-thumbnail me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center me-3"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                @if($item->product->is_featured)
                                                    <span class="badge bg-warning ms-2">Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>₱{{ number_format($item->unit_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $item->quantity }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">₱{{ number_format($item->total_price, 2) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tracking Information (only for delivery orders) -->
            @if($order->delivery_type === 'delivery')
                @if($order->tracking)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tracking Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Tracking Number:</strong>
                                    <span class="ms-2">{{ $order->tracking->tracking_number }}</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Carrier:</strong>
                                    <span class="ms-2">{{ $order->tracking->carrier }}</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Status:</strong>
                                    <span class="badge bg-{{ $order->tracking->is_delivered ? 'success' : 'info' }} ms-2">
                                        {{ ucfirst($order->tracking->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Current Location:</strong>
                                    <span class="ms-2">{{ $order->tracking->current_location ?? 'N/A' }}</span>
                                </div>
                                @if($order->tracking->estimated_delivery)
                                <div class="mb-3">
                                    <strong>Estimated Delivery:</strong>
                                    <span class="ms-2">{{ $order->tracking->estimated_delivery->format('M d, Y') }}</span>
                                </div>
                                @endif
                                @if($order->tracking->actual_delivery)
                                <div class="mb-3">
                                    <strong>Actual Delivery:</strong>
                                    <span class="ms-2 text-success">{{ $order->tracking->actual_delivery->format('M d, Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($order->tracking->description)
                        <div class="mt-3">
                            <strong>Description:</strong>
                            <p class="mb-0 mt-1">{{ $order->tracking->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tracking Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>No tracking information available yet.</strong>
                            <p class="mb-2 mt-2">Tracking information will be automatically created when the order status is changed to "shipped".</p>
                        </div>

                        <form method="POST" action="{{ route('admin.orders.create-tracking', $order) }}" class="mt-3">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="carrier" class="form-label">Carrier</label>
                                        <input type="text" class="form-control" id="carrier" name="carrier" value="Standard Delivery" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estimated_delivery" class="form-label">Estimated Delivery Date</label>
                                        <input type="date" class="form-control" id="estimated_delivery" name="estimated_delivery" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">Order has been processed and is ready for shipping</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-truck me-2"></i>Create Tracking Information
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-3"
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <strong>{{ $order->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong><i class="fas fa-phone me-2 text-primary"></i>Phone:</strong>
                        <br>
                        <a href="tel:{{ $order->user->phone }}" class="text-decoration-none">{{ $order->user->phone }}</a>
                    </div>

                    <div class="mb-0">
                        <strong><i class="fas fa-calendar me-2 text-primary"></i>Member Since:</strong>
                        <br>
                        <span class="text-muted">{{ $order->user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping & Billing -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Addresses</h6>
                </div>
                <div class="card-body">
                    @if($order->shippingAddress && $order->delivery_type === 'delivery')
                    <div class="mb-3">
                        <h6 class="text-primary">Shipping Address</h6>
                        <p class="mb-1">{{ $order->shippingAddress->full_name }}</p>
                        <p class="mb-1">{{ $order->shippingAddress->full_address }}</p>
                        <p class="mb-1">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                        <p class="mb-1">{{ $order->shippingAddress->country }}</p>
                        <p class="mb-0">{{ $order->shippingAddress->phone }}</p>
                    </div>
                    @endif

                    @if($order->billingAddress)
                    <div class="mb-0">
                        <h6 class="text-primary">Billing Address</h6>
                        <p class="mb-1">{{ $order->billingAddress->full_name }}</p>
                        <p class="mb-1">{{ $order->billingAddress->full_address }}</p>
                        <p class="mb-1">{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}</p>
                        <p class="mb-1">{{ $order->billingAddress->country }}</p>
                        <p class="mb-0">{{ $order->billingAddress->phone }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle w-100" type="button" onclick="toggleOrderStatusDropdown(this)">
                                <i class="fas fa-tasks me-2"></i>Update Status
                            </button>
                            <ul class="dropdown-menu w-100">
                                @php
                                    $allStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
                                @endphp
                                @foreach($allStatuses as $status)
                                    @if($status === $order->status)
                                        @continue
                                    @endif
                                    @php
                                        $label = ucfirst($status);
                                        if ($isPickup) {
                                            if ($status === 'shipped') continue;
                                            $label = match($status) {
                                                'confirmed' => 'Approved',
                                                'processing' => 'For Preparation',
                                                'delivered' => 'Ready for Pickup',
                                                default => ucfirst($status),
                                            };
                                        }
                                    @endphp
                                    <li>
                                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $status }}">
                                            <button type="submit" class="dropdown-item">
                                                Mark as {{ $label }}
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if($order->cancellation_requested && $order->status === 'confirmed')
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Cancellation Requested</strong>
                                <p class="mb-2 small">Customer has requested to cancel this order.</p>
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.orders.approve-cancellation', $order) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.orders.reject-cancellation', $order) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-info w-100">
                            <i class="fas fa-edit me-2"></i>Edit Order
                        </a>

                        <a href="{{ route('admin.users.show', $order->user) }}" class="btn btn-secondary w-100">
                            <i class="fas fa-user me-2"></i>View Customer
                        </a>

                        @if(!$order->tracking && $order->delivery_type === 'delivery')
                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#createTrackingModal">
                            <i class="fas fa-truck me-2"></i>Create Tracking
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Tracking Modal (delivery only) -->
@if(!$order->tracking && $order->delivery_type === 'delivery')
<div class="modal fade" id="createTrackingModal" tabindex="-1" aria-labelledby="createTrackingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTrackingModalLabel">
                    <i class="fas fa-truck me-2"></i>Create Tracking Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.orders.create-tracking', $order) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Creating tracking information for Order #{{ $order->order_number }}</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="carrier" class="form-label">Carrier <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="carrier" name="carrier" value="Standard Delivery" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estimated_delivery" class="form-label">Estimated Delivery Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="estimated_delivery" name="estimated_delivery" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">Order has been processed and is ready for shipping</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-truck me-1"></i>Create Tracking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    function toggleOrderStatusDropdown(button) {
        // Close any other open dropdowns in the Quick Actions card
        document.querySelectorAll('.card .dropdown-menu.show').forEach(function(menu) {
            if (menu !== button.nextElementSibling) {
                menu.classList.remove('show');
            }
        });

        const menu = button.nextElementSibling;
        if (menu) {
            menu.classList.toggle('show');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const isButton = event.target.closest('.btn.btn-primary.dropdown-toggle');
        const isMenu = event.target.closest('.dropdown-menu');

        if (!isButton && !isMenu) {
            document.querySelectorAll('.card .dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
            });
        }
    });
</script>
@endpush

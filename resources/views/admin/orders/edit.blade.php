@extends('layouts.admin')

@section('page-title', 'Edit Order')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Order: {{ $order->order_number }}</h1>
        <div>
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i>View Order
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
        </div>
    </div>

    <!-- Order Form -->
    <div class="card shadow">
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
                @endphp
                <span class="badge bg-{{ $statusColor }} fs-6">{{ ucfirst($order->status) }}</span>
                <span class="badge bg-info fs-6">{{ $order->orderItems->count() }} Items</span>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Order Information -->
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="order_number" class="form-label">Order Number</label>
                                <input type="text" class="form-control" id="order_number"
                                       value="{{ $order->order_number }}" readonly>
                                <small class="form-text text-muted">Order number cannot be changed</small>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Order Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                    @php
                                        $allStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
                                        $isPickup = $order->delivery_type === 'pickup';
                                    @endphp
                                    @foreach($allStatuses as $status)
                                        @php
                                            if ($isPickup && $status === 'shipped') {
                                                continue;
                                            }
                                            $label = ucfirst($status);
                                            if ($isPickup) {
                                                $label = match($status) {
                                                    'confirmed' => 'Approved',
                                                    'processing' => 'For Preparation',
                                                    'delivered' => 'Ready for Pickup',
                                                    default => ucfirst($status),
                                                };
                                            }
                                        @endphp
                                        <option value="{{ $status }}" {{ old('status', $order->status) == $status ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="order_date" class="form-label">Order Date <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('order_date') is-invalid @enderror"
                                       id="order_date" name="order_date"
                                       value="{{ old('order_date', $order->order_date->format('Y-m-d\TH:i')) }}" required>
                                @error('order_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="estimated_delivery" class="form-label">Estimated Delivery</label>
                                <input type="date" class="form-control @error('estimated_delivery') is-invalid @enderror"
                                       id="estimated_delivery" name="estimated_delivery"
                                       value="{{ old('estimated_delivery', $order->estimated_delivery ? $order->estimated_delivery->format('Y-m-d') : '') }}">
                                @error('estimated_delivery')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="subtotal" class="form-label">Subtotal <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control @error('subtotal') is-invalid @enderror"
                                           id="subtotal" name="subtotal"
                                           value="{{ old('subtotal', $order->subtotal) }}"
                                           step="0.01" min="0" required>
                                </div>
                                @error('subtotal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="total_amount" class="form-label">Total Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control @error('total_amount') is-invalid @enderror"
                                           id="total_amount" name="total_amount"
                                           value="{{ old('total_amount', $order->total_amount) }}"
                                           step="0.01" min="0" required>
                                </div>
                                @error('total_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tax_amount" class="form-label">Tax Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control @error('tax_amount') is-invalid @enderror"
                                           id="tax_amount" name="tax_amount"
                                           value="{{ old('tax_amount', $order->tax_amount) }}"
                                           step="0.01" min="0">
                                </div>
                                @error('tax_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="shipping_amount" class="form-label">Shipping Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control @error('shipping_amount') is-invalid @enderror"
                                           id="shipping_amount" name="shipping_amount"
                                           value="{{ old('shipping_amount', $order->shipping_amount) }}"
                                           step="0.01" min="0">
                                </div>
                                @error('shipping_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="discount_amount" class="form-label">Discount Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control @error('discount_amount') is-invalid @enderror"
                                           id="discount_amount" name="discount_amount"
                                           value="{{ old('discount_amount', $order->discount_amount) }}"
                                           step="0.01" min="0">
                                </div>
                                @error('discount_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sidebar Options -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Order Options</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="delivered_at" class="form-label">Delivered At</label>
                                    <input type="datetime-local" class="form-control @error('delivered_at') is-invalid @enderror"
                                           id="delivered_at" name="delivered_at"
                                           value="{{ old('delivered_at', $order->delivered_at ? $order->delivered_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('delivered_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Set when order is delivered</small>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Order Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="text-primary fw-bold">{{ $order->orderItems->count() }}</div>
                                        <small class="text-muted">Items</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-success fw-bold">₱{{ number_format($order->total_amount, 2) }}</div>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>

                                <hr>

                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="text-info fw-bold">{{ $order->user->name }}</div>
                                        <small class="text-muted">Customer</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-warning fw-bold">{{ $order->status }}</div>
                                        <small class="text-muted">Status</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Last updated: {{ $order->updated_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-calculate total amount
function calculateTotal() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
    const shipping = parseFloat(document.getElementById('shipping_amount').value) || 0;
    const discount = parseFloat(document.getElementById('discount_amount').value) || 0;

    const total = subtotal + tax + shipping - discount;
    document.getElementById('total_amount').value = total.toFixed(2);
}

// Add event listeners for calculation
document.getElementById('subtotal').addEventListener('input', calculateTotal);
document.getElementById('tax_amount').addEventListener('input', calculateTotal);
document.getElementById('shipping_amount').addEventListener('input', calculateTotal);
document.getElementById('discount_amount').addEventListener('input', calculateTotal);

// Auto-set delivered_at when status changes to delivered
document.getElementById('status').addEventListener('change', function() {
    const status = this.value;
    const deliveredAtField = document.getElementById('delivered_at');

    if (status === 'delivered' && !deliveredAtField.value) {
        const now = new Date();
        const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
        deliveredAtField.value = localDateTime;
    }
});

// Validate discount amount
document.getElementById('discount_amount').addEventListener('input', function() {
    const discount = parseFloat(this.value) || 0;
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;

    if (discount > subtotal) {
        this.setCustomValidity('Discount cannot exceed subtotal');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endpush
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle" style="font-size: 4rem; color: #5D2B4C;"></i>
                    </div>
                    <h4 class="fw-bold" style="color: #5D2B4C;">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <p class="text-muted">{{ $user->phone ?? 'No phone number' }}</p>

                    <button class="btn btn-outline-primary" style="border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Bookings -->
        <div class="col-lg-8">
            <!-- Recent Orders -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                        <i class="fas fa-shopping-bag me-2"></i>Recent Orders
                    </h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                        <div class="row align-items-center py-3 border-bottom">
                            <div class="col-md-3">
                                <strong>Order #{{ $order->order_number }}</strong>
                                <br>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'processing' ? 'warning' : 'info') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                                View All Orders
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No orders yet. <a href="{{ route('products.index') }}">Start shopping!</a></p>
                    @endif
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                        <i class="fas fa-calendar-check me-2"></i>Recent Bookings
                    </h5>
                </div>
                <div class="card-body">
                    @if($bookings->count() > 0)
                        @foreach($bookings as $booking)
                        <div class="row align-items-center py-3 border-bottom">
                            <div class="col-md-3">
                                <strong>{{ $booking->event_type }}</strong>
                                <br>
                                <small class="text-muted">{{ $booking->event_date->format('M d, Y') }}</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                {{ $booking->guest_count }} guests
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                                View All Bookings
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No bookings yet. <a href="{{ route('bookings.create') }}">Book an event!</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0" style="background: #F5EEE4;">
                <h5 class="modal-title fw-bold" id="editProfileModalLabel" style="color: #5D2B4C;">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="first_name" class="form-label fw-semibold" style="color: #5D2B4C;">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="middle_name" class="form-label fw-semibold" style="color: #5D2B4C;">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="last_name" class="form-label fw-semibold" style="color: #5D2B4C;">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold" style="color: #5D2B4C;">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px; padding: 12px;">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
// Auto-close modal and refresh page after successful profile update
@if(session('success'))
    // Close the modal if it's open
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }

    // Refresh the page after a short delay to show updated information
    setTimeout(function() {
        window.location.reload();
    }, 1500);
@endif

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#editProfileModal form');
    if (form) {
        form.addEventListener('submit', function() {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
            submitBtn.disabled = true;
        });
    }
});
</script>

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                </a>
            </div>

            <!-- Booking Header -->
            <div class="text-center mb-5">
                <div class="mb-3">
                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'completed' ? 'info' : 'secondary')) }} fs-5 px-4 py-2">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <h2 class="fw-bold mb-2" style="color: #5D2B4C;">
                    <i class="fas fa-calendar-alt me-2"></i>{{ ucfirst($booking->event_type) }} Event
                </h2>
                <p class="lead text-muted">{{ $booking->event_date }} at {{ $booking->event_time }}</p>
            </div>

            <!-- Event Details -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                        <i class="fas fa-info-circle me-2"></i>Event Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Event Type</label>
                            <p class="mb-0">{{ ucfirst($booking->event_type) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Event Date</label>
                            <p class="mb-0">{{ $booking->event_date }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Event Time</label>
                            <p class="mb-0">{{ $booking->event_time }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Guest Count</label>
                            <p class="mb-0">{{ $booking->guest_count ? $booking->guest_count . ' guests' : 'Not specified' }}</p>
                        </div>
                        @if($booking->event_description)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Event Description</label>
                            <p class="mb-0">{{ $booking->event_description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Floral Requirements -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                        <i class="fas fa-flower-tulip me-2"></i>Floral Requirements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Flower Preferences</label>
                            <p class="mb-0">{{ $booking->flower_preferences ? ucfirst($booking->flower_preferences) : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Color Scheme</label>
                            <p class="mb-0">{{ $booking->color_scheme ? ucfirst($booking->color_scheme) : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Arrangement Type</label>
                            <p class="mb-0">{{ $booking->arrangement_type ? ucfirst($booking->arrangement_type) : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Budget Range</label>
                            <p class="mb-0">{{ $booking->budget_range ? '₱' . str_replace('-', ' - ₱', $booking->budget_range) : 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Services -->
            @if($booking->additional_services)
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                        <i class="fas fa-plus-circle me-2"></i>Additional Services
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $services = is_array($booking->additional_services) ? $booking->additional_services : json_decode($booking->additional_services, true);
                        @endphp
                        @if($services)
                            @foreach($services as $service)
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                {{ ucfirst(str_replace('_', ' ', $service)) }}
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Special Requests -->
            @if($booking->special_requests)
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                        <i class="fas fa-star me-2"></i>Special Requests
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $booking->special_requests }}</p>
                </div>
            </div>
            @endif

            <!-- Contact Information -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                        <i class="fas fa-user me-2"></i>Contact Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Contact Person</label>
                            <p class="mb-0">{{ $booking->contact_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Contact Phone</label>
                            <p class="mb-0">{{ $booking->contact_phone }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Contact Email</label>
                            <p class="mb-0">{{ $booking->contact_email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Timeline -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                        <i class="fas fa-clock me-2"></i>Booking Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold" style="color: #5D2B4C;">Booking Submitted</h6>
                                <p class="text-muted mb-1">Your booking request was submitted</p>
                                <small class="text-muted">{{ $booking->created_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                        @if($booking->status === 'confirmed')
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold" style="color: #5D2B4C;">Booking Confirmed</h6>
                                <p class="text-muted mb-1">Your booking has been confirmed by our team</p>
                                <small class="text-muted">{{ $booking->updated_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                        @endif
                        @if($booking->status === 'completed')
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold" style="color: #5D2B4C;">Event Completed</h6>
                                <p class="text-muted mb-1">Your event has been successfully completed</p>
                                <small class="text-muted">{{ $booking->updated_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                        @endif
                        @if($booking->status === 'cancelled')
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold" style="color: #5D2B4C;">Booking Cancelled</h6>
                                <p class="text-muted mb-1">Your booking has been cancelled</p>
                                <small class="text-muted">{{ $booking->updated_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-center gap-3 mb-5">
                @if($booking->status === 'pending')
                <button class="btn btn-outline-danger px-4" onclick="cancelBooking({{ $booking->id }})">
                    <i class="fas fa-times me-2"></i>Cancel Booking
                </button>
                @endif
                @if($booking->status === 'confirmed')
                <button class="btn btn-success px-4" onclick="markCompleted({{ $booking->id }})">
                    <i class="fas fa-check me-2"></i>Mark as Completed
                </button>
                @endif
            </div>

            <!-- Contact Support -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-bold mb-0" style="color: #5D2B4C;">Need Help?</h6>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-3">If you have any questions about your booking, please contact us:</p>
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
                            <a href="{{ route('chat.index') }}" class="text-decoration-none">
                                <i class="fas fa-comments" style="font-size: 2rem; color: #5D2B4C;"></i>
                                <p class="mt-2">Live Chat</p>
                            </a>
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

<script>
function cancelBooking(bookingId) {
    Swal.fire({
        icon: 'warning',
        title: 'Cancel Booking?',
        text: 'Are you sure you want to cancel this booking? This action cannot be undone.',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'Keep booking'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/bookings/${bookingId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cancelled',
                        text: 'Booking cancelled successfully.',
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error cancelling booking: ' + (data.message || 'Please try again.'),
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error cancelling booking. Please try again.',
                });
            });
        }
    });
}

function markCompleted(bookingId) {
    Swal.fire({
        icon: 'question',
        title: 'Mark as Completed?',
        text: 'Are you sure you want to mark this booking as completed?',
        showCancelButton: true,
        confirmButtonText: 'Yes, mark completed',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/bookings/${bookingId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: 'completed' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: 'Booking marked as completed successfully.',
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating booking: ' + (data.message || 'Please try again.'),
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating booking. Please try again.',
                });
            });
        }
    });
}
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold mb-2" style="color: #5D2B4C;">
                        <i class="fas fa-calendar-alt me-2"></i>My Event Bookings
                    </h2>
                    <p class="lead text-muted">Manage your upcoming and past event bookings</p>
                </div>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary px-4" style="background-color: #5D2B4C; border-color: #5D2B4C;">
                    <i class="fas fa-plus me-2"></i>Book New Event
                </a>
            </div>

            @if($bookings->count() > 0)
                <!-- Active Bookings -->
                <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                            <i class="fas fa-clock me-2"></i>Active Bookings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($bookings->where('status', '!=', 'completed')->where('status', '!=', 'cancelled') as $booking)
                            <div class="col-lg-6 mb-4">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1" style="color: #5D2B4C;">{{ ucfirst($booking->event_type) }}</h6>
                                                <p class="text-muted small mb-0">{{ $booking->event_date }} at {{ $booking->event_time }}</p>
                                            </div>
                                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'info') }} px-3 py-2">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>

                                        <div class="mb-3">
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                {{ $booking->event_description ? Str::limit($booking->event_description, 100) : 'No description provided' }}
                                            </p>
                                            @if($booking->guest_count)
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-users me-2"></i>
                                                {{ $booking->guest_count }} guests expected
                                            </p>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Booked on {{ $booking->created_at->format('M d, Y') }}</small>
                                            <div>
                                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm me-2">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                                @if($booking->status === 'pending')
                                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary btn-sm me-2">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <button class="btn btn-outline-danger btn-sm" onclick="cancelBooking({{ $booking->id }})">
                                                    <i class="fas fa-times me-1"></i>Cancel
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Completed/Cancelled Bookings -->
                <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="fw-bold mb-0" style="color: #5D2B4C;">
                            <i class="fas fa-history me-2"></i>Past Bookings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($bookings->whereIn('status', ['completed', 'cancelled']) as $booking)
                            <div class="col-lg-6 mb-4">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; opacity: 0.7;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1" style="color: #5D2B4C;">{{ ucfirst($booking->event_type) }}</h6>
                                                <p class="text-muted small mb-0">{{ $booking->event_date }} at {{ $booking->event_time }}</p>
                                            </div>
                                            <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : 'secondary' }} px-3 py-2">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>

                                        <div class="mb-3">
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                {{ $booking->event_description ? Str::limit($booking->event_description, 100) : 'No description provided' }}
                                            </p>
                                            @if($booking->guest_count)
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-users me-2"></i>
                                                {{ $booking->guest_count }} guests expected
                                            </p>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Booked on {{ $booking->created_at->format('M d, Y') }}</small>
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="card border-0 shadow-lg text-center py-5" style="border-radius: 20px;">
                    <div class="card-body">
                        <i class="fas fa-calendar-plus" style="font-size: 4rem; color: #CFB8BE;"></i>
                        <h4 class="mt-3 mb-3" style="color: #5D2B4C;">No Bookings Yet</h4>
                        <p class="text-muted mb-4">You haven't made any event bookings yet. Start planning your special occasion with us!</p>
                        <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-lg px-5" style="background-color: #5D2B4C; border-color: #5D2B4C;">
                            <i class="fas fa-calendar-plus me-2"></i>Book Your First Event
                        </a>
                    </div>
                </div>
            @endif

            <!-- Booking Statistics -->
            @if($bookings->count() > 0)
            <div class="row mt-5">
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-lg text-center" style="border-radius: 20px;">
                        <div class="card-body">
                            <i class="fas fa-clock" style="font-size: 2.5rem; color: #5D2B4C;"></i>
                            <h3 class="fw-bold mt-2" style="color: #5D2B4C;">{{ $bookings->where('status', 'pending')->count() }}</h3>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-lg text-center" style="border-radius: 20px;">
                        <div class="card-body">
                            <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #5D2B4C;"></i>
                            <h3 class="fw-bold mt-2" style="color: #5D2B4C;">{{ $bookings->where('status', 'confirmed')->count() }}</h3>
                            <p class="text-muted mb-0">Confirmed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-lg text-center" style="border-radius: 20px;">
                        <div class="card-body">
                            <i class="fas fa-flag-checkered" style="font-size: 2.5rem; color: #5D2B4C;"></i>
                            <h3 class="fw-bold mt-2" style="color: #5D2B4C;">{{ $bookings->where('status', 'completed')->count() }}</h3>
                            <p class="text-muted mb-0">Completed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-lg text-center" style="border-radius: 20px;">
                        <div class="card-body">
                            <i class="fas fa-calendar-alt" style="font-size: 2.5rem; color: #5D2B4C;"></i>
                            <h3 class="fw-bold mt-2" style="color: #5D2B4C;">{{ $bookings->count() }}</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

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
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
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
</script>
@endsection

@extends('layouts.admin')

@section('page-title', 'Booking Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Booking #{{ $booking->id }}</h1>
                <p class="text-muted mb-0">Event: {{ ucfirst($booking->event_type) }} on {{ $booking->event_date->format('M d, Y') }}</p>
            </div>
            <div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Bookings
                </a>
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Booking Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Customer Details</h6>
                            <p><strong>Name:</strong> {{ $booking->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $booking->customer_email }}</p>
                            <p><strong>Phone:</strong> {{ $booking->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Event Details</h6>
                            <p><strong>Event Type:</strong>
                                <span class="badge bg-info">{{ ucfirst($booking->event_type) }}</span>
                            </p>
                            <p><strong>Event Date:</strong> {{ $booking->event_date->format('F d, Y') }}</p>
                            <p><strong>Event Time:</strong> {{ $booking->event_time }}</p>
                            <p><strong>Venue:</strong> {{ $booking->venue ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Requirements & Budget</h6>
                            <p><strong>Requirements:</strong></p>
                            <p class="text-muted">{{ $booking->requirements ?? 'No specific requirements' }}</p>
                            <p><strong>Budget Range:</strong> {{ $booking->budget_range ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Status & Dates</h6>
                            <p><strong>Status:</strong>
                                <span class="badge {{ $booking->status_badge_class }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </p>
                            <p><strong>Created:</strong> {{ $booking->created_at->format('M d, Y g:i A') }}</p>
                            @if($booking->confirmed_at)
                                <p><strong>Confirmed:</strong> {{ $booking->confirmed_at->format('M d, Y g:i A') }}</p>
                            @endif
                            @if($booking->cancelled_at)
                                <p><strong>Cancelled:</strong> {{ $booking->cancelled_at->format('M d, Y g:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    @if($booking->admin_notes)
                        <hr>
                        <div>
                            <h6 class="text-muted">Admin Notes</h6>
                            <p class="text-muted">{{ $booking->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <!-- Status Update -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">New Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="rescheduled" {{ $booking->status == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3"
                                      placeholder="Add notes about this status change...">{{ $booking->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($booking->status !== 'confirmed')
                            <button type="button" class="btn btn-success" onclick="confirmBooking()">
                                <i class="bi bi-check-circle"></i> Confirm Booking
                            </button>
                        @endif

                        @if($booking->status !== 'cancelled')
                            <button type="button" class="btn btn-danger" onclick="cancelBooking()">
                                <i class="bi bi-x-circle"></i> Cancel Booking
                            </button>
                        @endif

                        <button type="button" class="btn btn-warning" onclick="rescheduleBooking()">
                            <i class="bi bi-calendar-plus"></i> Reschedule
                        </button>

                        <a href="{{ route('admin.bookings.export', $booking) }}" class="btn btn-info">
                            <i class="bi bi-download"></i> Export Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            @if($booking->user)
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Customer History</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Member Since:</strong> {{ $booking->user->created_at->format('M d, Y') }}</p>
                        <p><strong>Total Orders:</strong> {{ $booking->user->orders->count() }}</p>
                        <a href="{{ route('admin.users.show', $booking->user) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person"></i> View Customer
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reschedule Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.bookings.reschedule', $booking) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_date" class="form-label">New Event Date</label>
                        <input type="date" name="new_date" id="new_date" class="form-control"
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_time" class="form-label">New Event Time</label>
                        <input type="time" name="new_time" id="new_time" class="form-control"
                               value="{{ $booking->event_time }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Rescheduling</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3"
                                  placeholder="Please provide a reason for rescheduling..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reschedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Are you sure you want to cancel this booking?</p>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Cancellation</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3"
                                  placeholder="Please provide a reason for cancellation..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep</button>
                    <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmBooking() {
    if (confirm('Are you sure you want to confirm this booking?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.bookings.update-status", $booking) }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PATCH';

        const status = document.createElement('input');
        status.type = 'hidden';
        status.name = 'status';
        status.value = 'confirmed';

        form.appendChild(csrfToken);
        form.appendChild(method);
        form.appendChild(status);
        document.body.appendChild(form);
        form.submit();
    }
}

function cancelBooking() {
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}

function rescheduleBooking() {
    const modal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
    modal.show();
}
</script>
@endpush
@endsection

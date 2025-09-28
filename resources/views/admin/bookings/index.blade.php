@extends('layouts.admin')

@section('page-title', 'Bookings Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Event Bookings</h1>
                <p class="text-muted mb-0">Manage customer event bookings and reservations</p>
            </div>
            <div>
                <a href="{{ route('admin.bookings.calendar') }}" class="btn btn-info">
                    <i class="bi bi-calendar-event"></i> Calendar View
                </a>
                <a href="{{ route('admin.bookings.export') }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Export
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="rescheduled" {{ request('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="event_type" class="form-label">Event Type</label>
                <select name="event_type" id="event_type" class="form-select">
                    <option value="">All Event Types</option>
                    <option value="wedding" {{ request('event_type') == 'wedding' ? 'selected' : '' }}>Wedding</option>
                    <option value="birthday" {{ request('event_type') == 'birthday' ? 'selected' : '' }}>Birthday</option>
                    <option value="anniversary" {{ request('event_type') == 'anniversary' ? 'selected' : '' }}>Anniversary</option>
                    <option value="graduation" {{ request('event_type') == 'graduation' ? 'selected' : '' }}>Graduation</option>
                    <option value="funeral" {{ request('event_type') == 'funeral' ? 'selected' : '' }}>Funeral</option>
                    <option value="corporate" {{ request('event_type') == 'corporate' ? 'selected' : '' }}>Corporate</option>
                    <option value="other" {{ request('event_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" name="date_from" id="date_from" class="form-control"
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" name="date_to" id="date_to" class="form-control"
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="content-section">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">All Bookings</h6>
            </div>
            <div class="card-body">
                @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table id="bookingsTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Event Type</th>
                                    <th>Event Date</th>
                                    <th>Status</th>
                                    <th>Budget</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    <tr>
                                        <td>
                                            <strong>#{{ $booking->id }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $booking->customer_name }}</strong><br>
                                                <small class="text-muted">{{ $booking->customer_email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($booking->event_type) }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $booking->event_date->format('M d, Y') }}</strong><br>
                                                <small class="text-muted">{{ $booking->event_time }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $booking->status_badge_class }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $booking->budget_range ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $booking->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('admin.bookings.show', $booking) }}"
                                                   class="btn btn-action btn-info btn-action-sm" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.bookings.edit', $booking) }}"
                                                   class="btn btn-action btn-warning btn-action-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-action btn-danger btn-action-sm"
                                                        title="Delete" onclick="deleteBooking({{ $booking->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                @else
                    <div class="empty-state">
                        <i class="bi bi-calendar-event"></i>
                        <h5>No Bookings Found</h5>
                        <p>There are no event bookings matching your criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this booking? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteBookingForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#bookingsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 25,
        order: [[3, 'asc']], // Sort by event date by default
        columnDefs: [
            { orderable: false, targets: [7] } // Disable sorting for actions column
        ],
        language: {
            search: "Search bookings:",
            lengthMenu: "Show _MENU_ bookings per page",
            info: "Showing _START_ to _END_ of _TOTAL_ bookings",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});

function deleteBooking(bookingId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteBookingModal'));
    const form = document.getElementById('deleteBookingForm');
    form.action = `/admin/bookings/${bookingId}`;
    modal.show();
}
</script>
@endpush
@endsection

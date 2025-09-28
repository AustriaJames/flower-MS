@extends('layouts.admin')

@section('page-title', 'Bookings Calendar')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Bookings Calendar</h1>
                <p class="text-muted mb-0">Visual calendar view of all event bookings</p>
            </div>
            <div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Legend -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-muted mb-3">Status Legend:</h6>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2">Pending</span>
                            <small class="text-muted">Awaiting confirmation</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info me-2">Confirmed</span>
                            <small class="text-muted">Booking confirmed</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2">Rescheduled</span>
                            <small class="text-muted">Date/time changed</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2">Completed</span>
                            <small class="text-muted">Event finished</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-danger me-2">Cancelled</span>
                            <small class="text-muted">Booking cancelled</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="card shadow">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventModalBody">
                <!-- Event details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="viewBookingBtn" class="btn btn-primary">
                    <i class="bi bi-eye"></i> View Full Details
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
.fc-event {
    cursor: pointer;
    border-radius: 4px;
    padding: 2px 4px;
    font-size: 12px;
    font-weight: 500;
}

.fc-event:hover {
    opacity: 0.8;
}

.booking-pending {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
    color: #000 !important;
}

.booking-confirmed {
    background-color: #17a2b8 !important;
    border-color: #17a2b8 !important;
    color: #fff !important;
}

.booking-rescheduled {
    background-color: #fd7e14 !important;
    border-color: #fd7e14 !important;
    color: #fff !important;
}

.booking-completed {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: #fff !important;
}

.booking-cancelled {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: #fff !important;
}

.fc-toolbar-title {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
}

.fc-button {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: white !important;
    font-weight: 500 !important;
}

.fc-button:hover {
    background-color: #2563eb !important;
    border-color: #2563eb !important;
}

.fc-button:focus {
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25) !important;
}

.fc-daygrid-day-number {
    font-weight: 500 !important;
}

.fc-col-header-cell {
    background-color: #f8fafc !important;
    font-weight: 600 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        height: 'auto',
        events: @json($bookings),
        eventClick: function(info) {
            showEventDetails(info.event);
        },
        eventDidMount: function(info) {
            // Add tooltip
            info.el.title = info.event.title;
        },
        dayMaxEvents: true,
        moreLinkClick: 'popover',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            list: 'List'
        },
        firstDay: 1, // Monday
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5, 6, 0], // Monday - Sunday
            startTime: '08:00',
            endTime: '18:00',
        },
        selectable: true,
        selectMirror: true,
        select: function(arg) {
            // Handle date selection if needed
            console.log('Date selected:', arg.startStr);
        },
        editable: false,
        dayMaxEvents: true,
        weekends: true,
        navLinks: true,
        nowIndicator: true,
        eventDisplay: 'block',
        eventColor: '#3b82f6',
        eventTextColor: '#ffffff',
        eventBorderColor: '#3b82f6'
    });

    calendar.render();
});

function showEventDetails(event) {
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    const modalTitle = document.getElementById('eventModalTitle');
    const modalBody = document.getElementById('eventModalBody');
    const viewBtn = document.getElementById('viewBookingBtn');

    // Set modal title
    modalTitle.textContent = event.title;

    // Get event data
    const extendedProps = event.extendedProps;

    // Create event details HTML
    let detailsHtml = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted">Event Details</h6>
                <p><strong>Type:</strong> ${extendedProps.event_type}</p>
                <p><strong>Date:</strong> ${event.start.toLocaleDateString()}</p>
                <p><strong>Status:</strong>
                    <span class="badge bg-${getStatusColor(extendedProps.status)}">
                        ${extendedProps.status.charAt(0).toUpperCase() + extendedProps.status.slice(1)}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Customer</h6>
                <p><strong>Name:</strong> ${extendedProps.customer}</p>
                <p><strong>Requirements:</strong></p>
                <p class="text-muted small">${extendedProps.requirements || 'No specific requirements'}</p>
            </div>
        </div>
    `;

    modalBody.innerHTML = detailsHtml;

    // Set view button href
    viewBtn.href = `/admin/bookings/${event.id}`;

    modal.show();
}

function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'confirmed': 'info',
        'rescheduled': 'warning',
        'completed': 'success',
        'cancelled': 'danger'
    };
    return colors[status] || 'secondary';
}
</script>
@endpush
@endsection

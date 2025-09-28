@extends('layouts.admin')

@section('page-title', 'Edit Booking')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Edit Booking #{{ $booking->id }}</h1>
                <p class="text-muted mb-0">Modify booking information and details</p>
            </div>
            <div>
                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Booking
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Booking Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Customer Name *</label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                                           value="{{ old('customer_name', $booking->customer_name) }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_email" class="form-label">Customer Email *</label>
                                    <input type="email" name="customer_email" id="customer_email" class="form-control @error('customer_email') is-invalid @enderror"
                                           value="{{ old('customer_email', $booking->customer_email) }}" required>
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Customer Phone *</label>
                                    <input type="text" name="customer_phone" id="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror"
                                           value="{{ old('customer_phone', $booking->customer_phone) }}" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="event_type" class="form-label">Event Type *</label>
                                    <select name="event_type" id="event_type" class="form-select @error('event_type') is-invalid @enderror" required>
                                        @foreach($eventTypes as $type)
                                            <option value="{{ $type }}" {{ old('event_type', $booking->event_type) == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="event_date" class="form-label">Event Date *</label>
                                    <input type="date" name="event_date" id="event_date" class="form-control @error('event_date') is-invalid @enderror"
                                           value="{{ old('event_date', $booking->event_date->format('Y-m-d')) }}" required>
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="event_time" class="form-label">Event Time *</label>
                                    <input type="time" name="event_time" id="event_time" class="form-control @error('event_time') is-invalid @enderror"
                                           value="{{ old('event_time', $booking->event_time) }}" required>
                                    @error('event_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue</label>
                            <input type="text" name="venue" id="venue" class="form-control @error('venue') is-invalid @enderror"
                                   value="{{ old('venue', $booking->venue) }}" placeholder="Event venue or location">
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Requirements</label>
                            <textarea name="requirements" id="requirements" class="form-control @error('requirements') is-invalid @enderror"
                                      rows="4" placeholder="Special requirements or requests">{{ old('requirements', $booking->requirements) }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="budget_range" class="form-label">Budget Range</label>
                                    <input type="text" name="budget_range" id="budget_range" class="form-control @error('budget_range') is-invalid @enderror"
                                           value="{{ old('budget_range', $booking->budget_range) }}" placeholder="e.g., ₱1000-5000">
                                    @error('budget_range')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $booking->status) == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control @error('admin_notes') is-invalid @enderror"
                                      rows="3" placeholder="Internal notes about this booking">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Booking Summary -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Current Booking Info</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Customer:</strong><br>
                        {{ $booking->customer_name }}<br>
                        <small class="text-muted">{{ $booking->customer_email }}</small>
                    </div>

                    <div class="mb-3">
                        <strong>Event:</strong><br>
                        <span class="badge bg-info">{{ ucfirst($booking->event_type) }}</span><br>
                        <small class="text-muted">{{ $booking->event_date->format('M d, Y') }} at {{ $booking->event_time }}</small>
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge {{ $booking->status_badge_class }}">{{ ucfirst($booking->status) }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        <small class="text-muted">{{ $booking->created_at->format('M d, Y g:i A') }}</small>
                    </div>

                    @if($booking->user)
                        <div class="mb-3">
                            <strong>Customer Account:</strong><br>
                            <a href="{{ route('admin.users.show', $booking->user) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-person"></i> View Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-info">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                        <a href="{{ route('admin.bookings.export', $booking) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

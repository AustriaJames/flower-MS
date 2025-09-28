@extends('layouts.admin')

@section('page-title', 'Edit User')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i>View Profile
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <!-- User Form -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
            <div class="d-flex gap-2">
                <span class="badge bg-{{ $user->is_admin ? 'danger' : 'secondary' }} fs-6">
                    {{ $user->is_admin ? 'Admin' : 'User' }}
                </span>
                <span class="badge bg-success fs-6">Active</span>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name"
                                       value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                       id="middle_name" name="middle_name"
                                       value="{{ old('middle_name', $user->middle_name) }}">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name"
                                       value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone"
                                       value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                   id="new_password" name="new_password" minlength="8">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Leave blank to keep current password. Must be at least 8 characters with uppercase, lowercase, number, and special character.
                            </small>
                        </div>
                    </div>

                    <!-- Sidebar Options -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">User Options</h6>
                            </div>
                            <div class="card-body">
                                @if($user->id !== auth()->id())
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin"
                                                   value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_admin">
                                                Admin User
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Grant administrative privileges to this user</small>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked disabled>
                                            <label class="form-check-label">
                                                Admin User (Own Account)
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Cannot modify your own admin status</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- User Stats -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">User Statistics</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="text-primary fw-bold">{{ $user->orders->count() }}</div>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-success fw-bold">{{ $user->reviews->count() }}</div>
                                        <small class="text-muted">Reviews</small>
                                    </div>
                                </div>

                                <hr>

                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="text-info fw-bold">₱{{ number_format($user->orders->sum('total_amount'), 2) }}</div>
                                        <small class="text-muted">Total Spent</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-warning fw-bold">{{ $user->cartItems->count() }}</div>
                                        <small class="text-muted">Cart Items</small>
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
                                <small class="text-muted">Last updated: {{ $user->updated_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update User
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
// Password strength indicator
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const strength = checkPasswordStrength(password);

    // Remove existing strength classes
    this.classList.remove('is-valid', 'is-invalid');

    if (password.length > 0) {
        if (strength >= 3) {
            this.classList.add('is-valid');
        } else {
            this.classList.add('is-invalid');
        }
    }
});

function checkPasswordStrength(password) {
    let strength = 0;

    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    return strength;
}

// Auto-update display name preview
document.getElementById('first_name').addEventListener('input', updateDisplayName);
document.getElementById('middle_name').addEventListener('input', updateDisplayName);
document.getElementById('last_name').addEventListener('input', updateDisplayName);

function updateDisplayName() {
    const firstName = document.getElementById('first_name').value;
    const middleName = document.getElementById('middle_name').value;
    const lastName = document.getElementById('last_name').value;

    let displayName = firstName;
    if (middleName) displayName += ' ' + middleName;
    if (lastName) displayName += ' ' + lastName;

    // Update page title if possible
    const pageTitle = document.querySelector('h1');
    if (pageTitle) {
        pageTitle.textContent = 'Edit User: ' + (displayName || 'User');
    }
}
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('page-title', 'User Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Profile: {{ $user->name }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <!-- User Information -->
    <div class="row">
        <!-- Main User Details -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
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
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary">{{ $user->name }}</h5>
                            <p class="text-muted mb-2">{{ $user->email }}</p>
                            <p class="text-muted mb-3">{{ $user->phone }}</p>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>First Name:</strong>
                                    <span class="ms-2">{{ $user->first_name }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Last Name:</strong>
                                    <span class="ms-2">{{ $user->last_name }}</span>
                                </div>
                            </div>

                            @if($user->middle_name)
                            <div class="mb-3">
                                <strong>Middle Name:</strong>
                                <span class="ms-2">{{ $user->middle_name }}</span>
                            </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Joined:</strong>
                                    <span class="text-muted ms-2">{{ $user->created_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Last Updated:</strong>
                                    <span class="text-muted ms-2">{{ $user->updated_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Member Since:</strong>
                                <span class="text-muted ms-2">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="col-md-4 text-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                            <h6 class="text-primary">{{ $user->name }}</h6>
                            <p class="text-muted mb-0">{{ $user->is_admin ? 'Administrator' : 'Customer' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Orders -->
            @if($user->orders->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->orders->take(10) as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
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
                                        <span class="badge bg-{{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">₱{{ number_format($order->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        {{ $order->order_date->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $order->order_date->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="btn btn-sm btn-info" title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($user->orders->count() > 10)
                    <div class="text-center mt-3">
                        <p class="text-muted">Showing first 10 orders. Total: {{ $user->orders->count() }} orders</p>
                        <a href="{{ route('admin.orders.index') }}?user={{ $user->id }}" class="btn btn-primary">
                            View All Orders
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- User Reviews -->
            @if($user->reviews->count() > 0)
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Reviews</h6>
                </div>
                <div class="card-body">
                    @foreach($user->reviews->take(5) as $review)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $review->product->name }}</strong>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                    <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                                </div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                        </div>
                        <p class="mt-2 mb-0">{{ $review->comment }}</p>
                        @if($review->is_verified_purchase)
                            <span class="badge bg-success mt-2">Verified Purchase</span>
                        @endif
                    </div>
                    @endforeach

                    @if($user->reviews->count() > 5)
                    <div class="text-center mt-3">
                        <p class="text-muted">Showing first 5 reviews. Total: {{ $user->reviews->count() }} reviews</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- User Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-primary fw-bold fs-4">{{ $user->orders->count() }}</div>
                            <small class="text-muted">Total Orders</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-success fw-bold fs-4">₱{{ number_format($user->orders->sum('total_amount'), 2) }}</div>
                            <small class="text-muted">Total Spent</small>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-info fw-bold">{{ $user->reviews->count() }}</div>
                            <small class="text-muted">Reviews</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-warning fw-bold">{{ $user->cartItems->count() }}</div>
                            <small class="text-muted">Cart Items</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                            
                            </form>

                            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                    data-bs-target="#resetPasswordModal">
                                <i class="fas fa-key me-2"></i>Reset Password
                            </button>

                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-info w-100">
                                <i class="fas fa-edit me-2"></i>Edit User
                            </a>

                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Delete User
                                </button>
                            </form>
                        @else
                            <span class="text-muted text-center">Cannot modify own account</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="fas fa-envelope me-2 text-primary"></i>Email:</strong>
                        <br>
                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a>
                    </div>

                    <div class="mb-3">
                        <strong><i class="fas fa-phone me-2 text-primary"></i>Phone:</strong>
                        <br>
                        <a href="tel:{{ $user->phone }}" class="text-decoration-none">{{ $user->phone }}</a>
                    </div>

                    <div class="mb-0">
                        <strong><i class="fas fa-calendar me-2 text-primary"></i>Member Since:</strong>
                        <br>
                        <span class="text-muted">{{ $user->created_at->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
@if($user->id !== auth()->id())
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password for {{ $user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password"
                               name="new_password" required minlength="8">
                        <small class="form-text text-muted">
                            Password must be at least 8 characters with uppercase, lowercase, number, and special character.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

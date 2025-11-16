@extends('layouts.admin')

@section('page-title', 'Users Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Users Management</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.export') }}" class="btn-action btn-success btn-action-lg">
                    <i class="fas fa-download"></i>
                    <span>Export Users</span>
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn-action btn-primary btn-action-lg">
                    <i class="fas fa-plus"></i>
                    <span>Add New User</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-bordered table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Orders</th>
                            <th>Reviews</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->middle_name)
                                        <br>
                                        <small class="text-muted">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td>
                                <a href="tel:{{ $user->phone }}" class="text-decoration-none">
                                    {{ $user->phone }}
                                </a>
                            </td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $user->orders_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $user->reviews_count }}</span>
                            </td>
                            <td>
                                {{ $user->created_at->format('M d, Y') }}
                                <br>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="btn-action btn-info" title="View Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn-action btn-warning" title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modals -->
@foreach($users as $user)
@if($user->id !== auth()->id())
<div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1">
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
                        <label for="new_password{{ $user->id }}" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password{{ $user->id }}"
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
@endforeach

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
    $('#usersTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 25,
        order: [[8, 'desc']], // Sort by joined date by default
        columnDefs: [
            { orderable: false, targets: [1, 9] } // Disable sorting for avatar and actions columns
        ],
        language: {
            search: "Search users:",
            lengthMenu: "Show _MENU_ users per page",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});
</script>
@endpush
@endsection

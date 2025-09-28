@extends('layouts.admin')

@section('page-title', 'Reviews Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Customer Reviews</h1>
                <p class="text-muted mb-0">Manage and moderate customer product reviews</p>
            </div>
            <div>
                <a href="{{ route('admin.reviews.export') }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Export
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="rating" class="form-label">Rating</label>
                <select name="rating" id="rating" class="form-select">
                    <option value="">All Ratings</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="product_id" class="form-label">Product</label>
                <select name="product_id" id="product_id" class="form-select">
                    <option value="">All Products</option>
                    @foreach(\App\Models\Product::orderBy('name')->get() as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="bulkActionForm" action="{{ route('admin.reviews.bulk-action') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" id="selectAll" class="form-check-input me-2">
                            <label for="selectAll" class="form-check-label mb-0">Select All</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="action" class="form-select" required>
                            <option value="">Choose Action</option>
                            <option value="approve">Approve Selected</option>
                            <option value="reject">Reject Selected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary" id="bulkActionBtn" disabled>
                            <i class="bi bi-check-circle"></i> Apply Action
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="content-section">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">All Reviews</h6>
            </div>
            <div class="card-body">
                @if($reviews->count() > 0)
                    <div class="table-responsive">
                        <table id="reviewsTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAllHeader" class="form-check-input">
                                    </th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="reviews[]" value="{{ $review->id }}"
                                                   class="form-check-input review-checkbox">
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $review->user->name ?? 'Guest' }}</strong><br>
                                                <small class="text-muted">{{ $review->user->email ?? 'No email' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.show', $review->product) }}"
                                               class="text-decoration-none">
                                                {{ $review->product->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}
                                                              text-warning"></i>
                                                @endfor
                                                <span class="ms-2 text-muted">({{ $review->rating }})</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;"
                                                 title="{{ $review->comment }}">
                                                {{ Str::limit($review->comment, 80) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $review->status_badge_class }}">
                                                {{ ucfirst($review->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('admin.reviews.show', $review) }}"
                                                   class="btn btn-action btn-info btn-action-sm" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.reviews.edit', $review) }}"
                                                   class="btn btn-action btn-warning btn-action-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if($review->status === 'pending')
                                                    <button type="button" class="btn btn-action btn-success btn-action-sm"
                                                            title="Approve" onclick="approveReview({{ $review->id }})">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-action btn-danger btn-action-sm"
                                                            title="Reject" onclick="rejectReview({{ $review->id }})">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-action btn-danger btn-action-sm"
                                                        title="Delete" onclick="deleteReview({{ $review->id }})">
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
                        <i class="bi bi-star"></i>
                        <h5>No Reviews Found</h5>
                        <p>There are no customer reviews matching your criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Review Modal -->
<div class="modal fade" id="rejectReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectReviewForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Are you sure you want to reject this review?</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection *</label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control"
                                  rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Review Modal -->
<div class="modal fade" id="deleteReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this review? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteReviewForm" method="POST" style="display: inline;">
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
    $('#reviewsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 25,
        order: [[6, 'desc']], // Sort by date by default
        columnDefs: [
            { orderable: false, targets: [0, 7] } // Disable sorting for checkbox and actions columns
        ],
        language: {
            search: "Search reviews:",
            lengthMenu: "Show _MENU_ reviews per page",
            info: "Showing _START_ to _END_ of _TOTAL_ reviews",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActionButton();
});

document.getElementById('selectAllHeader').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    document.getElementById('selectAll').checked = this.checked;
    updateBulkActionButton();
});

// Individual checkbox change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('review-checkbox')) {
        updateBulkActionButton();
        updateSelectAllCheckboxes();
    }
});

function updateBulkActionButton() {
    const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    bulkActionBtn.disabled = checkedBoxes.length === 0;
}

function updateSelectAllCheckboxes() {
    const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
    const totalBoxes = document.querySelectorAll('.review-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllHeaderCheckbox = document.getElementById('selectAllHeader');

    selectAllCheckbox.checked = checkedBoxes.length === totalBoxes.length;
    selectAllHeaderCheckbox.checked = checkedBoxes.length === totalBoxes.length;
}

function approveReview(reviewId) {
    if (confirm('Are you sure you want to approve this review?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/reviews/${reviewId}/approve`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PATCH';

        form.appendChild(csrfToken);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectReview(reviewId) {
    const modal = new bootstrap.Modal(document.getElementById('rejectReviewModal'));
    const form = document.getElementById('rejectReviewForm');
    form.action = `/admin/reviews/${reviewId}/reject`;
    modal.show();
}

function deleteReview(reviewId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteReviewModal'));
    const form = document.getElementById('deleteReviewForm');
    form.action = `/admin/reviews/${reviewId}`;
    modal.show();
}
</script>
@endpush
@endsection

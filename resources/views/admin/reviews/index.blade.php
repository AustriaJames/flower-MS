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
              
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3">
            <div class="col-md-4">
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
            <div class="col-md-4">
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
            <div class="col-md-4">
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
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                    <tr>
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
        order: [[4, 'desc']], // Sort by date by default
        columnDefs: [
            { orderable: false, targets: [5] } // Disable sorting for actions column
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

function deleteReview(reviewId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteReviewModal'));
    const form = document.getElementById('deleteReviewForm');
    form.action = `/admin/reviews/${reviewId}`;
    modal.show();
}
</script>
@endpush
@endsection
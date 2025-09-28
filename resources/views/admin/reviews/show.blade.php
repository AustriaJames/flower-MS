@extends('layouts.admin')

@section('page-title', 'Review Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Review Details</h1>
                <p class="text-muted mb-0">View and manage customer review</p>
            </div>
            <div>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Reviews
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Review Details -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Review Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Customer Information</h6>
                            <p><strong>Name:</strong> {{ $review->user->name ?? 'Guest' }}</p>
                            <p><strong>Email:</strong> {{ $review->user->email ?? 'No email' }}</p>
                            <p><strong>Review Date:</strong> {{ $review->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Product Information</h6>
                            <p><strong>Product:</strong>
                                <a href="{{ route('admin.products.show', $review->product) }}" class="text-decoration-none">
                                    {{ $review->product->name }}
                                </a>
                            </p>
                            <p><strong>Rating:</strong>
                                <div class="d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                    <span class="ms-2">({{ $review->rating }}/5)</span>
                                </div>
                            </p>
                            <p><strong>Status:</strong>
                                <span class="badge {{ $review->status_badge_class }}">
                                    {{ ucfirst($review->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted">Review Comment</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                        </div>
                    </div>

                    @if($review->admin_response)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-muted">Admin Response</h6>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <p class="mb-0">{{ $review->admin_response }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($review->admin_notes)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-muted">Admin Notes (Internal)</h6>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <p class="mb-0">{{ $review->admin_notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($review->status === 'pending')
                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle"></i> Approve Review
                                </button>
                            </form>

                            <button type="button" class="btn btn-danger w-100" onclick="rejectReview({{ $review->id }})">
                                <i class="bi bi-x-circle"></i> Reject Review
                            </button>
                        @endif

                        <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Review
                        </a>

                        <button type="button" class="btn btn-danger" onclick="deleteReview({{ $review->id }})">
                            <i class="bi bi-trash"></i> Delete Review
                        </button>
                    </div>
                </div>
            </div>

            <!-- Review Statistics -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Review Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $review->product->reviews->count() }}</h4>
                            <small class="text-muted">Total Reviews</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ number_format($review->product->reviews->avg('rating'), 1) }}</h4>
                            <small class="text-muted">Avg Rating</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Review Modal -->
<div class="modal fade" id="rejectReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectReviewForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Reject Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Reason for Rejection (Optional)</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"
                                  placeholder="Provide a reason for rejecting this review..."></textarea>
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

<!-- Delete Confirmation Modal -->
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
<script>
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

@extends('layouts.admin')

@section('page-title', 'Edit Review')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Edit Review</h1>
                <p class="text-muted mb-0">Modify review details and status</p>
            </div>
            <div>
                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Review
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Review Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Review Status</label>
                                    <select name="status" id="status" class="form-select" required>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ $review->status === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer</label>
                                    <input type="text" class="form-control" value="{{ $review->user->name ?? 'Guest' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Product</label>
                                    <input type="text" class="form-control" value="{{ $review->product->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning fs-5"></i>
                                        @endfor
                                        <span class="ms-2">({{ $review->rating }}/5)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Review Comment</label>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                            <small class="text-muted">Original comment cannot be edited</small>
                        </div>

                        <div class="mb-3">
                            <label for="admin_response" class="form-label">Admin Response (Public)</label>
                            <textarea name="admin_response" id="admin_response" class="form-control" rows="3"
                                      placeholder="Add a public response to this review...">{{ $review->admin_response }}</textarea>
                            <small class="text-muted">This response will be visible to the customer</small>
                            @error('admin_response')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes (Internal)</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3"
                                      placeholder="Add internal notes about this review...">{{ $review->admin_notes }}</textarea>
                            <small class="text-muted">These notes are only visible to admins</small>
                            @error('admin_notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Review
                            </button>
                            <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
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

                        <button type="button" class="btn btn-danger" onclick="deleteReview({{ $review->id }})">
                            <i class="bi bi-trash"></i> Delete Review
                        </button>
                    </div>
                </div>
            </div>

            <!-- Review History -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Review History</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Review Created</h6>
                                <p class="text-muted mb-0">{{ $review->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @if($review->reviewed_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Status Updated</h6>
                                    <p class="text-muted mb-0">{{ $review->reviewed_at->format('M d, Y h:i A') }}</p>
                                    <small class="text-muted">by {{ $review->reviewedBy->name ?? 'Admin' }}</small>
                                </div>
                            </div>
                        @endif
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

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    padding-left: 10px;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -29px;
    top: 17px;
    width: 2px;
    height: calc(100% + 3px);
    background-color: #e9ecef;
}
</style>
@endpush

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

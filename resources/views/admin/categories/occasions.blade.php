@extends('layouts.admin')

@section('page-title', 'Occasion Categories')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Occasion Categories</h1>
                <p class="text-muted mb-0">Manage special occasion categories</p>
            </div>
            <div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Categories
                </a>
                <a href="{{ route('admin.categories.create') }}?type=occasion" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Occasion Category
                </a>
            </div>
        </div>
    </div>

    <!-- Occasion Categories Grid -->
    <div class="content-section">
        @if($occasionCategories->count() > 0)
            <div class="row">
                @foreach($occasionCategories as $category)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card h-100 {{ $category->is_active ? 'border-success' : 'border-secondary' }} occasion-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $category->slug }}</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               onchange="toggleStatus({{ $category->id }})"
                                               {{ $category->is_active ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>

                            @if($category->image)
                                <div class="card-img-top" style="height: 200px; overflow: hidden;">
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="img-fluid w-100 h-100" style="object-fit: cover;">
                                </div>
                            @endif

                            <div class="card-body">
                                @if($category->icon)
                                    <div class="text-center mb-3">
                                        <i class="{{ $category->icon }} fs-1 text-primary"></i>
                                    </div>
                                @endif

                                <p class="text-muted">{{ $category->description }}</p>

                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <strong class="text-primary">{{ $category->products_count }}</strong><br>
                                        <small class="text-muted">Products</small>
                                    </div>
                                    <div class="col-6">
                                        <strong class="text-info">{{ $category->sub_categories_count ?? 0 }}</strong><br>
                                        <small class="text-muted">Subcategories</small>
                                    </div>
                                </div>

                                @if($category->occasion_date)
                                    <div class="text-center mb-3">
                                        <span class="badge bg-info fs-6">
                                            <i class="bi bi-calendar-event"></i>
                                            {{ $category->occasion_date->format('F d, Y') }}
                                        </span>
                                    </div>
                                @endif

                                <div class="occasion-stats">
                                    <div class="d-flex justify-content-between align-items-center text-sm">
                                        <span class="text-muted">Created:</span>
                                        <span>{{ $category->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center text-sm">
                                        <span class="text-muted">Last Updated:</span>
                                        <span>{{ $category->updated_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.categories.show', $category) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button type="button" class="dropdown-item"
                                                        onclick="toggleStatus({{ $category->id }})">
                                                    <i class="bi bi-toggle-{{ $category->is_active ? 'off' : 'on' }}"></i>
                                                    {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger"
                                                        onclick="deleteCategory({{ $category->id }})">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination if needed -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <p class="text-muted">{{ $occasionCategories->count() }} occasion categories found</p>
                </nav>
            </div>
        @else
            <div class="empty-state">
                <div class="text-center py-5">
                    <div class="empty-state-icon">
                        <i class="bi bi-calendar-event text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="mt-3">No Occasion Categories</h4>
                    <p class="text-muted mb-4">You haven't created any occasion categories yet. Start by adding your first special occasion category.</p>
                    <a href="{{ route('admin.categories.create') }}?type=occasion" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Create First Occasion Category
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.occasion-card {
    /* Card animations removed */
}

.empty-state {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 3rem;
    margin: 2rem 0;
}

.empty-state-icon {
    opacity: 0.7;
}

.occasion-stats {
    font-size: 0.875rem;
    border-top: 1px solid #e9ecef;
    padding-top: 1rem;
}

.text-sm {
    font-size: 0.875rem;
}

.fs-6 {
    font-size: 0.875rem!important;
}
</style>
@endpush

@push('scripts')
<script>
function toggleStatus(categoryId) {
    fetch(`/admin/categories/${categoryId}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Category status updated successfully!');
            // Reload the page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showError('Failed to update category status.');
        }
    })
    .catch(error => {
        showError('An error occurred while updating category status.');
    });
}

function deleteCategory(categoryId) {
    confirmDelete('Delete Occasion Category?', 'Are you sure you want to delete this occasion category? This action cannot be undone.')
    .then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/categories/${categoryId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection



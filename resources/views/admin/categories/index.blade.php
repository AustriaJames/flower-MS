@extends('layouts.admin')

@section('page-title', 'Categories Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Categories Management</h1>
                <p class="text-muted mb-0">Manage product categories and occasion specials</p>
            </div>
            <div>
                <a href="{{ route('admin.categories.occasions') }}" class="btn btn-info">
                    <i class="bi bi-calendar-event"></i> Occasion Categories
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Category
                </a>
            </div>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="categoryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="regular-tab" data-bs-toggle="tab" data-bs-target="#regular"
                            type="button" role="tab">Regular Categories</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="occasions-tab" data-bs-toggle="tab" data-bs-target="#occasions"
                            type="button" role="tab">Occasion Categories</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="categoryTabsContent">
                <!-- Regular Categories Tab -->
                <div class="tab-pane fade show active" id="regular" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Product Categories</h6>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="enableReordering()">
                            <i class="bi bi-arrows-move"></i> Reorder
                        </button>
                    </div>

                    @if($regularCategories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="regularCategoriesTable">
                                <thead>
                                    <tr>
                                        <th width="50">Order</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="sortable" data-type="regular">
                                    @foreach($regularCategories as $category)
                                        <tr data-id="{{ $category->id }}">
                                            <td>
                                                <div class="sort-handle">
                                                    <i class="bi bi-grip-vertical text-muted"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($category->image)
                                                        <img src="{{ asset('storage/' . $category->image) }}"
                                                             alt="{{ $category->name }}"
                                                             class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $category->name }}</strong><br>
                                                        <small class="text-muted">{{ $category->slug }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ Str::limit($category->description, 80) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $category->products_count }} products</span>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           onchange="toggleStatus({{ $category->id }})"
                                                           {{ $category->is_active ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="{{ route('admin.categories.show', $category) }}"
                                                       class="btn btn-action btn-info btn-action-sm" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                                       class="btn btn-action btn-warning btn-action-sm" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-action btn-danger btn-action-sm"
                                                            title="Delete" onclick="deleteCategory({{ $category->id }})">
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
                            <i class="bi bi-folder"></i>
                            <h5>No Regular Categories</h5>
                            <p>Create your first product category to get started.</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Category
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Occasion Categories Tab -->
                <div class="tab-pane fade" id="occasions" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Occasion Special Categories</h6>
                        <a href="{{ route('admin.categories.create') }}?type=occasion" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Occasion
                        </a>
                    </div>

                    @if($occasionCategories->count() > 0)
                        <div class="row">
                            @foreach($occasionCategories as $category)
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <div class="card h-100 {{ $category->is_active ? 'border-success' : 'border-secondary' }}">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $category->name }}</h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       onchange="toggleStatus({{ $category->id }})"
                                                       {{ $category->is_active ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if($category->image)
                                                <div class="text-center mb-3">
                                                    <img src="{{ asset('storage/' . $category->image) }}"
                                                         alt="{{ $category->name }}"
                                                         class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                                                </div>
                                            @endif

                                            <p class="text-muted">{{ $category->description }}</p>

                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <strong>{{ $category->products_count }}</strong><br>
                                                    <small class="text-muted">Products</small>
                                                </div>
                                                <div class="col-6">
                                                    <strong>{{ $category->subCategories->count() }}</strong><br>
                                                    <small class="text-muted">Subcategories</small>
                                                </div>
                                            </div>

                                            @if($category->occasion_date)
                                                <div class="text-center mt-3">
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-calendar"></i>
                                                        {{ $category->occasion_date->format('M d, Y') }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer">
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('admin.categories.show', $category) }}"
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                                       class="btn btn-outline-warning btn-sm">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="deleteCategory({{ $category->id }})">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-calendar-event"></i>
                            <h5>No Occasion Categories</h5>
                            <p>Create special occasion categories for seasonal promotions.</p>
                            <a href="{{ route('admin.categories.create') }}?type=occasion" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Occasion Category
                            </a>
                        </div>
                    @endif
                </div>
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Initialize DataTables
$(document).ready(function() {
    $('#regularCategoriesTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0, 5] } // Disable sorting for order and actions columns
        ]
    });
});

let isReorderingEnabled = false;

function enableReordering() {
    if (!isReorderingEnabled) {
        const regularTable = document.getElementById('regularCategoriesTable');
        const tbody = regularTable.querySelector('tbody');

        new Sortable(tbody, {
            handle: '.sort-handle',
            animation: 150,
            onEnd: function(evt) {
                const items = Array.from(tbody.querySelectorAll('tr')).map((tr, index) => ({
                    id: tr.dataset.id,
                    order: index + 1
                }));

                // Send reorder request
                fetch('{{ route("admin.categories.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ categories: items })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showSuccess('Categories reordered successfully!');
                    }
                });
            }
        });

        isReorderingEnabled = true;
        document.querySelector('[onclick="enableReordering()"]').textContent = 'Reorder Complete';
        document.querySelector('[onclick="enableReordering()"]').disabled = true;
    }
}

function toggleStatus(categoryId) {
    fetch(`/admin/categories/${categoryId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const status = data.status;
            showSuccess(`Category ${status} successfully.`);
        }
    });
}

function deleteCategory(categoryId) {
    confirmDelete('Delete Category?', 'Are you sure you want to delete this category? This action cannot be undone.')
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

// Initialize Bootstrap tabs
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tabs
    const triggerTabList = document.querySelectorAll('#categoryTabs button[data-bs-toggle="tab"]');
    triggerTabList.forEach(triggerEl => {
        const tabTrigger = new bootstrap.Tab(triggerEl);

        triggerEl.addEventListener('click', event => {
            event.preventDefault();
            tabTrigger.show();
        });
    });
});
</script>
@endpush
@endsection

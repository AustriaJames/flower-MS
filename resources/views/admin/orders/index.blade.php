@extends('layouts.admin')

@section('page-title', 'Orders Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Orders Management</h1>
            <div>
                <a href="{{ route('admin.orders.export') }}" class="btn-action btn-success btn-action-lg">
                    <i class="bi bi-download"></i>
                    <span>Export Orders</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card shadow mb-3" id="bulkActionsCard" style="display: none;">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" id="selectedCount">0 orders selected</span>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><button type="button" class="dropdown-item" onclick="bulkUpdateStatus('confirmed')">Mark as Confirmed</button></li>
                        <li><button type="button" class="dropdown-item" onclick="bulkUpdateStatus('processing')">Mark as Processing</button></li>
                        <li><button type="button" class="dropdown-item" onclick="bulkUpdateStatus('shipped')">Mark as Shipped</button></li>
                        <li><button type="button" class="dropdown-item" onclick="bulkUpdateStatus('delivered')">Mark as Delivered</button></li>
                        <li><button type="button" class="dropdown-item" onclick="bulkUpdateStatus('cancelled')">Mark as Cancelled</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button type="button" class="dropdown-item text-danger" onclick="bulkDelete()">Delete Selected</button></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">Clear Selection</button>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orders List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="ordersTable" class="table table-bordered table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Items</th>
                            <th>Order Date</th>
                            <th>Estimated Delivery</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input order-checkbox" value="{{ $order->id }}">
                            </td>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $order->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </div>
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
                                @if($order->discount_amount > 0)
                                    <br>
                                    <small class="text-muted">Discount: ₱{{ number_format($order->discount_amount, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $order->orderItems->count() }} items</span>
                                <br>
                                <small class="text-muted">
                                    @foreach($order->orderItems->take(2) as $item)
                                        {{ $item->product->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if($order->orderItems->count() > 2)
                                        +{{ $order->orderItems->count() - 2 }} more
                                    @endif
                                </small>
                            </td>
                            <td>
                                {{ $order->order_date->format('M d, Y') }}
                                <br>
                                <small class="text-muted">{{ $order->order_date->format('h:i A') }}</small>
                            </td>
                            <td>
                                @if($order->estimated_delivery)
                                    {{ $order->estimated_delivery->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn-action btn-info" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order) }}"
                                       class="btn-action btn-warning" title="Edit Order">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-primary dropdown-toggle"
                                            data-bs-toggle="dropdown" title="Quick Actions">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                            @if($status !== $order->status)
                                                <li>
                                                    <button type="button" class="dropdown-item"
                                                            onclick="updateOrderStatus({{ $order->id }}, '{{ $status }}')">
                                                        Mark as {{ ucfirst($status) }}
                                                    </button>
                                                </li>
                                            @endif
                                        @endforeach
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger"
                                                    onclick="deleteOrder({{ $order->id }})">
                                                <i class="bi bi-trash"></i> Delete Order
                                            </button>
                                        </li>
                                    </ul>
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
    $('#ordersTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 25,
        order: [[6, 'desc']], // Sort by order date by default
        columnDefs: [
            { orderable: false, targets: [0, 8] } // Disable sorting for checkbox and actions columns
        ],
        language: {
            search: "Search orders:",
            lengthMenu: "Show _MENU_ orders per page",
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});

function updateOrderStatus(orderId, status) {
    const statusLabels = {
        'pending': 'Pending',
        'confirmed': 'Confirmed',
        'processing': 'Processing',
        'shipped': 'Shipped',
        'delivered': 'Delivered',
        'cancelled': 'Cancelled'
    };

    confirmAction(
        'Update Order Status?',
        `Are you sure you want to mark this order as "${statusLabels[status]}"?`
    ).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}/status`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PATCH';

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;

            form.appendChild(csrfToken);
            form.appendChild(method);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function deleteOrder(orderId) {
    confirmDelete('Delete Order?', 'Are you sure you want to delete this order? This action cannot be undone.')
    .then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}`;

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

// Bulk selection functionality
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.order-checkbox:checked');
    const bulkActionsCard = document.getElementById('bulkActionsCard');
    const selectedCount = document.getElementById('selectedCount');

    if (checkboxes.length > 0) {
        bulkActionsCard.style.display = 'block';
        selectedCount.textContent = `${checkboxes.length} order${checkboxes.length > 1 ? 's' : ''} selected`;
    } else {
        bulkActionsCard.style.display = 'none';
    }
}

function clearSelection() {
    document.querySelectorAll('.order-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

function bulkUpdateStatus(status) {
    const selectedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);

    if (selectedOrders.length === 0) {
        showWarning('Please select at least one order.');
        return;
    }

    const statusLabels = {
        'pending': 'Pending',
        'confirmed': 'Confirmed',
        'processing': 'Processing',
        'shipped': 'Shipped',
        'delivered': 'Delivered',
        'cancelled': 'Cancelled'
    };

    confirmAction(
        'Bulk Update Status?',
        `Are you sure you want to mark ${selectedOrders.length} order${selectedOrders.length > 1 ? 's' : ''} as "${statusLabels[status]}"?`
    ).then((result) => {
        if (result.isConfirmed) {
            // For now, we'll update them one by one
            // In a real application, you might want to create a bulk update endpoint
            selectedOrders.forEach(orderId => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/orders/${orderId}/status`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PATCH';

                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;

                form.appendChild(csrfToken);
                form.appendChild(method);
                form.appendChild(statusInput);
                document.body.appendChild(form);
                form.submit();
            });
        }
    });
}

function bulkDelete() {
    const selectedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);

    if (selectedOrders.length === 0) {
        showWarning('Please select at least one order.');
        return;
    }

    confirmDelete(
        'Bulk Delete Orders?',
        `Are you sure you want to delete ${selectedOrders.length} order${selectedOrders.length > 1 ? 's' : ''}? This action cannot be undone.`
    ).then((result) => {
        if (result.isConfirmed) {
            // For now, we'll delete them one by one
            // In a real application, you might want to create a bulk delete endpoint
            selectedOrders.forEach(orderId => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/orders/${orderId}`;

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
            });
        }
    });
}

// Event listeners for checkboxes
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox functionality
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('order-checkbox')) {
            const allCheckboxes = document.querySelectorAll('.order-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
            const selectAllCheckbox = document.getElementById('selectAll');

            selectAllCheckbox.checked = checkedCheckboxes.length === allCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;

            updateBulkActions();
        }
    });
});
</script>
@endpush
@endsection

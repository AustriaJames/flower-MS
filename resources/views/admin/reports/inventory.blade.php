@extends('layouts.admin')

@section('page-title', 'Inventory Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Inventory Reports</h1>
                <p class="text-muted mb-0">Track stock levels, value, and product performance</p>
            </div>
            <div>
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-primary">
                    <i class="bi bi-graph-up"></i> Sales Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Inventory Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-box"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $lowStockProducts->count() }}</h4>
                        <p class="text-muted mb-0">Low Stock Items</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $outOfStockProducts->count() }}</h4>
                        <p class="text-muted mb-0">Out of Stock</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">₱{{ number_format($stockByCategory->sum('total_value'), 2) }}</h4>
                        <p class="text-muted mb-0">Total Stock Value</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $highTurnoverProducts->count() }}</h4>
                        <p class="text-muted mb-0">High Turnover Items</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Low Stock Products -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Low Stock Products (≤10 units)</h6>
                </div>
                <div class="card-body">
                    @if($lowStockProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProducts->take(10) as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->main_image)
                                                        <img src="{{ asset('storage/' . $product->main_image) }}"
                                                             alt="{{ $product->name }}" class="rounded me-2"
                                                             style="width: 30px; height: 30px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $product->name }}</strong><br>
                                                        <small class="text-muted">{{ $product->sku }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-warning">{{ $product->stock_quantity }}</span>
                                            </td>
                                            <td>₱{{ number_format($product->price, 2) }}</td>
                                            <td>₱{{ number_format($product->stock_quantity * $product->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($lowStockProducts->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">Showing 10 of {{ $lowStockProducts->count() }} items</small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle text-success fs-1"></i>
                            <h5 class="mt-3">No Low Stock Items</h5>
                            <p class="text-muted">All products have sufficient stock levels.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Out of Stock Products -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Out of Stock Products</h6>
                </div>
                <div class="card-body">
                    @if($outOfStockProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($outOfStockProducts->take(10) as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->main_image)
                                                        <img src="{{ asset('storage/' . $product->main_image) }}"
                                                             alt="{{ $product->name }}" class="rounded me-2"
                                                             style="width: 30px; height: 30px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $product->name }}</strong><br>
                                                        <small class="text-muted">{{ $product->sku }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                            <td>₱{{ number_format($product->price, 2) }}</td>
                                            <td>
                                                <span class="badge bg-danger">Out of Stock</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                   class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Restock
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($outOfStockProducts->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">Showing 10 of {{ $outOfStockProducts->count() }} items</small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle text-success fs-1"></i>
                            <h5 class="mt-3">No Out of Stock Items</h5>
                            <p class="text-muted">All products are in stock.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Stock Value by Category -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Value by Category</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Products</th>
                                    <th>Total Stock</th>
                                    <th>Total Value</th>
                                    <th>Avg Value/Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockByCategory->sortByDesc('total_value') as $category)
                                    <tr>
                                        <td>
                                            <strong>{{ $category['category'] }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $category['total_products'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $category['total_stock'] }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-success">₱{{ number_format($category['total_value'], 2) }}</strong>
                                        </td>
                                        <td>
                                            ₱{{ $category['total_products'] > 0 ? number_format($category['total_value'] / $category['total_products'], 2) : '0.00' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- High Turnover Products -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">High Turnover Products</h6>
                </div>
                <div class="card-body">
                    @if($highTurnoverProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($highTurnoverProducts->take(8) as $product)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $product->name }}</strong><br>
                                        <small class="text-muted">{{ $product->category->name ?? 'N/A' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">{{ $product->order_items_count }}</span><br>
                                        <small class="text-muted">orders</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-info fs-1"></i>
                            <h5 class="mt-3">No Data Available</h5>
                            <p class="text-muted">No products have been ordered yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Alerts -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Alerts & Recommendations</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-warning">⚠️ Immediate Actions Needed:</h6>
                            <ul class="list-unstyled">
                                @if($outOfStockProducts->count() > 0)
                                    <li class="mb-2">
                                        <i class="bi bi-exclamation-triangle text-danger"></i>
                                        <strong>{{ $outOfStockProducts->count() }} products</strong> are out of stock
                                    </li>
                                @endif
                                @if($lowStockProducts->count() > 0)
                                    <li class="mb-2">
                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                        <strong>{{ $lowStockProducts->count() }} products</strong> have low stock levels
                                    </li>
                                @endif
                                @if($outOfStockProducts->count() === 0 && $lowStockProducts->count() === 0)
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        All products have adequate stock levels
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">📊 Inventory Insights:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-graph-up text-primary"></i>
                                    Total inventory value: <strong>₱{{ number_format($stockByCategory->sum('total_value'), 2) }}</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-box text-secondary"></i>
                                    Total products: <strong>{{ $stockByCategory->sum('total_products') }}</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-currency-dollar text-success"></i>
                                    Average value per product: <strong>₱{{ $stockByCategory->sum('total_products') > 0 ? number_format($stockByCategory->sum('total_value') / $stockByCategory->sum('total_products'), 2) : '0.00' }}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Add any interactive features here
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh data every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);
});
</script>
@endpush
@endsection

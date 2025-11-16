@extends('layouts.admin')

@section('page-title', 'Product Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Product Reports</h1>
                <p class="text-muted mb-0">Analyze product performance, sales, and profitability</p>
            </div>
            <div>
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-primary">
                    <i class="bi bi-graph-up"></i> Sales Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Product Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-box"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($productStats['total_products']) }}</h4>
                        <p class="text-muted mb-0">Total Products</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($productStats['total_sales']) }}</h4>
                        <p class="text-muted mb-0">Total Sales</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">₱{{ number_format($productStats['total_revenue'], 2) }}</h4>
                        <p class="text-muted mb-0">Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-star"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($productStats['avg_rating'], 1) }}</h4>
                        <p class="text-muted mb-0">Avg Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Selling Products -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                </div>
                <div class="card-body">
                    @if($topSellingProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Units Sold</th>
                                        <th>Revenue</th>
                                        <th>Rating</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topSellingProducts as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->main_image)
                                                        <img src="{{ $product->main_image_url }}"
                                                             alt="{{ $product->name }}" class="rounded me-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $product->name }}</strong><br>
                                                        <small class="text-muted">{{ $product->sku }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $product->units_sold }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-success">₱{{ number_format($product->revenue, 2) }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $product->avg_rating ? '-fill' : '' }} text-warning"></i>
                                                    @endfor
                                                    <span class="ms-2">({{ number_format($product->avg_rating, 1) }})</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($product->stock_quantity <= 10)
                                                    <span class="badge bg-warning">{{ $product->stock_quantity }}</span>
                                                @elseif($product->stock_quantity == 0)
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @else
                                                    <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-info fs-1"></i>
                            <h5 class="mt-3">No Sales Data</h5>
                            <p class="text-muted">No products have been sold yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Categories Performance -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Category Performance</h6>
                </div>
                <div class="card-body">
                    @if($categoryPerformance->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($categoryPerformance->take(8) as $category)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $category['name'] }}</strong><br>
                                        <small class="text-muted">{{ $category['products_count'] }} products</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success">₱{{ number_format($category['revenue'], 2) }}</span><br>
                                        <small class="text-muted">{{ $category['sales_count'] }} sales</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-info fs-1"></i>
                            <h5 class="mt-3">No Category Data</h5>
                            <p class="text-muted">No category performance data available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Performance Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Product Performance</h6>
                </div>
                <div class="card-body">
                    <canvas id="productPerformanceChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Stock Status -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Status Overview</h6>
                </div>
                <div class="card-body">
                    <canvas id="stockStatusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Insights -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Product Insights & Recommendations</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Performance Metrics:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-graph-up text-success"></i>
                                    <strong>Best Seller:</strong>
                                    @if($topSellingProducts->count() > 0)
                                        {{ $topSellingProducts->first()->name }} ({{ $topSellingProducts->first()->units_sold }} units)
                                    @else
                                        No data available
                                    @endif
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-currency-dollar text-info"></i>
                                    <strong>Highest Revenue:</strong>
                                    @if($topSellingProducts->count() > 0)
                                        {{ $topSellingProducts->sortByDesc('revenue')->first()->name }} (₱{{ number_format($topSellingProducts->sortByDesc('revenue')->first()->revenue, 2) }})
                                    @else
                                        No data available
                                    @endif
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-star text-warning"></i>
                                    <strong>Top Rated:</strong>
                                    @if($topRatedProducts->count() > 0)
                                        {{ $topRatedProducts->first()->name }} ({{ number_format($topRatedProducts->first()->avg_rating, 1) }} stars)
                                    @else
                                        No data available
                                    @endif
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-box text-secondary"></i>
                                    <strong>Stock Status:</strong> {{ $productStats['low_stock_count'] }} items need restocking
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">💡 Recommendations:</h6>
                            <ul class="list-unstyled">
                                @if($productStats['low_stock_count'] > 0)
                                    <li class="mb-2">
                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                        <strong>Restock Alert:</strong> {{ $productStats['low_stock_count'] }} products need immediate restocking
                                    </li>
                                @endif
                                @if($productStats['avg_rating'] < 4.0)
                                    <li class="mb-2">
                                        <i class="bi bi-star text-warning"></i>
                                        <strong>Quality Improvement:</strong> Focus on improving product quality and customer satisfaction
                                    </li>
                                @endif
                                @if($productStats['total_revenue'] < 10000)
                                    <li class="mb-2">
                                        <i class="bi bi-currency-dollar text-success"></i>
                                        <strong>Revenue Growth:</strong> Implement marketing strategies to increase sales
                                    </li>
                                @endif
                                <li class="mb-2">
                                    <i class="bi bi-graph-up text-primary"></i>
                                    <strong>Product Optimization:</strong> Analyze underperforming products and consider promotions
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Product Performance Chart
    const ctx1 = document.getElementById('productPerformanceChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5'],
            datasets: [{
                label: 'Units Sold',
                data: [65, 59, 80, 81, 56],
                backgroundColor: 'rgba(0, 123, 255, 0.8)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Stock Status Chart
    const ctx2 = document.getElementById('stockStatusChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [70, 20, 10],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
@endsection

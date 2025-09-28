@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: var(--primary-color); border-radius: 15px;">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2 fw-bold">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                            <p class="mb-0 opacity-75">Here's what's happening with your flower shop today.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-none d-md-block">
                                <i class="bi bi-seedling fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Sales Stats -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon" style="background: var(--info-color);">
                        <i class="bi bi-cart"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Orders</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ number_format($stats['total_orders']) }}</div>
                        <div class="text-info small">
                            <i class="bi bi-clock"></i> {{ $stats['pending_orders'] }} pending
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon" style="background: var(--warning-color);">
                        <i class="bi bi-coin"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Revenue</div>
                        <div class="h3 mb-0 fw-bold text-dark">₱{{ number_format($stats['total_revenue'], 2) }}</div>
                        <div class="text-warning small">
                            <i class="bi bi-currency-peso"></i> Earned
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon" style="background: var(--success-color);">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Bookings</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ number_format($stats['total_bookings']) }}</div>
                        <div class="text-success small">
                            <i class="bi bi-clock"></i> {{ $stats['pending_bookings'] }} pending
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon" style="background: var(--primary-color);">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Users</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ number_format($stats['total_users']) }}</div>
                        <div class="text-primary small">
                            <i class="bi bi-arrow-up"></i> Active customers
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon" style="background: var(--success-color);">
                        <i class="bi bi-box"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Products</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ number_format($stats['total_products']) }}</div>
                        <div class="text-success small">
                            <i class="bi bi-seedling"></i> In catalog
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon" style="background: var(--warning-color);">
                        <i class="bi bi-star"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Reviews</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ number_format($stats['total_reviews']) }}</div>
                        <div class="text-warning small">
                            <i class="bi bi-clock"></i> {{ $stats['pending_reviews'] }} pending
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon" style="background: var(--info-color);">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Open Chats</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ number_format($stats['open_chats']) }}</div>
                        <div class="text-info small">
                            <i class="bi bi-exclamation-circle"></i> Need attention
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon" style="background: var(--secondary-color);">
                        <i class="bi bi-tags"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Categories</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ number_format($stats['total_categories']) }}</div>
                        <div class="text-secondary small">
                            <i class="bi bi-tag"></i> Including occasions
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

         <!-- Charts Row -->
     <div class="row mb-4">
         <!-- Monthly Revenue Chart -->
         <div class="col-xl-8 col-lg-7">
             <div class="card shadow mb-4">
                 <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                     <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue</h6>
                 </div>
                 <div class="card-body">
                     <div style="position: relative; height: 300px;">
                         <canvas id="revenueChart"></canvas>
                     </div>
                 </div>
             </div>
         </div>

                 <!-- Order Status Distribution -->
         <div class="col-xl-4 col-lg-5">
             <div class="card shadow mb-4">
                 <div class="card-header py-3">
                     <h6 class="m-0 font-weight-bold text-primary">Order Status Distribution</h6>
                 </div>
                 <div class="card-body">
                     <div style="position: relative; height: 300px;">
                         <canvas id="orderStatusChart"></canvas>
                     </div>
                 </div>
             </div>
         </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                            <td>{{ $order->order_date->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">View All Orders</a>
                        </div>
                    @else
                        <p class="text-muted text-center">No recent orders found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                </div>
                <div class="card-body">
                    @if($topProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Sales</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $product)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none">
                                                {{ $product->name }}
                                            </a>
                                        </td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>{{ $product->order_items_count }}</td>
                                        <td>₱{{ number_format($product->current_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">View All Products</a>
                        </div>
                    @else
                        <p class="text-muted text-center">No products found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Management -->
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.products.create') }}" class="quick-action-btn">
                                <i class="bi bi-plus me-2"></i>Add Product
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.categories.create') }}" class="quick-action-btn">
                                <i class="bi bi-tag me-2"></i>Add Category
                            </a>
                        </div>

                        <!-- Customer Management -->
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.users.create') }}" class="quick-action-btn">
                                <i class="bi bi-person-plus me-2"></i>Add Customer
                            </a>
                        </div>

                        <!-- Support & Reviews -->
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.reviews.index') }}" class="quick-action-btn">
                                <i class="bi bi-star me-2"></i>Review Reviews
                            </a>
                        </div>

                        <!-- Reports -->
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.reports.sales') }}" class="quick-action-btn">
                                <i class="bi bi-graph-up me-2"></i>View Reports
                            </a>
                        </div>

                        <!-- Chat Support -->
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.chats.index') }}" class="quick-action-btn">
                                <i class="bi bi-chat-dots me-2"></i>Chat Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Monthly Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
if (revenueCtx) {
    const revenueChart = new Chart(revenueCtx.getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')->map(function($month) { return date('F', mktime(0, 0, 0, $month, 1)); })) !!},
            datasets: [{
                label: 'Revenue (₱)',
                data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: '600'
                        },
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        },
                        font: {
                            size: 12
                        },
                        padding: 10
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        padding: 10
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            elements: {
                point: {
                    hoverBackgroundColor: '#2563eb'
                }
            }
        }
    });
}

// Order Status Chart
const statusCtx = document.getElementById('orderStatusChart');
if (statusCtx) {
    const statusChart = new Chart(statusCtx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($orderStatuses->pluck('status')->map(function($status) { return ucfirst($status); })) !!},
            datasets: [{
                data: {!! json_encode($orderStatuses->pluck('count')) !!},
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#06b6d4'
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4,
                hoverBorderColor: '#f8fafc'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            cutout: '60%',
            radius: '90%'
        }
    });
}
</script>
@endpush
@endsection

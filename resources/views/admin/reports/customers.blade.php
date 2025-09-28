@extends('layouts.admin')

@section('page-title', 'Customer Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Customer Reports</h1>
                <p class="text-muted mb-0">Analyze customer behavior, retention, and lifetime value</p>
            </div>
            <div>
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-primary">
                    <i class="bi bi-graph-up"></i> Sales Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Customer Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($customerStats['total_customers']) }}</h4>
                        <p class="text-muted mb-0">Total Customers</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($customerStats['new_customers']) }}</h4>
                        <p class="text-muted mb-0">New Customers</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($customerStats['active_customers']) }}</h4>
                        <p class="text-muted mb-0">Active Customers</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">₱{{ number_format($customerLTV, 2) }}</h4>
                        <p class="text-muted mb-0">Avg Customer LTV</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Customers -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Top Customers by Spending</h6>
                </div>
                <div class="card-body">
                    @if($topCustomers->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Orders</th>
                                        <th>Total Spent</th>
                                        <th>Avg Order Value</th>
                                        <th>Last Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topCustomers as $customer)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-3"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $customer->name }}</strong><br>
                                                        <small class="text-muted">Joined {{ $customer->created_at->format('M Y') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                                    {{ $customer->email }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $customer->orders_count }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-success">₱{{ number_format($customer->orders_sum_total_amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                ₱{{ $customer->orders_count > 0 ? number_format($customer->orders_sum_total_amount / $customer->orders_count, 2) : '0.00' }}
                                            </td>
                                            <td>
                                                @if($customer->orders->count() > 0)
                                                    {{ $customer->orders->first()->created_at->format('M d, Y') }}
                                                @else
                                                    <span class="text-muted">No orders</span>
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
                            <h5 class="mt-3">No Customer Data</h5>
                            <p class="text-muted">No customers have made purchases yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer Segments -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Segments</h6>
                </div>
                <div class="card-body">
                    @if($customerSegments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($customerSegments as $segment)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $segment['segment'] }}</strong><br>
                                        <small class="text-muted">{{ $segment['description'] }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">{{ $segment['count'] }}</span><br>
                                        <small class="text-muted">{{ number_format(($segment['count'] / $customerStats['total_customers']) * 100, 1) }}%</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-info fs-1"></i>
                            <h5 class="mt-3">No Segments</h5>
                            <p class="text-muted">Customer segmentation data not available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Retention -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Retention Rate</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="retention-circle mb-3">
                            <div class="retention-value">{{ number_format($retentionRate, 1) }}%</div>
                            <div class="retention-label">Retention Rate</div>
                        </div>
                        <p class="text-muted">
                            {{ $customerStats['repeat_customers'] }} out of {{ $customerStats['total_customers'] }} customers
                            have made multiple purchases
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Growth Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Growth</h6>
                </div>
                <div class="card-body">
                    <canvas id="customerGrowthChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Insights -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Insights & Recommendations</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Key Metrics:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-graph-up text-success"></i>
                                    <strong>Customer Growth:</strong> {{ $customerStats['new_customers'] }} new customers this period
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-people text-info"></i>
                                    <strong>Active Rate:</strong> {{ $customerStats['total_customers'] > 0 ? number_format(($customerStats['active_customers'] / $customerStats['total_customers']) * 100, 1) : '0' }}% of customers are active
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-arrow-repeat text-warning"></i>
                                    <strong>Repeat Rate:</strong> {{ $customerStats['total_customers'] > 0 ? number_format(($customerStats['repeat_customers'] / $customerStats['total_customers']) * 100, 1) : '0' }}% of customers are repeat buyers
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-currency-dollar text-success"></i>
                                    <strong>Average LTV:</strong> ₱{{ number_format($customerLTV, 2) }} per customer
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">💡 Recommendations:</h6>
                            <ul class="list-unstyled">
                                @if($retentionRate < 30)
                                    <li class="mb-2">
                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                        <strong>Low Retention:</strong> Focus on customer engagement and loyalty programs
                                    </li>
                                @endif
                                @if($customerStats['new_customers'] < 10)
                                    <li class="mb-2">
                                        <i class="bi bi-person-plus text-info"></i>
                                        <strong>Growth Opportunity:</strong> Increase marketing efforts to attract new customers
                                    </li>
                                @endif
                                @if($customerLTV < 1000)
                                    <li class="mb-2">
                                        <i class="bi bi-currency-dollar text-success"></i>
                                        <strong>Revenue Potential:</strong> Implement upselling and cross-selling strategies
                                    </li>
                                @endif
                                <li class="mb-2">
                                    <i class="bi bi-star text-warning"></i>
                                    <strong>Customer Experience:</strong> Focus on improving customer satisfaction and support
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.retention-circle {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: conic-gradient(#007bff 0deg {{ $retentionRate * 3.6 }}deg, #e9ecef {{ $retentionRate * 3.6 }}deg 360deg);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    position: relative;
}

.retention-circle::before {
    content: '';
    width: 120px;
    height: 120px;
    background: white;
    border-radius: 50%;
    position: absolute;
}

.retention-value {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
    z-index: 1;
}

.retention-label {
    font-size: 12px;
    color: #6c757d;
    z-index: 1;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Customer Growth Chart
    const ctx = document.getElementById('customerGrowthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Customers',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
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
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection

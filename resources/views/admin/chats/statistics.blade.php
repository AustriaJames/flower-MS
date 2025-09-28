@extends('layouts.admin')

@section('page-title', 'Chat Statistics')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Chat Statistics</h1>
                <p class="text-muted mb-0">Analytics and insights for customer support</p>
            </div>
            <div>
                <a href="{{ route('admin.chats.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Chats
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $stats['total_chats'] }}</h4>
                        <p class="text-muted mb-0">Total Chats</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $stats['open_chats'] }}</h4>
                        <p class="text-muted mb-0">Open Chats</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $stats['resolved_chats'] }}</h4>
                        <p class="text-muted mb-0">Resolved</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $stats['avg_response_time'] }}</h4>
                        <p class="text-muted mb-0">Avg Response Time</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chat Volume Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Chat Volume (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="chatVolumeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Chat Status Distribution -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Chat Status Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="chatStatusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Chats -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Chats</h6>
                </div>
                <div class="card-body">
                    @if($recentChats->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentChats as $chat)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $chat->subject }}</strong><br>
                                        <small class="text-muted">{{ $chat->user->name ?? 'Guest' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $chat->status_badge_class }}">
                                            {{ ucfirst(str_replace('_', ' ', $chat->status)) }}
                                        </span><br>
                                        <small class="text-muted">{{ $chat->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-info fs-1"></i>
                            <h5 class="mt-3">No Recent Chats</h5>
                            <p class="text-muted">No chat conversations have been started yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Performance Metrics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h4 class="text-primary">{{ $stats['resolution_rate'] }}%</h4>
                            <small class="text-muted">Resolution Rate</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success">{{ $stats['satisfaction_score'] }}/5</h4>
                            <small class="text-muted">Satisfaction Score</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-info">{{ $stats['avg_messages_per_chat'] }}</h4>
                            <small class="text-muted">Avg Messages/Chat</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-warning">{{ $stats['peak_hours'] }}</h4>
                            <small class="text-muted">Peak Hours</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Insights -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Chat Insights & Recommendations</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Key Metrics:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-chat-dots text-success"></i>
                                    <strong>Total Conversations:</strong> {{ $stats['total_chats'] }} chats
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-clock text-warning"></i>
                                    <strong>Open Issues:</strong> {{ $stats['open_chats'] }} need attention
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <strong>Resolution Rate:</strong> {{ $stats['resolution_rate'] }}% of chats resolved
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-speedometer2 text-info"></i>
                                    <strong>Response Time:</strong> {{ $stats['avg_response_time'] }} average
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">💡 Recommendations:</h6>
                            <ul class="list-unstyled">
                                @if($stats['open_chats'] > 5)
                                    <li class="mb-2">
                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                        <strong>High Open Chats:</strong> Consider increasing support staff
                                    </li>
                                @endif
                                @if($stats['avg_response_time'] > '2 hours')
                                    <li class="mb-2">
                                        <i class="bi bi-clock text-danger"></i>
                                        <strong>Slow Response:</strong> Improve response time for better satisfaction
                                    </li>
                                @endif
                                @if($stats['resolution_rate'] < 80)
                                    <li class="mb-2">
                                        <i class="bi bi-target text-warning"></i>
                                        <strong>Low Resolution:</strong> Focus on improving issue resolution
                                    </li>
                                @endif
                                <li class="mb-2">
                                    <i class="bi bi-graph-up text-success"></i>
                                    <strong>Performance:</strong> Monitor trends and optimize support processes
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
    // Chat Volume Chart
    const ctx1 = document.getElementById('chatVolumeChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: @json($chatVolume['labels']),
            datasets: [{
                label: 'Chats',
                data: @json($chatVolume['data']),
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
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Chat Status Chart
    const ctx2 = document.getElementById('chatStatusChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'In Progress', 'Resolved', 'Closed'],
            datasets: [{
                data: [
                    {{ $stats['open_chats'] }},
                    {{ $stats['in_progress_chats'] ?? 0 }},
                    {{ $stats['resolved_chats'] }},
                    {{ $stats['closed_chats'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(0, 123, 255, 0.8)',
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(108, 117, 125, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 193, 7, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(40, 167, 69, 1)',
                    'rgba(108, 117, 125, 1)'
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

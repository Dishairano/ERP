@extends('layouts/contentNavbarLayout')

@section('title', 'Security Dashboard')

@section('content')
    <h4 class="fw-bold">Security Dashboard</h4>

    <!-- Security Overview Cards -->
    <div class="row">
        <!-- Failed Login Attempts -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="mb-2">Failed Login Attempts</p>
                            <h4 class="mb-0">{{ $failedLogins }}</h4>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-danger rounded p-2">
                                <i class="ri-shield-keyhole-line fs-4"></i>
                            </span>
                        </div>
                    </div>
                    <small class="text-muted">Last 24 hours</small>
                </div>
            </div>
        </div>

        <!-- 2FA Enabled Users -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="mb-2">2FA Enabled Users</p>
                            <h4 class="mb-0">{{ $twoFactorEnabled }}/{{ $totalUsers }}</h4>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-success rounded p-2">
                                <i class="ri-smartphone-line fs-4"></i>
                            </span>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ ($twoFactorEnabled / $totalUsers) * 100 }}%"
                            aria-valuenow="{{ ($twoFactorEnabled / $totalUsers) * 100 }}" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="mb-2">Active Sessions</p>
                            <h4 class="mb-0">{{ $activeSessions ?? 0 }}</h4>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-info rounded p-2">
                                <i class="ri-user-shared-line fs-4"></i>
                            </span>
                        </div>
                    </div>
                    <small class="text-muted">Currently online users</small>
                </div>
            </div>
        </div>

        <!-- Security Score -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="mb-2">Security Score</p>
                            <h4 class="mb-0">{{ $securityScore ?? 85 }}/100</h4>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-primary rounded p-2">
                                <i class="ri-shield-check-line fs-4"></i>
                            </span>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $securityScore ?? 85 }}%"
                            aria-valuenow="{{ $securityScore ?? 85 }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Security Events -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Recent Security Events</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Location</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                        <tr>
                            <td>{{ $log->event_type }}</td>
                            <td>{{ $log->user->name }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->location }}</td>
                            <td>{{ $log->created_at->diffForHumans() }}</td>
                            <td>
                                <span class="badge bg-{{ $log->status === 'success' ? 'success' : 'danger' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No recent security events</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security Recommendations -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Security Recommendations</h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @if ($twoFactorEnabled < $totalUsers)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Enable Two-Factor Authentication</h6>
                            <span class="badge bg-warning">Important</span>
                        </div>
                        <p class="mb-1">Some users haven't enabled 2FA yet. This leaves their accounts vulnerable to
                            unauthorized access.</p>
                        <a href="{{ route('security.settings') }}" class="btn btn-sm btn-primary mt-2">Review Settings</a>
                    </div>
                @endif
                @if ($failedLogins > 0)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Review Failed Login Attempts</h6>
                            <span class="badge bg-danger">Critical</span>
                        </div>
                        <p class="mb-1">Multiple failed login attempts detected. Consider reviewing and updating password
                            policies.</p>
                        <a href="#" class="btn btn-sm btn-primary mt-2">View Details</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

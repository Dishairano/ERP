@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Dashboard')

@section('content')
    <h4 class="fw-bold">Compliance Dashboard</h4>

    <div class="row">
        <!-- Requirements Overview Card -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-0">Requirements</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('compliance.requirements') }}">
                                    <i class="ri-eye-line me-2"></i>View All
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="badge bg-label-warning me-2">
                                    <i class="ri-timer-line"></i>
                                </div>
                                <div>Pending</div>
                            </div>
                            <div>{{ $pendingRequirements ?? 0 }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="badge bg-label-info me-2">
                                    <i class="ri-refresh-line"></i>
                                </div>
                                <div>In Progress</div>
                            </div>
                            <div>{{ $inProgressRequirements ?? 0 }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="badge bg-label-success me-2">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                                <div>Completed</div>
                            </div>
                            <div>{{ $completedRequirements ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audits Overview Card -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-0">Audits</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('compliance.audits') }}">
                                    <i class="ri-eye-line me-2"></i>View All
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="badge bg-label-primary me-2">
                                    <i class="ri-calendar-line"></i>
                                </div>
                                <div>Scheduled</div>
                            </div>
                            <div>{{ $scheduledAudits ?? 0 }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="badge bg-label-info me-2">
                                    <i class="ri-refresh-line"></i>
                                </div>
                                <div>In Progress</div>
                            </div>
                            <div>{{ $inProgressAudits ?? 0 }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="badge bg-label-success me-2">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                                <div>Completed</div>
                            </div>
                            <div>{{ $completedAudits ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance Score Card -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-0">Compliance Score</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('compliance.settings') }}">
                                    <i class="ri-settings-line me-2"></i>Settings
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <div class="display-6 mb-3">{{ $complianceScore ?? 0 }}%</div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $complianceScore ?? 0 }}%"
                                aria-valuenow="{{ $complianceScore ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="mt-3 text-muted">
                            Based on completed requirements and audit results
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Recent Activity</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Type</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities ?? [] as $activity)
                        <tr>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->type }}</td>
                            <td>{{ $activity->user->name }}</td>
                            <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $activity->status_color }}">
                                    {{ $activity->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No recent activity</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

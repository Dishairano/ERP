@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Weekly Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Hours</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($weeklyStats['total_hours'], 2) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-time-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Billable Hours</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($weeklyStats['billable_hours'], 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Overtime Hours</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($weeklyStats['overtime_hours'], 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-timer-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Active Projects</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $weeklyStats['projects_count'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-folder-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Weekly Time Distribution -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Weekly Time Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Hours</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projectAllocation as $project)
                                        <tr>
                                            <td>{{ $project['project_name'] }}</td>
                                            <td>{{ number_format($project['total_hours'], 2) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress w-100" style="height: 8px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $project['percentage'] }}%"
                                                            aria-valuenow="{{ $project['percentage'] }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="ms-2">{{ number_format($project['percentage'], 1) }}%</span>
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

            <!-- Pending Approvals -->
            @if (count($pendingApprovals) > 0)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pending Approvals</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Project</th>
                                            <th>Hours</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingApprovals as $registration)
                                            <tr>
                                                <td>{{ $registration->user->name }}</td>
                                                <td>{{ $registration->project->name }}</td>
                                                <td>{{ number_format($registration->duration, 2) }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form
                                                                    action="{{ route('time-registration.approve', $registration) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="ri-check-line me-1"></i> Approve
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <a href="#" class="dropdown-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#rejectModal{{ $registration->id }}">
                                                                    <i class="ri-close-line me-1"></i> Reject
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" class="dropdown-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#detailsModal{{ $registration->id }}">
                                                                    <i class="ri-eye-line me-1"></i> View Details
                                                                </a>
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
            @endif
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Activities</h5>
                        <button class="btn btn-primary"
                            onclick="window.location.href='{{ route('time-registration.create') }}'">
                            <i class="ri-add-line"></i> Register Time
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Project</th>
                                        <th>Task</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentActivities as $activity)
                                        <tr>
                                            <td>{{ $activity->date->format('M d, Y') }}</td>
                                            <td>{{ $activity->project->name }}</td>
                                            <td>{{ $activity->task->name }}</td>
                                            <td>{{ number_format($activity->duration, 2) }} hrs</td>
                                            <td>
                                                <span
                                                    class="badge bg-label-{{ $activity->status === 'approved' ? 'success' : ($activity->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($activity->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    @foreach ($pendingApprovals as $registration)
        <div class="modal fade" id="rejectModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('time-registration.reject', $registration) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Reject Time Registration</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Reason for Rejection</label>
                                <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Details Modal -->
    @foreach ($pendingApprovals as $registration)
        <div class="modal fade" id="detailsModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Time Registration Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Employee</label>
                                <p class="form-control-static">{{ $registration->user->name }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Project</label>
                                <p class="form-control-static">{{ $registration->project->name }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Date</label>
                                <p class="form-control-static">{{ $registration->date->format('M d, Y') }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Duration</label>
                                <p class="form-control-static">{{ number_format($registration->duration, 2) }} hrs</p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <p class="form-control-static">{{ $registration->description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('page-script')
    <script>
        // Add any custom JavaScript for the dashboard page here
    </script>
@endsection

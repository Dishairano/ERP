@extends('layouts/contentNavbarLayout')

@section('title', 'Project Dashboard')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Project Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Projects</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $projectStatistics['total'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-folder-line"></i>
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
                                <p class="card-text">On Track</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $projectStatistics['on_track'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-check-line"></i>
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
                                <p class="card-text">At Risk</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $projectStatistics['at_risk'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-error-warning-line"></i>
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
                                <p class="card-text">Delayed</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $projectStatistics['delayed'] }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="ri-time-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Overview -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Budget Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Allocated</span>
                                        <span
                                            class="fw-bold">{{ number_format($budgetOverview['total_allocated'], 2) }}</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Spent</span>
                                        <span class="fw-bold">{{ number_format($budgetOverview['total_spent'], 2) }}</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        @php
                                            $spentPercentage =
                                                $budgetOverview['total_allocated'] > 0
                                                    ? ($budgetOverview['total_spent'] /
                                                            $budgetOverview['total_allocated']) *
                                                        100
                                                    : 0;
                                        @endphp
                                        <div class="progress-bar bg-success" style="width: {{ $spentPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Remaining</span>
                                        <span
                                            class="fw-bold">{{ number_format($budgetOverview['total_remaining'], 2) }}</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        @php
                                            $remainingPercentage =
                                                $budgetOverview['total_allocated'] > 0
                                                    ? ($budgetOverview['total_remaining'] /
                                                            $budgetOverview['total_allocated']) *
                                                        100
                                                    : 0;
                                        @endphp
                                        <div class="progress-bar bg-info" style="width: {{ $remainingPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Projects Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="projectsTable">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Progress</th>
                                        <th>Tasks</th>
                                        <th>Budget</th>
                                        <th>Timeline</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dashboards as $dashboard)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">{{ $dashboard->project->name }}</h6>
                                                        <small
                                                            class="text-muted">{{ $dashboard->project->description }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <small class="mb-1">{{ $dashboard->progress_percentage }}%</small>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar"
                                                            style="width: {{ $dashboard->progress_percentage }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <small
                                                        class="mb-1">{{ $dashboard->completed_tasks }}/{{ $dashboard->total_tasks }}</small>
                                                    @if ($dashboard->overdue_tasks > 0)
                                                        <span
                                                            class="badge bg-label-danger">{{ $dashboard->overdue_tasks }}
                                                            Overdue</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <small
                                                        class="mb-1">{{ number_format($dashboard->budget_spent, 2) }}/{{ number_format($dashboard->budget_allocated, 2) }}</small>
                                                    @php
                                                        $budgetStatus = $dashboard->calculateBudgetStatus();
                                                        $statusClass = match ($budgetStatus) {
                                                            'over_budget' => 'danger',
                                                            'critical' => 'warning',
                                                            'warning' => 'warning',
                                                            default => 'success',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge bg-label-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $budgetStatus)) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <small class="mb-1">{{ $dashboard->start_date->format('M d') }} -
                                                        {{ $dashboard->end_date->format('M d, Y') }}</small>
                                                    @php
                                                        $timelineStatus = $dashboard->getTimelineStatus();
                                                        $timelineClass = match ($timelineStatus) {
                                                            'overdue' => 'danger',
                                                            'behind_schedule' => 'warning',
                                                            'ahead_schedule' => 'success',
                                                            default => 'primary',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge bg-label-{{ $timelineClass }}">{{ ucfirst(str_replace('_', ' ', $timelineStatus)) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match ($dashboard->status) {
                                                        'completed' => 'success',
                                                        'on_track' => 'primary',
                                                        'at_risk' => 'warning',
                                                        'delayed' => 'danger',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge bg-label-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $dashboard->status)) }}</span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('projects.dashboard.show', $dashboard->project_id) }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="refreshMetrics({{ $dashboard->project_id }})">
                                                            <i class="ri-refresh-line me-1"></i> Refresh Metrics
                                                        </a>
                                                    </div>
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
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script>
        function refreshMetrics(projectId) {
            fetch(`/projects/${projectId}/dashboard/refresh`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // Show success message and reload the page
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTables
            $('#projectsTable').DataTable({
                order: [
                    [5, 'desc']
                ],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                responsive: true
            });
        });
    </script>
@endsection

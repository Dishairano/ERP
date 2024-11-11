@extends('layouts/contentNavbarLayout')

@section('title', 'Project Details')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Project Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">{{ $dashboard->project->name }}</h4>
                                <p class="text-muted mb-0">{{ $dashboard->project->description }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" onclick="refreshMetrics({{ $dashboard->project_id }})">
                                    <i class="ri-refresh-line me-1"></i> Refresh Metrics
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Overview Cards -->
        <div class="row">
            <!-- Progress -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-2">Progress</h6>
                                <h4 class="mb-0">{{ number_format($dashboard->progress_percentage, 1) }}%</h4>
                            </div>
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri-pie-chart-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" style="width: {{ $dashboard->progress_percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-2">Budget</h6>
                                <h4 class="mb-0">{{ number_format($dashboard->budget_spent, 2) }}</h4>
                                <small class="text-muted">of {{ number_format($dashboard->budget_allocated, 2) }}</small>
                            </div>
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            @php
                                $budgetPercentage =
                                    $dashboard->budget_allocated > 0
                                        ? ($dashboard->budget_spent / $dashboard->budget_allocated) * 100
                                        : 0;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $budgetPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-2">Tasks</h6>
                                <h4 class="mb-0">{{ $taskBreakdown['completed'] }}/{{ $taskBreakdown['total'] }}</h4>
                                @if ($taskBreakdown['overdue'] > 0)
                                    <small class="text-danger">{{ $taskBreakdown['overdue'] }} Overdue</small>
                                @endif
                            </div>
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ri-task-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            @php
                                $taskPercentage =
                                    $taskBreakdown['total'] > 0
                                        ? ($taskBreakdown['completed'] / $taskBreakdown['total']) * 100
                                        : 0;
                            @endphp
                            <div class="progress-bar bg-warning" style="width: {{ $taskPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-2">Timeline</h6>
                                <h4 class="mb-0">{{ $dashboard->end_date->diffForHumans() }}</h4>
                                <small class="text-muted">{{ $timelineStatus }}</small>
                            </div>
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ri-calendar-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members & Recent Activities -->
        <div class="row">
            <!-- Team Members -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Team Members</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($dashboard->team_members as $member)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-sm">
                                        @if (isset($member['avatar']))
                                            <img src="{{ $member['avatar'] }}" alt="Avatar" class="rounded-circle">
                                        @else
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($member['name'], 0, 2)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $member['name'] }}</h6>
                                        <small class="text-muted">{{ $member['role'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <ul class="timeline timeline-dashed mb-0">
                            @foreach ($dashboard->recent_activities as $activity)
                                <li class="timeline-item timeline-item-transparent">
                                    <span class="timeline-point timeline-point-primary"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header">
                                            <h6 class="mb-0">{{ $activity['description'] }}</h6>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</small>
                                        </div>
                                        @if (isset($activity['status']))
                                            <div class="mt-1">
                                                <span
                                                    class="badge bg-label-{{ $activity['status'] === 'completed' ? 'success' : 'primary' }}">
                                                    {{ ucfirst($activity['status']) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Milestones & Risk Summary -->
        <div class="row">
            <!-- Upcoming Milestones -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Upcoming Milestones</h5>
                    </div>
                    <div class="card-body">
                        <ul class="timeline timeline-dashed mb-0">
                            @foreach ($dashboard->upcoming_milestones as $milestone)
                                <li class="timeline-item timeline-item-transparent">
                                    <span class="timeline-point timeline-point-warning"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header">
                                            <h6 class="mb-0">{{ $milestone['name'] }}</h6>
                                            <small class="text-muted">Due
                                                {{ \Carbon\Carbon::parse($milestone['due_date'])->format('M d, Y') }}</small>
                                        </div>
                                        <div class="mt-1">
                                            <span
                                                class="badge bg-label-{{ $milestone['status'] === 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($milestone['status']) }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Risk Summary -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Risk Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <h6 class="mb-2">Total Risks</h6>
                                <h4 class="mb-0">{{ $dashboard->risk_summary['total'] }}</h4>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="text-center">
                                    <div class="badge bg-danger mb-2">{{ $dashboard->risk_summary['high'] }}</div>
                                    <span class="d-block text-muted">High</span>
                                </div>
                                <div class="text-center">
                                    <div class="badge bg-warning mb-2">{{ $dashboard->risk_summary['medium'] }}</div>
                                    <span class="d-block text-muted">Medium</span>
                                </div>
                                <div class="text-center">
                                    <div class="badge bg-success mb-2">{{ $dashboard->risk_summary['low'] }}</div>
                                    <span class="d-block text-muted">Low</span>
                                </div>
                            </div>
                        </div>
                        <div id="riskChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
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
            // Initialize Risk Chart
            const riskChartEl = document.querySelector('#riskChart');
            if (riskChartEl) {
                const riskChart = new ApexCharts(riskChartEl, {
                    series: [
                        {{ $dashboard->risk_summary['high'] }},
                        {{ $dashboard->risk_summary['medium'] }},
                        {{ $dashboard->risk_summary['low'] }}
                    ],
                    chart: {
                        height: 200,
                        type: 'donut'
                    },
                    labels: ['High', 'Medium', 'Low'],
                    colors: ['#ff4d4f', '#faad14', '#52c41a'],
                    legend: {
                        show: false
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%'
                            }
                        }
                    }
                });
                riskChart.render();
            }
        });
    </script>
@endsection

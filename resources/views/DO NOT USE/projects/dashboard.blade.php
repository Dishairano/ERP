@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Project Metrics -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="metric">
                                    <h6>Total Projects</h6>
                                    <h3>{{ $projectMetrics['total'] }}</h3>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="metric">
                                    <h6>Active Projects</h6>
                                    <h3>{{ $projectMetrics['active'] }}</h3>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="metric">
                                    <h6>Completion Rate</h6>
                                    <h3>{{ $projectMetrics['completion_rate'] }}%</h3>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="metric">
                                    <h6>Budget Variance</h6>
                                    <h3>{{ $projectMetrics['budget_variance'] }}%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach ($recentActivities as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="{{ $activity['icon_class'] }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>{{ $activity['description'] }}</h6>
                                        <small>
                                            {{ $activity['user_name'] }} -
                                            {{ $activity['created_at']->diffForHumans() }}
                                            @if ($activity['project_name'])
                                                in {{ $activity['project_name'] }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Risk Summary -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Risk Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="risk-metrics">
                            @foreach ($riskSummary['recent'] as $risk)
                                <div class="risk-item">
                                    <h6>{{ $risk->title }}</h6>
                                    <span
                                        class="badge bg-label-{{ $risk->priority === 'high' ? 'danger' : ($risk->priority === 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($risk->priority) }}
                                    </span>
                                    <p class="mb-0">{{ Str::limit($risk->description, 100) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Upcoming Deadlines</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Project</th>
                                        <th>Due Date</th>
                                        <th>Assigned To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upcomingDeadlines as $task)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td>{{ $task->project->name }}</td>
                                            <td>{{ $task->due_date->format('M d, Y') }}</td>
                                            <td>{{ $task->assignedTo->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Tracking -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Time Tracking</h5>
                    </div>
                    <div class="card-body">
                        <div class="time-metrics">
                            <div class="row">
                                <div class="col-6">
                                    <div class="metric">
                                        <h6>Weekly Total</h6>
                                        <h3>{{ $timeTracking['weekly_total'] }} hrs</h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="metric">
                                        <h6>Pending Approvals</h6>
                                        <h3>{{ $timeTracking['pending_approvals'] }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="project-distribution mt-4">
                                <h6>Project Distribution</h6>
                                @foreach ($timeTracking['project_distribution'] as $distribution)
                                    <div class="project-time">
                                        <span>{{ $distribution->project->name }}</span>
                                        <span>{{ $distribution->total_hours }} hrs</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

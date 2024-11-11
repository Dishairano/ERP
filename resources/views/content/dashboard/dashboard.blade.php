@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Active Job Postings
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeJobPostings }}</div>
                                <div class="text-xs {{ $jobPostingsGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $jobPostingsGrowth }}% from last month
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Candidates
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCandidates }}</div>
                                <div class="text-xs {{ $candidatesGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $candidatesGrowth }}% from last month
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Upcoming Interviews
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $upcomingInterviews }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Assessments
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingAssessments }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Overview Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Budget</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($financialStats['total_budget']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Spent</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($financialStats['total_spent']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Monthly Cash Flow</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($financialStats['cash_flow']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Budget Variance</div>
                                <div
                                    class="h5 mb-0 font-weight-bold {{ $financialStats['budget_variance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    ${{ number_format(abs($financialStats['budget_variance'])) }}
                                    {{ $financialStats['budget_variance'] >= 0 ? 'Under' : 'Over' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Management Overview -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Weekly Hours</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($timeStats['total_hours_this_week']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monthly Hours</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($timeStats['total_hours_this_month']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Overtime Hours</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($timeStats['overtime_hours']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">On Leave Today</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveStats['on_leave_today'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Pipeline Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recruitment Pipeline</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="pipelineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interview Distribution Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Interview Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie">
                            <canvas id="interviewDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Performance -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Project Performance Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Progress</th>
                                        <th>Tasks</th>
                                        <th>Budget</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Risks</th>
                                        <th>Team</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projectStats as $project)
                                        <tr>
                                            <td>{{ $project['name'] }}</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $project['progress'] }}%">
                                                        {{ $project['progress'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $project['tasks_completed'] }}/{{ $project['tasks_total'] }}
                                                @if ($project['high_priority_tasks'] > 0)
                                                    <span
                                                        class="badge badge-danger ml-1">{{ $project['high_priority_tasks'] }}
                                                        High</span>
                                                @endif
                                                @if ($project['overdue_tasks'] > 0)
                                                    <span class="badge badge-warning ml-1">{{ $project['overdue_tasks'] }}
                                                        Overdue</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($project['budget_spent']) }}/${{ number_format($project['budget_total']) }}
                                            </td>
                                            <td><span
                                                    class="badge badge-{{ $project['status'] === 'active' ? 'success' : 'warning' }}">{{ ucfirst($project['status']) }}</span>
                                            </td>
                                            <td><span
                                                    class="badge badge-{{ $project['priority'] === 'high' ? 'danger' : ($project['priority'] === 'medium' ? 'warning' : 'info') }}">{{ ucfirst($project['priority']) }}</span>
                                            </td>
                                            <td>{{ $project['risk_count'] }}</td>
                                            <td>{{ $project['team_members'] }}</td>
                                            <td>{{ $project['last_updated'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Reviews -->
        <div class="row mb-4">
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Performance Review Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $performanceStats['completed'] }}
                                </div>
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Completed</div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $performanceStats['pending'] }}
                                </div>
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $performanceStats['upcoming'] }}
                                </div>
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Upcoming</div>
                            </div>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Average Rating:
                                {{ number_format($performanceStats['average_rating'], 1) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Leave Management Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $leaveStats['pending_requests'] }}
                                </div>
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pending Requests
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $leaveStats['approved_upcoming'] }}
                                </div>
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Upcoming Leaves
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $leaveStats['on_leave_today'] }}
                                </div>
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">On Leave Today</div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6 class="font-weight-bold">Leave Distribution</h6>
                            @php
                                $totalLeaves = array_sum($leaveStats['leave_distribution']);
                            @endphp
                            <div class="progress-group">
                                <span class="progress-text">Vacation</span>
                                <span class="float-right">{{ $leaveStats['leave_distribution']['vacation'] }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-primary"
                                        style="width: {{ $totalLeaves > 0 ? ($leaveStats['leave_distribution']['vacation'] / $totalLeaves) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                            <div class="progress-group">
                                <span class="progress-text">Sick</span>
                                <span class="float-right">{{ $leaveStats['leave_distribution']['sick'] }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-warning"
                                        style="width: {{ $totalLeaves > 0 ? ($leaveStats['leave_distribution']['sick'] / $totalLeaves) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                            <div class="progress-group">
                                <span class="progress-text">Personal</span>
                                <span class="float-right">{{ $leaveStats['leave_distribution']['personal'] }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-success"
                                        style="width: {{ $totalLeaves > 0 ? ($leaveStats['leave_distribution']['personal'] / $totalLeaves) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                            <div class="progress-group">
                                <span class="progress-text">Other</span>
                                <span class="float-right">{{ $leaveStats['leave_distribution']['other'] }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-info"
                                        style="width: {{ $totalLeaves > 0 ? ($leaveStats['leave_distribution']['other'] / $totalLeaves) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Overview -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Department Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Manager</th>
                                        <th>Employees</th>
                                        <th>Open Positions</th>
                                        <th>Pending Reviews</th>
                                        <th>Approved Leaves</th>
                                        <th>Training Status</th>
                                        <th>Budget Overview</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departmentStats as $dept)
                                        <tr>
                                            <td>{{ $dept['name'] }}</td>
                                            <td>{{ $dept['manager'] ?? 'Not Assigned' }}</td>
                                            <td>{{ $dept['employees'] }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $dept['open_positions'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning">{{ $dept['pending_reviews'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $dept['approved_leaves'] }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 mr-2" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $dept['training_completion'] }}%">
                                                        </div>
                                                    </div>
                                                    <small>{{ $dept['completed_trainings'] }}/{{ $dept['total_trainings'] }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="progress mb-1" style="height: 8px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $dept['budget_utilization'] }}%">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">
                                                        ${{ number_format($dept['budget_spent']) }}/${{ number_format($dept['budget_allocated']) }}
                                                    </small>
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

@push('scripts')
    <script>
        // Pipeline Chart
        var pipelineCtx = document.getElementById('pipelineChart').getContext('2d');
        var pipelineChart = new Chart(pipelineCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($pipelineData['applications'], 'date')) !!},
                datasets: [{
                        label: 'Applications',
                        data: {!! json_encode(array_column($pipelineData['applications'], 'count')) !!},
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        pointRadius: 3,
                        pointBackgroundColor: '#4e73df',
                        pointBorderColor: '#4e73df',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: '#4e73df',
                        pointHoverBorderColor: '#4e73df',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Interviews',
                        data: {!! json_encode(array_column($pipelineData['interviews'], 'count')) !!},
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.05)',
                        pointRadius: 3,
                        pointBackgroundColor: '#1cc88a',
                        pointBorderColor: '#1cc88a',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: '#1cc88a',
                        pointHoverBorderColor: '#1cc88a',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Offers',
                        data: {!! json_encode(array_column($pipelineData['offers'], 'count')) !!},
                        borderColor: '#36b9cc',
                        backgroundColor: 'rgba(54, 185, 204, 0.05)',
                        pointRadius: 3,
                        pointBackgroundColor: '#36b9cc',
                        pointBorderColor: '#36b9cc',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: '#36b9cc',
                        pointHoverBorderColor: '#36b9cc',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        fill: true
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: true
                }
            }
        });

        // Interview Distribution Chart
        var distributionCtx = document.getElementById('interviewDistributionChart').getContext('2d');
        var distributionChart = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($interviewDistribution['labels']) !!},
                datasets: [{
                    data: {!! json_encode($interviewDistribution['values']) !!},
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a'],
                    hoverBorderColor: 'rgba(234, 236, 244, 1)',
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyFontColor: '#858796',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                cutoutPercentage: 80,
            },
        });

        // Time Distribution Chart
        var timeDistributionCtx = document.getElementById('timeDistributionChart');
        if (timeDistributionCtx) {
            var timeDistributionChart = new Chart(timeDistributionCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys({!! json_encode($timeStats['project_distribution']) !!}),
                    datasets: [{
                        data: Object.values({!! json_encode($timeStats['project_distribution']) !!}),
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                            '#5a5c69', '#858796', '#f8f9fc', '#d1d3e2', '#b7b9cc'
                        ],
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    }
                }
            });
        }
    </script>
@endpush

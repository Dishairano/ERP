@extends('layouts/contentNavbarLayout')

@section('title', 'Project Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Project Overview Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Projects</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projects->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tasks Completed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $tasksByStatus->where('status', 'completed')->first()?->count ?? 0 }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Overall Progress</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            {{ round($projects->avg('progress')) }}%
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: {{ $projects->avg('progress') }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Upcoming Deadlines
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $upcomingDeadlines->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project List -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Active Projects</h6>
                        <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-list"></i> View Projects
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Progress</th>
                                        <th>Tasks</th>
                                        <th>Budget</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>{{ is_array($project) ? $project['name'] ?? '' : optional($project)->name }}</td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: {{ is_array($project) ? $project['progress'] ?? 0 : optional($project)->progress ?? 0 }}%">
                                                        {{ is_array($project) ? $project['progress'] ?? 0 : optional($project)->progress ?? 0 }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ is_array($project) ? ($project['tasks_completed'] ?? 0) : optional($project)->tasks_completed ?? 0 }}/{{ is_array($project) ? ($project['tasks_total'] ?? 0) : optional($project)->tasks_total ?? 0 }}</td>
                                            <td>${{ number_format(is_array($project) ? ($project['budget_spent'] ?? 0) : optional($project)->budget_spent ?? 0) }} /
                                                ${{ number_format(is_array($project) ? ($project['budget_total'] ?? 0) : optional($project)->budget_total ?? 0) }}</td>
                                            <td>
                                                @php
                                                    $status = is_array($project) ? ($project['status'] ?? '') : optional($project)->status;
                                                @endphp
                                                <span
                                                    class="badge badge-{{ $status === 'completed'
                                                        ? 'success'
                                                        : ($status === 'active'
                                                            ? 'primary'
                                                            : ($status === 'pending'
                                                                ? 'warning'
                                                                : 'danger')) }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $priority = is_array($project) ? ($project['priority'] ?? '') : optional($project)->priority;
                                                @endphp
                                                <span
                                                    class="badge badge-{{ $priority === 'high' ? 'danger' : ($priority === 'medium' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('projects.tasks.create', is_array($project) ? ($project['id'] ?? 0) : optional($project)->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus"></i> New Task
                                                </a>
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

        <!-- Upcoming Deadlines -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Upcoming Deadlines</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Project</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upcomingDeadlines as $task)
                                        <tr>
                                            <td>{{ optional($task)->title }}</td>
                                            <td>{{ optional($task->project)->name }}</td>
                                            <td>{{ optional($task->due_date)->format('M d, Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ optional($task)->status === 'completed' ? 'success' : (optional($task)->status === 'in_progress' ? 'primary' : 'warning') }}">
                                                    {{ ucfirst(optional($task)->status) }}
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

            <!-- Task Status Distribution -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Task Status Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="taskStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Task Status Distribution Chart
        var taskStatusCtx = document.getElementById('taskStatusChart').getContext('2d');
        var taskStatusData = @json($tasksByStatus);

        new Chart(taskStatusCtx, {
            type: 'doughnut',
            data: {
                labels: taskStatusData.map(item => item.status),
                datasets: [{
                    data: taskStatusData.map(item => item.count),
                    backgroundColor: [
                        '#4e73df', // Primary
                        '#1cc88a', // Success
                        '#36b9cc', // Info
                        '#f6c23e' // Warning
                    ],
                    hoverBackgroundColor: [
                        '#2e59d9',
                        '#17a673',
                        '#2c9faf',
                        '#dda20a'
                    ],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
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
    </script>
@endpush

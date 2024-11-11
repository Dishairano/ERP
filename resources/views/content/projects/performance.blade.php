@extends('layouts/contentNavbarLayout')

@section('title', 'Project Performance Metrics')

@section('content')
    <div class="container-fluid">
        <!-- Project Performance Overview -->
        <div class="row">
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
                                        <th>Project Name</th>
                                        <th>Progress</th>
                                        <th>Budget Performance</th>
                                        <th>Schedule Performance</th>
                                        <th>Task Completion Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projectPerformance as $project)
                                        <tr>
                                            <td>{{ $project['name'] }}</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $project['progress'] }}%"
                                                        aria-valuenow="{{ $project['progress'] }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ $project['progress'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 mr-2">
                                                        <div class="progress-bar {{ $project['budget_performance'] > 100 ? 'bg-danger' : 'bg-success' }}"
                                                            role="progressbar"
                                                            style="width: {{ min($project['budget_performance'], 100) }}%">
                                                        </div>
                                                    </div>
                                                    <span>{{ round($project['budget_performance']) }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 mr-2">
                                                        <div class="progress-bar {{ $project['schedule_performance'] < 90 ? 'bg-danger' : 'bg-success' }}"
                                                            role="progressbar"
                                                            style="width: {{ $project['schedule_performance'] }}%">
                                                        </div>
                                                    </div>
                                                    <span>{{ round($project['schedule_performance']) }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 mr-2">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $project['task_completion_rate'] }}%">
                                                        </div>
                                                    </div>
                                                    <span>{{ round($project['task_completion_rate']) }}%</span>
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

        <!-- Performance Charts -->
        <div class="row">
            <!-- Team Performance Chart -->
            <div class="col-xl-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Team Performance</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Team Member</th>
                                        <th>Total Tasks</th>
                                        <th>Completed</th>
                                        <th>Efficiency Ratio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teamPerformance as $member)
                                        <tr>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->total_tasks }}</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ ($member->completed_tasks / $member->total_tasks) * 100 }}%">
                                                        {{ $member->completed_tasks }}/{{ $member->total_tasks }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $member->efficiency_ratio <= 1 ? 'success' : ($member->efficiency_ratio <= 1.2 ? 'warning' : 'danger') }}">
                                                    {{ number_format($member->efficiency_ratio, 2) }}
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

            <!-- Performance Metrics Chart -->
            <div class="col-xl-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Performance Metrics</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="performanceMetricsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Performance Metrics Chart
        var ctx = document.getElementById('performanceMetricsChart').getContext('2d');
        var projectData = @json($projectPerformance);

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Progress', 'Budget Performance', 'Schedule Performance', 'Task Completion'],
                datasets: projectData.map(project => ({
                    label: project.name,
                    data: [
                        project.progress,
                        project.budget_performance,
                        project.schedule_performance,
                        project.task_completion_rate
                    ],
                    fill: true,
                    backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.2)`,
                    borderColor: `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`,
                    pointBackgroundColor: `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`,
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`
                }))
            },
            options: {
                elements: {
                    line: {
                        borderWidth: 3
                    }
                },
                scale: {
                    ticks: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
@endpush

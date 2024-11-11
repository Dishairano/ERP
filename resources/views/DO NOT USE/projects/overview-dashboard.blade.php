@extends('layouts/contentNavbarLayout')

@section('title', 'Projects Dashboard')

@section('content')
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="fw-bolder">{{ $statistics['total_projects'] }}</h2>
                    <p class="card-text">Total Projects</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="fw-bolder">{{ $statistics['active_projects'] }}</h2>
                    <p class="card-text">Active Projects</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="fw-bolder">{{ $statistics['completed_projects'] }}</h2>
                    <p class="card-text">Completed Projects</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="fw-bolder">{{ $statistics['delayed_projects'] }}</h2>
                    <p class="card-text">Delayed Projects</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="fw-bolder">{{ $statistics['at_risk_projects'] }}</h2>
                    <p class="card-text">At Risk Projects</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Projects -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Projects</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Manager</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentProjects as $project)
                                    <tr>
                                        <td>
                                            <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                                        </td>
                                        <td>{{ $project->manager->name }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'at_risk' ? 'warning' : 'info') }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $project->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Deadlines -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Upcoming Deadlines</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Manager</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($upcomingDeadlines as $project)
                                    <tr>
                                        <td>
                                            <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                                        </td>
                                        <td>{{ $project->manager->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'at_risk' ? 'warning' : 'info') }}">
                                                {{ ucfirst($project->status) }}
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

    <!-- Project Status Distribution Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Project Status Distribution</h4>
                </div>
                <div class="card-body">
                    <canvas id="projectStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/charts/chart.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('projectStatusChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Active', 'Completed', 'Delayed', 'At Risk'],
                    datasets: [{
                        label: 'Number of Projects',
                        data: [
                            {{ $statistics['active_projects'] }},
                            {{ $statistics['completed_projects'] }},
                            {{ $statistics['delayed_projects'] }},
                            {{ $statistics['at_risk_projects'] }}
                        ],
                        backgroundColor: [
                            'rgba(40, 199, 111, 0.2)',
                            'rgba(0, 207, 232, 0.2)',
                            'rgba(255, 159, 67, 0.2)',
                            'rgba(234, 84, 85, 0.2)'
                        ],
                        borderColor: [
                            'rgb(40, 199, 111)',
                            'rgb(0, 207, 232)',
                            'rgb(255, 159, 67)',
                            'rgb(234, 84, 85)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
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
        });
    </script>
@endsection

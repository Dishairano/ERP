@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Analytics')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Welcome! 🎉</h5>
                                <p class="mb-4">Your ERP system overview and analytics dashboard.</p>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <!-- Dashboard illustration or stats can go here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards Row -->
            <div class="row">
                <!-- Projects KPI -->
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="card-text">Active Projects</p>
                                    <div class="d-flex align-items-end mb-2">
                                        <h4 class="card-title mb-0 me-2">0</h4>
                                    </div>
                                </div>
                                <div class="card-icon">
                                    <span class="badge bg-label-primary rounded p-2">
                                        <i class="ri-projector-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks KPI -->
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="card-text">Pending Tasks</p>
                                    <div class="d-flex align-items-end mb-2">
                                        <h4 class="card-title mb-0 me-2">0</h4>
                                    </div>
                                </div>
                                <div class="card-icon">
                                    <span class="badge bg-label-warning rounded p-2">
                                        <i class="ri-task-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Registrations KPI -->
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="card-text">Hours Logged Today</p>
                                    <div class="d-flex align-items-end mb-2">
                                        <h4 class="card-title mb-0 me-2">0</h4>
                                    </div>
                                </div>
                                <div class="card-icon">
                                    <span class="badge bg-label-info rounded p-2">
                                        <i class="ri-time-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Risks KPI -->
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="card-text">High Priority Risks</p>
                                    <div class="d-flex align-items-end mb-2">
                                        <h4 class="card-title mb-0 me-2">0</h4>
                                    </div>
                                </div>
                                <div class="card-icon">
                                    <span class="badge bg-label-danger rounded p-2">
                                        <i class="ri-alert-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <!-- Project Progress Chart -->
                <div class="col-12 col-lg-8 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Project Progress Overview</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="projectProgressChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Task Distribution -->
                <div class="col-12 col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Task Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="taskDistributionChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Project Progress Chart
            const projectCtx = document.getElementById('projectProgressChart').getContext('2d');
            new Chart(projectCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Project Progress',
                        data: [0, 0, 0, 0, 0, 0],
                        borderColor: '#696cff',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Task Distribution Chart
            const taskCtx = document.getElementById('taskDistributionChart').getContext('2d');
            new Chart(taskCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'In Progress', 'Pending'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: ['#71dd37', '#696cff', '#ff3e1d']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
@endsection

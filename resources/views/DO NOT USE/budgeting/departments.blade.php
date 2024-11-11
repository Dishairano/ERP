@extends('layouts/contentNavbarLayout')

@section('title', 'Department Budgets')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Department Budgets</h4>
                                <p class="mb-0">Manage and track budgets by department</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                                        <i class="ri-add-line"></i> Create Budget
                                    </a>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                                href="{{ route('preset-departments.index') }}">Department Presets</a></li>
                                        <li><a class="dropdown-item" href="{{ route('budgets.reports') }}">Generate
                                                Report</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Summary Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Departments</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">0</h4>
                                    <small class="text-success">(Active)</small>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-building-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Budget</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$0</h4>
                                    <small class="text-success">(+0%)</small>
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

            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Spent</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$0</h4>
                                    <small class="text-danger">(0%)</small>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="ri-arrow-up-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Remaining</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$0</h4>
                                    <small class="text-success">(100%)</small>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-arrow-down-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Budgets -->
        <div class="row">
            <!-- Budget Distribution -->
            <div class="col-12 col-xl-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Budget Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div id="departmentDistributionChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Department List -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-6">
                                <h5 class="card-title mb-0">Departments</h5>
                            </div>
                            <div class="col-sm-6 text-end">
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-download-line"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Budget</th>
                                    <th>Spent</th>
                                    <th>Remaining</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments ?? [] as $department)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($department->name ?? 'IT', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('budgets.departments.show', $department ?? 1) }}">
                                                    {{ $department->name ?? 'IT Department' }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>${{ number_format($department->budget ?? 50000) }}</td>
                                        <td>${{ number_format($department->spent ?? 25000) }}</td>
                                        <td>${{ number_format($department->remaining ?? 25000) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress w-100 me-2" style="height: 8px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $department->progress ?? 50 }}%"></div>
                                                </div>
                                                <span>{{ $department->progress ?? 50 }}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-success">Active</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('budgets.departments.show', $department ?? 1) }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('budgets.create') }}">
                                                            <i class="ri-add-line me-1"></i> Add Budget
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="p-3">
                                                <i class="ri-building-line ri-3x text-primary mb-3"></i>
                                                <h5>No Departments Found</h5>
                                                <p class="mb-3">Start by adding departments and their budgets</p>
                                                <a href="{{ route('preset-departments.index') }}"
                                                    class="btn btn-primary">
                                                    <i class="ri-add-line"></i> Add Department
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Alerts -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Budget Alerts</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Alert Type</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alerts ?? [] as $alert)
                                    <tr>
                                        <td>{{ $alert->department->name ?? 'IT Department' }}</td>
                                        <td>
                                            <span class="badge bg-label-warning">Over Budget</span>
                                        </td>
                                        <td>Department has exceeded monthly budget by 10%</td>
                                        <td>{{ $alert->date ?? '2024-01-15' }}</td>
                                        <td>
                                            <span class="badge bg-label-danger">Unresolved</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item">
                                                            <i class="ri-check-line me-1"></i> Mark as Resolved
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item">
                                                            <i class="ri-notification-off-line me-1"></i> Dismiss
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No alerts found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Department Distribution Chart
            const departmentDistributionOptions = {
                series: [25000, 15000, 10000],
                chart: {
                    type: 'donut',
                    height: 400
                },
                labels: ['IT Department', 'Marketing', 'Sales'],
                colors: ['#696cff', '#03c3ec', '#71dd37'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Budget',
                                    formatter: function(w) {
                                        return '$' + w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                }
            };

            const departmentDistributionChart = new ApexCharts(
                document.querySelector("#departmentDistributionChart"),
                departmentDistributionOptions
            );
            departmentDistributionChart.render();
        });
    </script>
@endsection

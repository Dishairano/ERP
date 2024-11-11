@extends('layouts/contentNavbarLayout')

@section('title', 'Budget Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Budget Management</h4>
                                <p class="mb-0">Manage and track your organization's budgets</p>
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
                                        <li><a class="dropdown-item" href="{{ route('budgets.departments') }}">Department
                                                Budgets</a></li>
                                        <li><a class="dropdown-item" href="{{ route('budgets.projects') }}">Project
                                                Budgets</a></li>
                                        <li><a class="dropdown-item" href="{{ route('budgets.scenarios') }}">Budget
                                                Scenarios</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
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

        <!-- Budget Summary Cards -->
        <div class="row">
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
                                <small>vs previous year</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
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
                                <small>of total budget</small>
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
                                <small>available to spend</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-arrow-down-circle-line"></i>
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
                                <p class="card-text">Forecasted</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$0</h4>
                                    <small class="text-warning">(0%)</small>
                                </div>
                                <small>year-end projection</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-line-chart-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Overview -->
        <div class="row">
            <!-- Budget vs Actual Chart -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Budget vs Actual</h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                2024
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">2024</a></li>
                                <li><a class="dropdown-item" href="#">2023</a></li>
                                <li><a class="dropdown-item" href="#">2022</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="budgetVsActualChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Department Distribution -->
            <div class="col-12 col-xl-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Department Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div id="departmentDistributionChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-6">
                                <h5 class="card-title mb-0">Active Budgets</h5>
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
                                    <th>Budget Name</th>
                                    <th>Type</th>
                                    <th>Period</th>
                                    <th>Total Amount</th>
                                    <th>Spent</th>
                                    <th>Remaining</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($budgets ?? [] as $budget)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($budget->name ?? 'B', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('budgets.show', $budget ?? 1) }}">
                                                    {{ $budget->name ?? 'Annual Budget 2024' }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $budget->type ?? 'Department' }}</td>
                                        <td>{{ $budget->period ?? 'Jan 2024 - Dec 2024' }}</td>
                                        <td>${{ number_format($budget->total_amount ?? 0) }}</td>
                                        <td>${{ number_format($budget->spent_amount ?? 0) }}</td>
                                        <td>${{ number_format($budget->remaining_amount ?? 0) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress w-100 me-2" style="height: 8px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $budget->progress ?? 0 }}%"></div>
                                                </div>
                                                <span>{{ $budget->progress ?? 0 }}%</span>
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
                                                            href="{{ route('budgets.show', $budget ?? 1) }}">
                                                            <i class="ri-eye-line me-1"></i> View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('budgets.edit', $budget ?? 1) }}">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('budgets.destroy', $budget ?? 1) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ri-delete-bin-line me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="p-3">
                                                <i class="ri-money-dollar-circle-line ri-3x text-primary mb-3"></i>
                                                <h5>No Budgets Found</h5>
                                                <p class="mb-3">Start managing your finances by creating a budget</p>
                                                <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                                                    <i class="ri-add-line"></i> Create Budget
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <select class="form-select form-select-sm" style="width: 80px">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Budget vs Actual Chart
            const budgetVsActualOptions = {
                series: [{
                    name: 'Budget',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                }, {
                    name: 'Actual',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                }],
                chart: {
                    type: 'bar',
                    height: 400,
                    stacked: false,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
                },
                yaxis: {
                    title: {
                        text: 'Amount ($)'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "$ " + val
                        }
                    }
                },
                colors: ['#696cff', '#03c3ec']
            };

            const budgetVsActualChart = new ApexCharts(
                document.querySelector("#budgetVsActualChart"),
                budgetVsActualOptions
            );
            budgetVsActualChart.render();

            // Department Distribution Chart
            const departmentDistributionOptions = {
                series: [0, 0, 0, 0],
                chart: {
                    type: 'donut',
                    height: 400
                },
                labels: ['IT', 'Marketing', 'Sales', 'Operations'],
                colors: ['#696cff', '#03c3ec', '#71dd37', '#ffab00'],
                legend: {
                    position: 'bottom'
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

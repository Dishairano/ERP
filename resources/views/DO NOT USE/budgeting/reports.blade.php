@extends('layouts/contentNavbarLayout')

@section('title', 'Budget Reports')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Budget Reports</h4>
                                <p class="mb-0">Generate and analyze budget reports</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#generateReportModal">
                                        <i class="ri-file-chart-line"></i> Generate Report
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="ri-download-line me-1"></i>
                                                Export All</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-printer-line me-1"></i>
                                                Print</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label">Report Type</label>
                                <select class="form-select" id="reportType">
                                    <option value="summary">Summary Report</option>
                                    <option value="detailed">Detailed Report</option>
                                    <option value="variance">Variance Analysis</option>
                                    <option value="trend">Trend Analysis</option>
                                    <option value="forecast">Budget Forecast</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Department</label>
                                <select class="form-select">
                                    <option value="">All Departments</option>
                                    <!-- Departments will be populated here -->
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label">Period</label>
                                <select class="form-select">
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label">Year</label>
                                <select class="form-select">
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-3-line"></i> Apply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="card-info">
                                        <p class="card-text">Total Budget</p>
                                        <div class="d-flex align-items-end mb-2">
                                            <h4 class="card-title mb-0 me-2">$100,000</h4>
                                            <small class="text-success">(+5%)</small>
                                        </div>
                                        <small>vs previous period</small>
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

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="card-info">
                                        <p class="card-text">Actual Spend</p>
                                        <div class="d-flex align-items-end mb-2">
                                            <h4 class="card-title mb-0 me-2">$45,000</h4>
                                            <small class="text-danger">(+8%)</small>
                                        </div>
                                        <small>vs previous period</small>
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

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="card-info">
                                        <p class="card-text">Variance</p>
                                        <div class="d-flex align-items-end mb-2">
                                            <h4 class="card-title mb-0 me-2">$5,000</h4>
                                            <small class="text-warning">(+2%)</small>
                                        </div>
                                        <small>vs budget</small>
                                    </div>
                                    <div class="card-icon">
                                        <span class="badge bg-label-warning rounded p-2">
                                            <i class="ri-contrast-2-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="card-info">
                                        <p class="card-text">Forecast</p>
                                        <div class="d-flex align-items-end mb-2">
                                            <h4 class="card-title mb-0 me-2">$95,000</h4>
                                            <small class="text-success">(-5%)</small>
                                        </div>
                                        <small>vs budget</small>
                                    </div>
                                    <div class="card-icon">
                                        <span class="badge bg-label-info rounded p-2">
                                            <i class="ri-line-chart-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget vs Actual Trend -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Budget vs Actual Trend</h5>
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

        <!-- Detailed Reports -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Detailed Reports</h5>
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="ri-download-line"></i> Export
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Budget</th>
                                    <th>Actual</th>
                                    <th>Variance</th>
                                    <th>Variance %</th>
                                    <th>YTD Budget</th>
                                    <th>YTD Actual</th>
                                    <th>YTD Variance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports ?? [] as $report)
                                    <tr>
                                        <td>{{ $report->department ?? 'IT Department' }}</td>
                                        <td>${{ number_format($report->budget ?? 50000) }}</td>
                                        <td>${{ number_format($report->actual ?? 45000) }}</td>
                                        <td>${{ number_format($report->variance ?? 5000) }}</td>
                                        <td>
                                            <span class="badge bg-label-success">+10%</span>
                                        </td>
                                        <td>${{ number_format($report->ytd_budget ?? 150000) }}</td>
                                        <td>${{ number_format($report->ytd_actual ?? 140000) }}</td>
                                        <td>${{ number_format($report->ytd_variance ?? 10000) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-download-line me-1"></i> Export
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No reports available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Report Modal -->
    <div class="modal fade" id="generateReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('budgets.reports.generate') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Report Type</label>
                            <select class="form-select" name="report_type" required>
                                <option value="summary">Summary Report</option>
                                <option value="detailed">Detailed Report</option>
                                <option value="variance">Variance Analysis</option>
                                <option value="trend">Trend Analysis</option>
                                <option value="forecast">Budget Forecast</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department_id">
                                <option value="">All Departments</option>
                                <!-- Departments will be populated here -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" name="date_range" required>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_quarter">This Quarter</option>
                                <option value="last_quarter">Last Quarter</option>
                                <option value="this_year">This Year</option>
                                <option value="last_year">Last Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="mb-3 custom-date-range d-none">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Format</label>
                            <select class="form-select" name="format" required>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </div>
                </form>
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
                    data: [10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000,
                        10000
                    ]
                }, {
                    name: 'Actual',
                    data: [8500, 9200, 9800, 8900, 9000, 8700, 0, 0, 0, 0, 0, 0]
                }],
                chart: {
                    type: 'line',
                    height: 400,
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: [2, 2],
                    dashArray: [0, 0]
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ]
                },
                yaxis: {
                    title: {
                        text: 'Amount ($)'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return '$ ' + val;
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

            // Handle custom date range visibility
            const dateRangeSelect = document.querySelector('select[name="date_range"]');
            const customDateRange = document.querySelector('.custom-date-range');

            dateRangeSelect.addEventListener('change', function() {
                customDateRange.classList.toggle('d-none', this.value !== 'custom');
            });
        });
    </script>
@endsection

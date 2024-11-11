@extends('layouts/contentNavbarLayout')

@section('title', 'Cash Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Cash Management</h4>
                                <p class="mb-0">Monitor and manage cash flow, forecasting, and liquidity</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createForecastModal">
                                        <i class="ri-line-chart-line"></i> New Forecast
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#transferFundsModal"><i class="ri-exchange-line me-1"></i>
                                                Transfer Funds</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-file-chart-line me-1"></i>
                                                Generate Report</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-settings-line me-1"></i>
                                                Settings</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash Position Summary -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Current Cash Position</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$500,000</h4>
                                    <small class="text-success">(+8%)</small>
                                </div>
                                <small>Total available cash</small>
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
                                <p class="card-text">Projected Inflows</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$150,000</h4>
                                    <small class="text-success">Next 30 days</small>
                                </div>
                                <small>Expected receipts</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-arrow-right-circle-line"></i>
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
                                <p class="card-text">Projected Outflows</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$100,000</h4>
                                    <small class="text-danger">Next 30 days</small>
                                </div>
                                <small>Expected payments</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="ri-arrow-left-circle-line"></i>
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
                                <p class="card-text">Liquidity Ratio</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">2.5</h4>
                                    <small class="text-success">(Healthy)</small>
                                </div>
                                <small>Current ratio</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-scales-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash Flow Forecast -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Cash Flow Forecast</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm">
                                <i class="ri-refresh-line"></i> Update Forecast
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="cashFlowForecastChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Cash Flows -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Upcoming Inflows</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Probability</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inflows ?? [] as $inflow)
                                    <tr>
                                        <td>{{ $inflow->date ?? '2024-01-20' }}</td>
                                        <td>{{ $inflow->description ?? 'Customer Payment' }}</td>
                                        <td>${{ number_format($inflow->amount ?? 25000, 2) }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 90%"></div>
                                            </div>
                                            <small>90%</small>
                                        </td>
                                        <td><span class="badge bg-label-success">Confirmed</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No upcoming inflows</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Upcoming Outflows</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outflows ?? [] as $outflow)
                                    <tr>
                                        <td>{{ $outflow->date ?? '2024-01-25' }}</td>
                                        <td>{{ $outflow->description ?? 'Vendor Payment' }}</td>
                                        <td>${{ number_format($outflow->amount ?? 15000, 2) }}</td>
                                        <td><span class="badge bg-label-danger">High</span></td>
                                        <td><span class="badge bg-label-warning">Pending</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No upcoming outflows</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liquidity Analysis -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Liquidity Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Current Ratio</h6>
                                    <span class="badge bg-label-success">2.5</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 83%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Quick Ratio</h6>
                                    <span class="badge bg-label-info">1.8</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 60%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Cash Ratio</h6>
                                    <span class="badge bg-label-primary">0.8</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 40%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Working Capital</h6>
                                    <span class="badge bg-label-warning">$400,000</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Forecast Modal -->
    <div class="modal fade" id="createForecastModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Cash Flow Forecast</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('treasury.cash-management.forecasts.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Forecast Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Forecast Method</label>
                                <select class="form-select" name="method" required>
                                    <option value="historical">Historical Data</option>
                                    <option value="trend">Trend Analysis</option>
                                    <option value="manual">Manual Entry</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Include Categories</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="categories[]"
                                                value="sales" checked>
                                            <label class="form-check-label">Sales Revenue</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="categories[]"
                                                value="receivables" checked>
                                            <label class="form-check-label">Accounts Receivable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="categories[]"
                                                value="payables" checked>
                                            <label class="form-check-label">Accounts Payable</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="categories[]"
                                                value="expenses" checked>
                                            <label class="form-check-label">Operating Expenses</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Forecast</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transfer Funds Modal -->
    <div class="modal fade" id="transferFundsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Funds</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('treasury.cash-management.transfers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">From Account</label>
                                <select class="form-select" name="from_account_id" required>
                                    <option value="">Select Account</option>
                                    <!-- Accounts will be populated here -->
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">To Account</label>
                                <select class="form-select" name="to_account_id" required>
                                    <option value="">Select Account</option>
                                    <!-- Accounts will be populated here -->
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="amount" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Transfer Date</label>
                                <input type="date" class="form-control" name="transfer_date" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Reference</label>
                                <input type="text" class="form-control" name="reference">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Transfer Funds</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Cash Flow Forecast Chart
            const cashFlowForecastOptions = {
                series: [{
                    name: 'Inflows',
                    data: [30000, 40000, 35000, 50000, 49000, 60000, 70000, 91000, 125000]
                }, {
                    name: 'Outflows',
                    data: [20000, 35000, 25000, 45000, 42000, 55000, 65000, 85000, 110000]
                }, {
                    name: 'Net Position',
                    data: [10000, 15000, 25000, 30000, 37000, 42000, 47000, 53000, 68000]
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
                    width: [2, 2, 4],
                    dashArray: [0, 0, 0]
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
                },
                yaxis: {
                    title: {
                        text: 'Amount ($)'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return '$ ' + val.toLocaleString();
                        }
                    }
                },
                colors: ['#696cff', '#ff6b6b', '#03c3ec'],
                legend: {
                    position: 'top'
                }
            };

            const cashFlowForecastChart = new ApexCharts(
                document.querySelector("#cashFlowForecastChart"),
                cashFlowForecastOptions
            );
            cashFlowForecastChart.render();
        });
    </script>
@endsection

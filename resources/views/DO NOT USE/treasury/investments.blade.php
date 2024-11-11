@extends('layouts/contentNavbarLayout')

@section('title', 'Investment Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Investment Management</h4>
                                <p class="mb-0">Manage investment portfolio and track performance</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#newInvestmentModal">
                                        <i class="ri-add-line"></i> New Investment
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#rebalancePortfolioModal"><i
                                                    class="ri-refresh-line me-1"></i> Rebalance Portfolio</a></li>
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

        <!-- Portfolio Summary -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Portfolio Value</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$1,500,000</h4>
                                    <small class="text-success">(+12%)</small>
                                </div>
                                <small>Market value</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-funds-line"></i>
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
                                <p class="card-text">Total Return</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$180,000</h4>
                                    <small class="text-success">(+15%)</small>
                                </div>
                                <small>YTD return</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-line-chart-line"></i>
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
                                <p class="card-text">Dividend Income</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$45,000</h4>
                                    <small class="text-success">(+8%)</small>
                                </div>
                                <small>Annual income</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
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
                                <p class="card-text">Risk Score</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">3.5</h4>
                                    <small class="text-warning">(Moderate)</small>
                                </div>
                                <small>Portfolio risk</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-shield-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset Allocation -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Portfolio Performance</h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary active">1M</button>
                            <button type="button" class="btn btn-outline-primary">3M</button>
                            <button type="button" class="btn btn-outline-primary">6M</button>
                            <button type="button" class="btn btn-outline-primary">1Y</button>
                            <button type="button" class="btn btn-outline-primary">ALL</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="portfolioPerformanceChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Asset Allocation</h5>
                    </div>
                    <div class="card-body">
                        <div id="assetAllocationChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Investment Holdings -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Investment Holdings</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Cost Basis</th>
                                    <th>Market Value</th>
                                    <th>Gain/Loss</th>
                                    <th>Return</th>
                                    <th>Weight</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($investments ?? [] as $investment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($investment->symbol ?? 'A', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <a href="#"
                                                        class="text-body fw-bold">{{ $investment->name ?? 'Apple Inc.' }}</a>
                                                    <br>
                                                    <small class="text-muted">{{ $investment->symbol ?? 'AAPL' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $investment->type ?? 'Stock' }}</td>
                                        <td>{{ $investment->quantity ?? 100 }}</td>
                                        <td>${{ number_format($investment->cost_basis ?? 15000, 2) }}</td>
                                        <td>${{ number_format($investment->market_value ?? 18000, 2) }}</td>
                                        <td class="text-success">+${{ number_format($investment->gain_loss ?? 3000, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-label-success">+20%</span>
                                        </td>
                                        <td>12%</td>
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
                                                            <i class="ri-add-circle-line me-1"></i> Buy More
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-subtract-line me-1"></i> Sell
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#">
                                                            <i class="ri-delete-bin-line me-1"></i> Remove
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No investments found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Investment Modal -->
    <div class="modal fade" id="newInvestmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Investment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('treasury.investments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Asset Type</label>
                                <select class="form-select" name="asset_type" required>
                                    <option value="">Select Type</option>
                                    <option value="stock">Stock</option>
                                    <option value="bond">Bond</option>
                                    <option value="mutual_fund">Mutual Fund</option>
                                    <option value="etf">ETF</option>
                                    <option value="real_estate">Real Estate</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Symbol/Identifier</label>
                                <input type="text" class="form-control" name="symbol" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" step="0.01" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Purchase Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="price" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" name="purchase_date" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Investment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rebalance Portfolio Modal -->
    <div class="modal fade" id="rebalancePortfolioModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rebalance Portfolio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('treasury.investments.rebalance') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Target Allocation</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Asset Class</th>
                                                <th>Current %</th>
                                                <th>Target %</th>
                                                <th>Difference</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Stocks</td>
                                                <td>65%</td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="stocks_target" value="60" min="0"
                                                        max="100">
                                                </td>
                                                <td class="text-danger">+5%</td>
                                            </tr>
                                            <tr>
                                                <td>Bonds</td>
                                                <td>25%</td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="bonds_target" value="30" min="0"
                                                        max="100">
                                                </td>
                                                <td class="text-danger">-5%</td>
                                            </tr>
                                            <tr>
                                                <td>Cash</td>
                                                <td>10%</td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="cash_target" value="10" min="0" max="100">
                                                </td>
                                                <td>0%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Rebalancing Method</label>
                                <select class="form-select" name="rebalancing_method" required>
                                    <option value="threshold">Threshold-based</option>
                                    <option value="calendar">Calendar-based</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Rebalance Portfolio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Portfolio Performance Chart
            const portfolioPerformanceOptions = {
                series: [{
                    name: 'Portfolio Value',
                    data: [1300000, 1350000, 1400000, 1380000, 1420000, 1460000, 1500000]
                }, {
                    name: 'Benchmark',
                    data: [1300000, 1320000, 1360000, 1350000, 1390000, 1420000, 1450000]
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
                    width: [2, 2]
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
                },
                yaxis: {
                    title: {
                        text: 'Value ($)'
                    },
                    labels: {
                        formatter: function(val) {
                            return '$' + (val / 1000000).toFixed(2) + 'M';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return '$' + val.toLocaleString();
                        }
                    }
                },
                colors: ['#696cff', '#03c3ec']
            };

            const portfolioPerformanceChart = new ApexCharts(
                document.querySelector("#portfolioPerformanceChart"),
                portfolioPerformanceOptions
            );
            portfolioPerformanceChart.render();

            // Asset Allocation Chart
            const assetAllocationOptions = {
                series: [60, 30, 10],
                chart: {
                    type: 'donut',
                    height: 400
                },
                labels: ['Stocks', 'Bonds', 'Cash'],
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
                                    label: 'Total Value',
                                    formatter: function(w) {
                                        return '$' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) /
                                            1000000).toFixed(2) + 'M';
                                    }
                                }
                            }
                        }
                    }
                }
            };

            const assetAllocationChart = new ApexCharts(
                document.querySelector("#assetAllocationChart"),
                assetAllocationOptions
            );
            assetAllocationChart.render();
        });
    </script>
@endsection

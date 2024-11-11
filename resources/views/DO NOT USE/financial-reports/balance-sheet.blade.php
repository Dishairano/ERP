@extends('layouts/contentNavbarLayout')

@section('title', 'Balance Sheet')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Balance Sheet</h4>
                                <p class="mb-0">View company assets, liabilities, and equity</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="ri-download-line"></i> Export
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="ri-file-pdf-line me-1"></i>
                                                PDF</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-file-excel-line me-1"></i>
                                                Excel</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
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

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label">As of Date</label>
                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Comparison</label>
                                <select class="form-select">
                                    <option value="none">No Comparison</option>
                                    <option value="previous_month">Previous Month</option>
                                    <option value="previous_quarter">Previous Quarter</option>
                                    <option value="previous_year">Previous Year</option>
                                    <option value="custom">Custom Date</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Display Level</label>
                                <select class="form-select">
                                    <option value="summary">Summary</option>
                                    <option value="detailed">Detailed</option>
                                    <option value="all">All Accounts</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-refresh-line"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Sheet Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="card-title mb-0">Balance Sheet</h5>
                                <small class="text-muted">As of {{ date('F d, Y') }}</small>
                            </div>
                            <div class="col text-end">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary active">Amount</button>
                                    <button type="button" class="btn btn-outline-primary">Percentage</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <!-- Assets -->
                                    <tr class="table-light">
                                        <th colspan="2">ASSETS</th>
                                    </tr>

                                    <!-- Current Assets -->
                                    <tr>
                                        <td class="ps-4"><strong>Current Assets</strong></td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Cash and Cash Equivalents</td>
                                        <td class="text-end">$100,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Accounts Receivable</td>
                                        <td class="text-end">$75,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Inventory</td>
                                        <td class="text-end">$50,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Prepaid Expenses</td>
                                        <td class="text-end">$25,000</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td class="ps-4"><strong>Total Current Assets</strong></td>
                                        <td class="text-end"><strong>$250,000</strong></td>
                                    </tr>

                                    <!-- Non-Current Assets -->
                                    <tr>
                                        <td class="ps-4"><strong>Non-Current Assets</strong></td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Property, Plant and Equipment</td>
                                        <td class="text-end">$500,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Intangible Assets</td>
                                        <td class="text-end">$100,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Long-term Investments</td>
                                        <td class="text-end">$150,000</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td class="ps-4"><strong>Total Non-Current Assets</strong></td>
                                        <td class="text-end"><strong>$750,000</strong></td>
                                    </tr>

                                    <tr class="table-primary">
                                        <th class="ps-3">TOTAL ASSETS</th>
                                        <th class="text-end">$1,000,000</th>
                                    </tr>

                                    <!-- Liabilities -->
                                    <tr class="table-light">
                                        <th colspan="2">LIABILITIES</th>
                                    </tr>

                                    <!-- Current Liabilities -->
                                    <tr>
                                        <td class="ps-4"><strong>Current Liabilities</strong></td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Accounts Payable</td>
                                        <td class="text-end">$50,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Short-term Loans</td>
                                        <td class="text-end">$75,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Current Tax Liabilities</td>
                                        <td class="text-end">$25,000</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td class="ps-4"><strong>Total Current Liabilities</strong></td>
                                        <td class="text-end"><strong>$150,000</strong></td>
                                    </tr>

                                    <!-- Non-Current Liabilities -->
                                    <tr>
                                        <td class="ps-4"><strong>Non-Current Liabilities</strong></td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Long-term Loans</td>
                                        <td class="text-end">$200,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Deferred Tax Liabilities</td>
                                        <td class="text-end">$50,000</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td class="ps-4"><strong>Total Non-Current Liabilities</strong></td>
                                        <td class="text-end"><strong>$250,000</strong></td>
                                    </tr>

                                    <tr class="table-primary">
                                        <th class="ps-3">TOTAL LIABILITIES</th>
                                        <th class="text-end">$400,000</th>
                                    </tr>

                                    <!-- Equity -->
                                    <tr class="table-light">
                                        <th colspan="2">EQUITY</th>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Share Capital</td>
                                        <td class="text-end">$400,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Retained Earnings</td>
                                        <td class="text-end">$200,000</td>
                                    </tr>

                                    <tr class="table-primary">
                                        <th class="ps-3">TOTAL EQUITY</th>
                                        <th class="text-end">$600,000</th>
                                    </tr>

                                    <tr class="table-dark">
                                        <th class="ps-3">TOTAL LIABILITIES AND EQUITY</th>
                                        <th class="text-end">$1,000,000</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="ri-information-line"></i> All amounts are in USD
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    Last updated: {{ date('M d, Y H:i:s') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Ratios -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Financial Ratios</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Current Ratio</h6>
                                    <span class="badge bg-label-info">1.67</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 83.5%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Debt to Equity</h6>
                                    <span class="badge bg-label-warning">0.67</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 66.7%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Working Capital</h6>
                                    <span class="badge bg-label-success">$100,000</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Asset Turnover</h6>
                                    <span class="badge bg-label-primary">1.2</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 60%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading fw-bold mb-1">Important Notes:</h6>
                            <ul class="mb-0">
                                <li>All figures are presented in accordance with IFRS standards</li>
                                <li>The balance sheet is prepared on a historical cost basis</li>
                                <li>Foreign currency transactions are converted at the exchange rate on the reporting date
                                </li>
                                <li>Comparative figures for the previous period are available in the detailed report</li>
                            </ul>
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
            // Initialize any necessary JavaScript functionality
        });
    </script>
@endsection

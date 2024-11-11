@extends('layouts/contentNavbarLayout')

@section('title', 'Cash Flow Statement')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Cash Flow Statement</h4>
                                <p class="mb-0">View cash inflows and outflows from operating, investing, and financing
                                    activities</p>
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
                            <div class="col-12 col-md-3">
                                <label class="form-label">Period</label>
                                <select class="form-select">
                                    <option value="current_month">Current Month</option>
                                    <option value="current_quarter">Current Quarter</option>
                                    <option value="ytd">Year to Date</option>
                                    <option value="last_year">Last Year</option>
                                    <option value="custom">Custom Period</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Comparison</label>
                                <select class="form-select">
                                    <option value="none">No Comparison</option>
                                    <option value="previous_period">Previous Period</option>
                                    <option value="previous_year">Previous Year</option>
                                    <option value="budget">Budget</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Method</label>
                                <select class="form-select">
                                    <option value="indirect">Indirect Method</option>
                                    <option value="direct">Direct Method</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-refresh-line"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash Flow Summary Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Operating Activities</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$150,000</h4>
                                    <small class="text-success">(+15%)</small>
                                </div>
                                <small>Net cash from operations</small>
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
                                <p class="card-text">Investing Activities</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">($50,000)</h4>
                                    <small class="text-danger">(-25%)</small>
                                </div>
                                <small>Net cash from investing</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="ri-stock-line"></i>
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
                                <p class="card-text">Financing Activities</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">($20,000)</h4>
                                    <small class="text-warning">(-10%)</small>
                                </div>
                                <small>Net cash from financing</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-bank-line"></i>
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
                                <p class="card-text">Net Cash Flow</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$80,000</h4>
                                    <small class="text-success">(+5%)</small>
                                </div>
                                <small>Total net change</small>
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
        </div>

        <!-- Cash Flow Statement -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="card-title mb-0">Cash Flow Statement</h5>
                                <small class="text-muted">For the period ending {{ date('F d, Y') }}</small>
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
                                    <!-- Operating Activities -->
                                    <tr class="table-light">
                                        <th colspan="2">OPERATING ACTIVITIES</th>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Net Income</td>
                                        <td class="text-end">$100,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 text-muted">Adjustments for non-cash items:</td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Depreciation and Amortization</td>
                                        <td class="text-end">$30,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 text-muted">Changes in working capital:</td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Decrease in Accounts Receivable</td>
                                        <td class="text-end">$15,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Increase in Inventory</td>
                                        <td class="text-end">($10,000)</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5">Increase in Accounts Payable</td>
                                        <td class="text-end">$15,000</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <th class="ps-3">Net Cash from Operating Activities</th>
                                        <th class="text-end">$150,000</th>
                                    </tr>

                                    <!-- Investing Activities -->
                                    <tr class="table-light">
                                        <th colspan="2">INVESTING ACTIVITIES</th>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Purchase of Property and Equipment</td>
                                        <td class="text-end">($75,000)</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Sale of Investments</td>
                                        <td class="text-end">$25,000</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <th class="ps-3">Net Cash from Investing Activities</th>
                                        <th class="text-end">($50,000)</th>
                                    </tr>

                                    <!-- Financing Activities -->
                                    <tr class="table-light">
                                        <th colspan="2">FINANCING ACTIVITIES</th>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Proceeds from Long-term Debt</td>
                                        <td class="text-end">$50,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Repayment of Long-term Debt</td>
                                        <td class="text-end">($40,000)</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Dividends Paid</td>
                                        <td class="text-end">($30,000)</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <th class="ps-3">Net Cash from Financing Activities</th>
                                        <th class="text-end">($20,000)</th>
                                    </tr>

                                    <!-- Net Change in Cash -->
                                    <tr class="table-success">
                                        <th class="ps-3">Net Increase in Cash</th>
                                        <th class="text-end">$80,000</th>
                                    </tr>

                                    <!-- Beginning and Ending Cash -->
                                    <tr>
                                        <td class="ps-4">Cash at Beginning of Period</td>
                                        <td class="text-end">$100,000</td>
                                    </tr>
                                    <tr class="table-dark">
                                        <th class="ps-3">Cash at End of Period</th>
                                        <th class="text-end">$180,000</th>
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

        <!-- Cash Flow Analysis -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Cash Flow Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Operating Cash Flow Ratio</h6>
                                    <span class="badge bg-label-success">1.5</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Cash Flow Coverage Ratio</h6>
                                    <span class="badge bg-label-info">2.1</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 70%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Cash Flow to Debt Ratio</h6>
                                    <span class="badge bg-label-primary">0.8</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 40%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Free Cash Flow Yield</h6>
                                    <span class="badge bg-label-warning">5.2%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 52%"></div>
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
                                <li>This cash flow statement is prepared using the indirect method</li>
                                <li>Non-cash transactions are excluded from this statement</li>
                                <li>Changes in working capital are based on balance sheet comparisons</li>
                                <li>Significant non-cash investing and financing activities are disclosed separately</li>
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

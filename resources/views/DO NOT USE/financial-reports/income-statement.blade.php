@extends('layouts/contentNavbarLayout')

@section('title', 'Income Statement')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Income Statement</h4>
                                <p class="mb-0">View company revenues, costs, and expenses</p>
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
                                <label class="form-label">Display Level</label>
                                <select class="form-select">
                                    <option value="summary">Summary</option>
                                    <option value="detailed">Detailed</option>
                                    <option value="all">All Accounts</option>
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

        <!-- Income Statement Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="card-title mb-0">Income Statement</h5>
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
                                    <!-- Revenue -->
                                    <tr class="table-light">
                                        <th colspan="2">REVENUE</th>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Sales Revenue</td>
                                        <td class="text-end">$500,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Service Revenue</td>
                                        <td class="text-end">$200,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Other Revenue</td>
                                        <td class="text-end">$50,000</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <th class="ps-3">TOTAL REVENUE</th>
                                        <th class="text-end">$750,000</th>
                                    </tr>

                                    <!-- Cost of Sales -->
                                    <tr class="table-light">
                                        <th colspan="2">COST OF SALES</th>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Cost of Goods Sold</td>
                                        <td class="text-end">$300,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Direct Labor</td>
                                        <td class="text-end">$100,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Manufacturing Overhead</td>
                                        <td class="text-end">$50,000</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <th class="ps-3">TOTAL COST OF SALES</th>
                                        <th class="text-end">$450,000</th>
                                    </tr>

                                    <!-- Gross Profit -->
                                    <tr class="table-success">
                                        <th class="ps-3">GROSS PROFIT</th>
                                        <th class="text-end">$300,000</th>
                                    </tr>

                                    <!-- Operating Expenses -->
                                    <tr class="table-light">
                                        <th colspan="2">OPERATING EXPENSES</th>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Salaries and Wages</td>
                                        <td class="text-end">$100,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Rent and Utilities</td>
                                        <td class="text-end">$30,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Marketing and Advertising</td>
                                        <td class="text-end">$20,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Office Supplies</td>
                                        <td class="text-end">$5,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Insurance</td>
                                        <td class="text-end">$10,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Depreciation</td>
                                        <td class="text-end">$15,000</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <th class="ps-3">TOTAL OPERATING EXPENSES</th>
                                        <th class="text-end">$180,000</th>
                                    </tr>

                                    <!-- Operating Income -->
                                    <tr class="table-info">
                                        <th class="ps-3">OPERATING INCOME</th>
                                        <th class="text-end">$120,000</th>
                                    </tr>

                                    <!-- Other Income and Expenses -->
                                    <tr class="table-light">
                                        <th colspan="2">OTHER INCOME AND EXPENSES</th>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Interest Income</td>
                                        <td class="text-end">$5,000</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">Interest Expense</td>
                                        <td class="text-end">($10,000)</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <th class="ps-3">TOTAL OTHER INCOME AND EXPENSES</th>
                                        <th class="text-end">($5,000)</th>
                                    </tr>

                                    <!-- Income Before Tax -->
                                    <tr class="table-warning">
                                        <th class="ps-3">INCOME BEFORE TAX</th>
                                        <th class="text-end">$115,000</th>
                                    </tr>

                                    <!-- Income Tax -->
                                    <tr>
                                        <td class="ps-4">Income Tax</td>
                                        <td class="text-end">$28,750</td>
                                    </tr>

                                    <!-- Net Income -->
                                    <tr class="table-success">
                                        <th class="ps-3">NET INCOME</th>
                                        <th class="text-end">$86,250</th>
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

        <!-- Key Metrics -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Key Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Gross Profit Margin</h6>
                                    <span class="badge bg-label-success">40%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 40%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Operating Margin</h6>
                                    <span class="badge bg-label-info">16%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 16%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Net Profit Margin</h6>
                                    <span class="badge bg-label-primary">11.5%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 11.5%"></div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Operating Expense Ratio</h6>
                                    <span class="badge bg-label-warning">24%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 24%"></div>
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
                                <li>This income statement is prepared using the accrual basis of accounting</li>
                                <li>Revenue is recognized when earned, regardless of when cash is received</li>
                                <li>Expenses are recognized when incurred, regardless of when cash is paid</li>
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

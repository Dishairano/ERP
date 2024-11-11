@extends('layouts/contentNavbarLayout')

@section('title', 'Custom Financial Reports')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Custom Financial Reports</h4>
                                <p class="mb-0">Generate customized financial reports with selected metrics and comparisons
                                </p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" onclick="generateReport()">
                                        <i class="ri-file-chart-line"></i> Generate Report
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="ri-save-line me-1"></i> Save
                                                Template</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-history-line me-1"></i>
                                                Load Template</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-settings-line me-1"></i>
                                                Report Settings</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Builder -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Report Builder</h5>
                    </div>
                    <div class="card-body">
                        <form id="reportBuilderForm">
                            <!-- Report Settings -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Report Name</label>
                                    <input type="text" class="form-control" name="report_name"
                                        placeholder="Enter report name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Report Description</label>
                                    <input type="text" class="form-control" name="report_description"
                                        placeholder="Enter description">
                                </div>
                            </div>

                            <!-- Time Period -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="mb-3">Time Period</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Period Type</label>
                                    <select class="form-select" name="period_type">
                                        <option value="custom">Custom Range</option>
                                        <option value="month">Month</option>
                                        <option value="quarter">Quarter</option>
                                        <option value="year">Year</option>
                                        <option value="ytd">Year to Date</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date">
                                </div>
                            </div>

                            <!-- Comparison Options -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="mb-3">Comparison Options</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Compare With</label>
                                    <select class="form-select" name="comparison_type">
                                        <option value="none">No Comparison</option>
                                        <option value="previous_period">Previous Period</option>
                                        <option value="previous_year">Previous Year</option>
                                        <option value="budget">Budget</option>
                                        <option value="forecast">Forecast</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Show Variance As</label>
                                    <select class="form-select" name="variance_type">
                                        <option value="amount">Amount</option>
                                        <option value="percentage">Percentage</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Include Trend Analysis</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="include_trend">
                                        <label class="form-check-label">Show trends</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Metrics Selection -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="mb-3">Select Metrics</h6>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Financial Position</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="total_assets">
                                                <label class="form-check-label">Total Assets</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="total_liabilities">
                                                <label class="form-check-label">Total Liabilities</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="equity">
                                                <label class="form-check-label">Equity</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="working_capital">
                                                <label class="form-check-label">Working Capital</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Performance Metrics</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="revenue">
                                                <label class="form-check-label">Revenue</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="gross_profit">
                                                <label class="form-check-label">Gross Profit</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="operating_income">
                                                <label class="form-check-label">Operating Income</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="net_income">
                                                <label class="form-check-label">Net Income</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Cash Flow Metrics</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="operating_cash_flow">
                                                <label class="form-check-label">Operating Cash Flow</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="free_cash_flow">
                                                <label class="form-check-label">Free Cash Flow</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="cash_conversion">
                                                <label class="form-check-label">Cash Conversion Cycle</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="metrics[]"
                                                    value="cash_balance">
                                                <label class="form-check-label">Cash Balance</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Display Options -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="mb-3">Display Options</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Chart Type</label>
                                    <select class="form-select" name="chart_type">
                                        <option value="line">Line Chart</option>
                                        <option value="bar">Bar Chart</option>
                                        <option value="combo">Combination Chart</option>
                                        <option value="none">No Chart</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Table Format</label>
                                    <select class="form-select" name="table_format">
                                        <option value="standard">Standard</option>
                                        <option value="compact">Compact</option>
                                        <option value="detailed">Detailed</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Number Format</label>
                                    <select class="form-select" name="number_format">
                                        <option value="standard">Standard</option>
                                        <option value="thousands">In Thousands</option>
                                        <option value="millions">In Millions</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Additional Options -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="include_ratios">
                                                        <label class="form-check-label">Include Financial Ratios</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="include_notes">
                                                        <label class="form-check-label">Include Notes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="include_summary">
                                                        <label class="form-check-label">Include Executive Summary</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="auto_schedule">
                                                        <label class="form-check-label">Schedule Auto-Generation</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-label-secondary me-2">Reset</button>
                        <button type="button" class="btn btn-primary" onclick="generateReport()">Generate
                            Report</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Preview -->
        <div class="row mt-4 d-none" id="reportPreview">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Report Preview</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm">
                                <i class="ri-download-line"></i> Export
                            </button>
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="ri-file-pdf-line me-1"></i> PDF</a>
                                </li>
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
                    <div class="card-body">
                        <!-- Report content will be dynamically loaded here -->
                        <div id="reportContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        function generateReport() {
            // Get form data
            const formData = new FormData(document.getElementById('reportBuilderForm'));

            // Show preview section
            document.getElementById('reportPreview').classList.remove('d-none');

            // TODO: Make API call to generate report
            // For now, just scroll to preview section
            document.getElementById('reportPreview').scrollIntoView({
                behavior: 'smooth'
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any necessary JavaScript functionality

            // Handle period type changes
            const periodTypeSelect = document.querySelector('select[name="period_type"]');
            const startDateInput = document.querySelector('input[name="start_date"]');
            const endDateInput = document.querySelector('input[name="end_date"]');

            periodTypeSelect.addEventListener('change', function() {
                const isCustom = this.value === 'custom';
                startDateInput.disabled = !isCustom;
                endDateInput.disabled = !isCustom;
            });
        });
    </script>
@endsection

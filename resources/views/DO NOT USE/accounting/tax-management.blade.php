@extends('layouts/contentNavbarLayout')

@section('title', 'Tax Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Tax Management</h4>
                                <p class="mb-0">Manage tax calculations, filings, and compliance</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createTaxEntryModal">
                                        <i class="ri-add-line"></i> New Tax Entry
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#scheduleTaxFilingModal"><i
                                                    class="ri-calendar-line me-1"></i> Schedule Filing</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-file-chart-line me-1"></i>
                                                Generate Report</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-download-line me-1"></i>
                                                Export</a></li>
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

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Tax Liability</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$25,000</h4>
                                    <small class="text-danger">(+8%)</small>
                                </div>
                                <small>Current period</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
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
                                <p class="card-text">Tax Paid</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$20,000</h4>
                                    <small class="text-success">(80%)</small>
                                </div>
                                <small>of total liability</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-check-double-line"></i>
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
                                <p class="card-text">Due This Month</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$5,000</h4>
                                    <small class="text-warning">(20%)</small>
                                </div>
                                <small>remaining balance</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-timer-line"></i>
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
                                <p class="card-text">Upcoming Filings</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">3</h4>
                                    <small class="text-info">Due soon</small>
                                </div>
                                <small>next 30 days</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-calendar-check-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Calendar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tax Calendar</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#scheduleTaxFilingModal">
                            <i class="ri-calendar-line"></i> Add Filing
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tax Type</th>
                                        <th>Filing Period</th>
                                        <th>Due Date</th>
                                        <th>Amount Due</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($taxFilings ?? [] as $filing)
                                        <tr>
                                            <td>{{ $filing->type ?? 'VAT' }}</td>
                                            <td>{{ $filing->period ?? 'Q1 2024' }}</td>
                                            <td>{{ $filing->due_date ?? '2024-04-30' }}</td>
                                            <td>${{ number_format($filing->amount ?? 5000, 2) }}</td>
                                            <td>
                                                <span class="badge bg-label-warning">Pending</span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm p-0" type="button"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button type="button" class="dropdown-item"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#recordTaxPaymentModal">
                                                                <i class="ri-money-dollar-circle-line me-1"></i> Record
                                                                Payment
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="dropdown-item">
                                                                <i class="ri-file-line me-1"></i> View Details
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="dropdown-item">
                                                                <i class="ri-download-line me-1"></i> Download Forms
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No upcoming tax filings</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Rates -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tax Rates</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addTaxRateModal">
                            <i class="ri-add-line"></i> Add Rate
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tax Type</th>
                                    <th>Rate (%)</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxRates ?? [] as $rate)
                                    <tr>
                                        <td>{{ $rate->type ?? 'VAT' }}</td>
                                        <td>{{ $rate->rate ?? '20' }}%</td>
                                        <td>{{ $rate->effective_from ?? '2024-01-01' }}</td>
                                        <td>{{ $rate->effective_to ?? null ?: 'Present' }}</td>
                                        <td>{{ $rate->description ?? 'Standard Rate' }}</td>
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
                                                        <button type="button" class="dropdown-item">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item">
                                                            <i class="ri-history-line me-1"></i> View History
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No tax rates defined</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Reports -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tax Reports</h5>
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="ri-download-line"></i> Export Reports
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Period</th>
                                    <th>Generated Date</th>
                                    <th>Generated By</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxReports ?? [] as $report)
                                    <tr>
                                        <td>{{ $report->name ?? 'VAT Return Q1 2024' }}</td>
                                        <td>{{ $report->period ?? 'Q1 2024' }}</td>
                                        <td>{{ $report->generated_date ?? '2024-04-15' }}</td>
                                        <td>{{ $report->generated_by ?? 'John Doe' }}</td>
                                        <td>
                                            <span class="badge bg-label-success">Filed</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item">
                                                            <i class="ri-eye-line me-1"></i> View
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item">
                                                            <i class="ri-download-line me-1"></i> Download
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item">
                                                            <i class="ri-mail-send-line me-1"></i> Send
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No tax reports available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Tax Filing Modal -->
    <div class="modal fade" id="scheduleTaxFilingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Tax Filing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounting.tax-management.filings.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Tax Type</label>
                                <select class="form-select" name="tax_type" required>
                                    <option value="">Select Tax Type</option>
                                    <option value="vat">VAT</option>
                                    <option value="income">Income Tax</option>
                                    <option value="payroll">Payroll Tax</option>
                                    <option value="corporate">Corporate Tax</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Filing Period</label>
                                <select class="form-select" name="period" required>
                                    <option value="">Select Period</option>
                                    <option value="q1_2024">Q1 2024</option>
                                    <option value="q2_2024">Q2 2024</option>
                                    <option value="q3_2024">Q3 2024</option>
                                    <option value="q4_2024">Q4 2024</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Due Date</label>
                                <input type="date" class="form-control" name="due_date" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Estimated Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="estimated_amount" step="0.01"
                                        min="0">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="set_reminder" id="setReminder"
                                        checked>
                                    <label class="form-check-label" for="setReminder">
                                        Set reminder for this filing
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Schedule Filing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Tax Rate Modal -->
    <div class="modal fade" id="addTaxRateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Tax Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounting.tax-management.rates.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Tax Type</label>
                                <select class="form-select" name="tax_type" required>
                                    <option value="">Select Tax Type</option>
                                    <option value="vat">VAT</option>
                                    <option value="income">Income Tax</option>
                                    <option value="payroll">Payroll Tax</option>
                                    <option value="corporate">Corporate Tax</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rate (%)</label>
                                <input type="number" class="form-control" name="rate" step="0.01" min="0"
                                    max="100" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Effective From</label>
                                <input type="date" class="form-control" name="effective_from" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="isDefault">
                                    <label class="form-check-label" for="isDefault">
                                        Set as default rate for this tax type
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Rate</button>
                    </div>
                </form>
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

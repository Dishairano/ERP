@extends('layouts/contentNavbarLayout')

@section('title', 'General Ledger')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">General Ledger</h4>
                                <p class="mb-0">View and manage general ledger entries</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createEntryModal">
                                        <i class="ri-add-line"></i> New Entry
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="ri-download-line me-1"></i>
                                                Export</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-printer-line me-1"></i>
                                                Print</a></li>
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

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label">Account</label>
                                <select class="form-select">
                                    <option value="">All Accounts</option>
                                    <!-- Accounts will be populated here -->
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label">Date Range</label>
                                <select class="form-select">
                                    <option value="today">Today</option>
                                    <option value="this_week">This Week</option>
                                    <option value="this_month">This Month</option>
                                    <option value="this_quarter">This Quarter</option>
                                    <option value="this_year">This Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label">Entry Type</label>
                                <select class="form-select">
                                    <option value="">All Types</option>
                                    <option value="debit">Debit</option>
                                    <option value="credit">Credit</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" placeholder="Search entries...">
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-3-line"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Summary -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Debits</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$50,000</h4>
                                    <small class="text-success">(+5%)</small>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
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
                                <p class="card-text">Total Credits</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$45,000</h4>
                                    <small class="text-danger">(-3%)</small>
                                </div>
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
                                <p class="card-text">Net Balance</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$5,000</h4>
                                    <small class="text-success">(+2%)</small>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-scales-line"></i>
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
                                <p class="card-text">Total Entries</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">150</h4>
                                    <small class="text-success">(+10%)</small>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-file-list-3-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger Entries -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Ledger Entries</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Account</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries ?? [] as $entry)
                                    <tr>
                                        <td>{{ $entry->date ?? '2024-01-15' }}</td>
                                        <td>{{ $entry->reference ?? 'GL-001' }}</td>
                                        <td>{{ $entry->account ?? 'Cash Account' }}</td>
                                        <td>{{ $entry->description ?? 'Initial deposit' }}</td>
                                        <td>${{ number_format($entry->debit ?? 1000, 2) }}</td>
                                        <td>${{ number_format($entry->credit ?? 0, 2) }}</td>
                                        <td>${{ number_format($entry->balance ?? 1000, 2) }}</td>
                                        <td>
                                            <span class="badge bg-label-success">Posted</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            data-bs-toggle="modal" data-bs-target="#editEntryModal">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item">
                                                            <i class="ri-file-copy-line me-1"></i> Duplicate
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No entries found</td>
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

    <!-- Create Entry Modal -->
    <div class="modal fade" id="createEntryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Ledger Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounting.general-ledger.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Reference</label>
                                <input type="text" class="form-control" name="reference" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>

                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="entryLines">
                                            <tr>
                                                <td>
                                                    <select class="form-select" name="accounts[]" required>
                                                        <option value="">Select Account</option>
                                                        <!-- Accounts will be populated here -->
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control debit-amount"
                                                        name="debits[]" step="0.01" min="0">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control credit-amount"
                                                        name="credits[]" step="0.01" min="0">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-label-danger btn-sm"
                                                        onclick="removeEntryLine(this)">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>Total</td>
                                                <td id="totalDebit">$0.00</td>
                                                <td id="totalCredit">$0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addEntryLine()">
                                    <i class="ri-add-line"></i> Add Line
                                </button>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="post_immediately"
                                        id="postImmediately">
                                    <label class="form-check-label" for="postImmediately">
                                        Post entry immediately
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle entry line calculations
            function updateTotals() {
                let totalDebit = 0;
                let totalCredit = 0;

                document.querySelectorAll('.debit-amount').forEach(input => {
                    totalDebit += parseFloat(input.value || 0);
                });

                document.querySelectorAll('.credit-amount').forEach(input => {
                    totalCredit += parseFloat(input.value || 0);
                });

                document.getElementById('totalDebit').textContent = `$${totalDebit.toFixed(2)}`;
                document.getElementById('totalCredit').textContent = `$${totalCredit.toFixed(2)}`;
            }

            // Add event listeners to all amount inputs
            document.querySelectorAll('.debit-amount, .credit-amount').forEach(input => {
                input.addEventListener('input', updateTotals);
            });

            // Initialize totals
            updateTotals();
        });

        function addEntryLine() {
            const tbody = document.getElementById('entryLines');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
        <td>
            <select class="form-select" name="accounts[]" required>
                <option value="">Select Account</option>
                <!-- Accounts will be populated here -->
            </select>
        </td>
        <td>
            <input type="number" class="form-control debit-amount" name="debits[]" step="0.01" min="0">
        </td>
        <td>
            <input type="number" class="form-control credit-amount" name="credits[]" step="0.01" min="0">
        </td>
        <td>
            <button type="button" class="btn btn-label-danger btn-sm" onclick="removeEntryLine(this)">
                <i class="ri-delete-bin-line"></i>
            </button>
        </td>
    `;
            tbody.appendChild(newRow);

            // Add event listeners to new inputs
            newRow.querySelectorAll('.debit-amount, .credit-amount').forEach(input => {
                input.addEventListener('input', () => {
                    let totalDebit = 0;
                    let totalCredit = 0;

                    document.querySelectorAll('.debit-amount').forEach(input => {
                        totalDebit += parseFloat(input.value || 0);
                    });

                    document.querySelectorAll('.credit-amount').forEach(input => {
                        totalCredit += parseFloat(input.value || 0);
                    });

                    document.getElementById('totalDebit').textContent = `$${totalDebit.toFixed(2)}`;
                    document.getElementById('totalCredit').textContent = `$${totalCredit.toFixed(2)}`;
                });
            });
        }

        function removeEntryLine(button) {
            const row = button.closest('tr');
            if (document.getElementById('entryLines').children.length > 1) {
                row.remove();

                // Update totals after removing row
                let totalDebit = 0;
                let totalCredit = 0;

                document.querySelectorAll('.debit-amount').forEach(input => {
                    totalDebit += parseFloat(input.value || 0);
                });

                document.querySelectorAll('.credit-amount').forEach(input => {
                    totalCredit += parseFloat(input.value || 0);
                });

                document.getElementById('totalDebit').textContent = `$${totalDebit.toFixed(2)}`;
                document.getElementById('totalCredit').textContent = `$${totalCredit.toFixed(2)}`;
            }
        }
    </script>
@endsection

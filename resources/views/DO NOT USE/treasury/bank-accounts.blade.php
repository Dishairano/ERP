@extends('layouts/contentNavbarLayout')

@section('title', 'Bank Accounts')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Bank Accounts</h4>
                                <p class="mb-0">Manage company bank accounts and transactions</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addAccountModal">
                                        <i class="ri-add-line"></i> New Account
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#importTransactionsModal"><i
                                                    class="ri-download-line me-1"></i> Import Transactions</a></li>
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#reconcileModal"><i class="ri-check-double-line me-1"></i>
                                                Reconcile</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-file-chart-line me-1"></i>
                                                Generate Report</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Summary Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Balance</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$250,000</h4>
                                    <small class="text-success">(+5%)</small>
                                </div>
                                <small>Across all accounts</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
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
                                <p class="card-text">Pending Transactions</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">15</h4>
                                    <small class="text-warning">($25,000)</small>
                                </div>
                                <small>Awaiting clearance</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-time-line"></i>
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
                                <p class="card-text">Monthly Interest</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$500</h4>
                                    <small class="text-success">(+2%)</small>
                                </div>
                                <small>Earned this month</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-percent-line"></i>
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
                                <p class="card-text">Bank Charges</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$150</h4>
                                    <small class="text-danger">(-10%)</small>
                                </div>
                                <small>This month</small>
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
        </div>

        <!-- Bank Accounts List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Bank Accounts</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    <th>Bank</th>
                                    <th>Type</th>
                                    <th>Currency</th>
                                    <th>Balance</th>
                                    <th>Last Transaction</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accounts ?? [] as $account)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($account->bank ?? 'B', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <a href="#"
                                                        class="text-body fw-bold">{{ $account->name ?? 'Main Operating Account' }}</a>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $account->branch ?? 'Main Branch' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $account->number ?? '****1234' }}</td>
                                        <td>{{ $account->bank ?? 'Example Bank' }}</td>
                                        <td>{{ $account->type ?? 'Checking' }}</td>
                                        <td>{{ $account->currency ?? 'USD' }}</td>
                                        <td>${{ number_format($account->balance ?? 100000, 2) }}</td>
                                        <td>{{ $account->last_transaction ?? '2024-01-15' }}</td>
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
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-exchange-line me-1"></i> View Transactions
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-bank-card-line me-1"></i> Transfer Funds
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="p-3">
                                                <i class="ri-bank-line ri-3x text-primary mb-3"></i>
                                                <h5>No Bank Accounts Found</h5>
                                                <p class="mb-3">Start by adding your first bank account</p>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addAccountModal">
                                                    <i class="ri-add-line"></i> Add Account
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Transactions</h5>
                        <a href="#" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions ?? [] as $transaction)
                                    <tr>
                                        <td>{{ $transaction->date ?? '2024-01-15' }}</td>
                                        <td>{{ $transaction->description ?? 'Payment to Vendor' }}</td>
                                        <td>{{ $transaction->account ?? 'Main Operating Account' }}</td>
                                        <td>{{ $transaction->type ?? 'Debit' }}</td>
                                        <td
                                            class="{{ $transaction->amount ?? -1000 < 0 ? 'text-danger' : 'text-success' }}">
                                            ${{ number_format(abs($transaction->amount ?? 1000), 2) }}
                                        </td>
                                        <td>${{ number_format($transaction->balance ?? 99000, 2) }}</td>
                                        <td>
                                            <span class="badge bg-label-success">Cleared</span>
                                        </td>
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
                                                            <i class="ri-file-list-3-line me-1"></i> Add Note
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-attachment-line me-1"></i> Attachments
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No recent transactions</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Account Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Bank Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('treasury.bank-accounts.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Account Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Bank Name</label>
                                <input type="text" class="form-control" name="bank" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Branch</label>
                                <input type="text" class="form-control" name="branch">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Account Number</label>
                                <input type="text" class="form-control" name="account_number" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Account Type</label>
                                <select class="form-select" name="account_type" required>
                                    <option value="">Select Type</option>
                                    <option value="checking">Checking</option>
                                    <option value="savings">Savings</option>
                                    <option value="money_market">Money Market</option>
                                    <option value="time_deposit">Time Deposit</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Currency</label>
                                <select class="form-select" name="currency" required>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="JPY">JPY</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Opening Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="opening_balance" step="0.01"
                                        min="0">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                        checked>
                                    <label class="form-check-label" for="isActive">
                                        Account is active and ready for use
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Transactions Modal -->
    <div class="modal fade" id="importTransactionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Transactions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('treasury.bank-accounts.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Bank Account</label>
                                <select class="form-select" name="account_id" required>
                                    <option value="">Select Account</option>
                                    <!-- Accounts will be populated here -->
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">File</label>
                                <input type="file" class="form-control" name="file" required>
                                <small class="text-muted">Supported formats: CSV, OFX, QFX</small>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="skip_duplicates"
                                        id="skipDuplicates" checked>
                                    <label class="form-check-label" for="skipDuplicates">
                                        Skip duplicate transactions
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reconcile Modal -->
    <div class="modal fade" id="reconcileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reconcile Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('treasury.bank-accounts.reconcile') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Bank Account</label>
                                <select class="form-select" name="account_id" required>
                                    <option value="">Select Account</option>
                                    <!-- Accounts will be populated here -->
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Statement Date</label>
                                <input type="date" class="form-control" name="statement_date" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Statement Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="statement_balance" step="0.01"
                                        required>
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
                        <button type="submit" class="btn btn-primary">Start Reconciliation</button>
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

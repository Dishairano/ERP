@extends('layouts/contentNavbarLayout')

@section('title', 'Accounts Payable')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Accounts Payable</h4>
                                <p class="mb-0">Manage vendor invoices and payments</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createInvoiceModal">
                                        <i class="ri-add-line"></i> New Invoice
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#createPaymentModal"><i
                                                    class="ri-money-dollar-circle-line me-1"></i> Record Payment</a></li>
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
                                <p class="card-text">Total Payables</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$50,000</h4>
                                    <small class="text-danger">(+8%)</small>
                                </div>
                                <small>vs last month</small>
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
                                <p class="card-text">Due This Week</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$15,000</h4>
                                    <small class="text-warning">(+12%)</small>
                                </div>
                                <small>10 invoices due</small>
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
                                <p class="card-text">Overdue</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$5,000</h4>
                                    <small class="text-danger">(-15%)</small>
                                </div>
                                <small>5 invoices overdue</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="ri-error-warning-line"></i>
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
                                <p class="card-text">Paid This Month</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$30,000</h4>
                                    <small class="text-success">(+20%)</small>
                                </div>
                                <small>20 invoices paid</small>
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
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label">Vendor</label>
                                <select class="form-select">
                                    <option value="">All Vendors</option>
                                    <!-- Vendors will be populated here -->
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="overdue">Overdue</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label">Due Date</label>
                                <select class="form-select">
                                    <option value="">All Dates</option>
                                    <option value="this_week">This Week</option>
                                    <option value="next_week">Next Week</option>
                                    <option value="this_month">This Month</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" placeholder="Search invoices...">
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

        <!-- Invoices Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Invoices</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Invoice #</th>
                                    <th>Vendor</th>
                                    <th>Issue Date</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices ?? [] as $invoice)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#viewInvoiceModal">
                                                {{ $invoice->number ?? 'INV-001' }}
                                            </a>
                                        </td>
                                        <td>{{ $invoice->vendor->name ?? 'Vendor Name' }}</td>
                                        <td>{{ $invoice->issue_date ?? '2024-01-01' }}</td>
                                        <td>{{ $invoice->due_date ?? '2024-01-31' }}</td>
                                        <td>${{ number_format($invoice->amount ?? 1000, 2) }}</td>
                                        <td>${{ number_format($invoice->paid_amount ?? 0, 2) }}</td>
                                        <td>${{ number_format($invoice->balance ?? 1000, 2) }}</td>
                                        <td>
                                            <span class="badge bg-label-warning">Pending</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                                                            <i class="ri-money-dollar-circle-line me-1"></i> Record Payment
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            data-bs-toggle="modal" data-bs-target="#editInvoiceModal">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item">
                                                            <i class="ri-download-line me-1"></i> Download
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
                                        <td colspan="10" class="text-center">
                                            <div class="p-3">
                                                <i class="ri-file-list-3-line ri-3x text-primary mb-3"></i>
                                                <h5>No Invoices Found</h5>
                                                <p class="mb-3">Start by adding your first invoice</p>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#createInvoiceModal">
                                                    <i class="ri-add-line"></i> Create Invoice
                                                </button>
                                            </div>
                                        </td>
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

    <!-- Create Invoice Modal -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounting.accounts-payable.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vendor</label>
                                <select class="form-select" name="vendor_id" required>
                                    <option value="">Select Vendor</option>
                                    <!-- Vendors will be populated here -->
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" name="invoice_number" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Issue Date</label>
                                <input type="date" class="form-control" name="issue_date" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Due Date</label>
                                <input type="date" class="form-control" name="due_date" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Items</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="invoiceItems">
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control" name="descriptions[]"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control quantity"
                                                        name="quantities[]" min="1" value="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control price" name="prices[]"
                                                        step="0.01" min="0" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control amount" name="amounts[]"
                                                        step="0.01" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-label-danger btn-sm"
                                                        onclick="removeInvoiceItem(this)">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end">Subtotal</td>
                                                <td>
                                                    <input type="number" class="form-control" name="subtotal" readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Tax (%)</td>
                                                <td>
                                                    <input type="number" class="form-control" name="tax_rate"
                                                        value="0" min="0" max="100">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Tax Amount</td>
                                                <td>
                                                    <input type="number" class="form-control" name="tax_amount"
                                                        readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Total</td>
                                                <td>
                                                    <input type="number" class="form-control" name="total" readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addInvoiceItem()">
                                    <i class="ri-add-line"></i> Add Item
                                </button>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Attachments</label>
                                <input type="file" class="form-control" name="attachments[]" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Record Payment Modal -->
    <div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounting.accounts-payable.payments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Invoice</label>
                                <input type="text" class="form-control" value="INV-001" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Payment Date</label>
                                <input type="date" class="form-control" name="payment_date" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Amount</label>
                                <input type="number" class="form-control" name="amount" step="0.01" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select" name="payment_method" required>
                                    <option value="">Select Method</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="check">Check</option>
                                    <option value="cash">Cash</option>
                                    <option value="credit_card">Credit Card</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Reference Number</label>
                                <input type="text" class="form-control" name="reference_number">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Record Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle select all checkbox
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    document.querySelectorAll('tbody .form-check-input').forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }

            // Initialize invoice calculations
            updateInvoiceCalculations();
        });

        function addInvoiceItem() {
            const tbody = document.getElementById('invoiceItems');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
        <td>
            <input type="text" class="form-control" name="descriptions[]" required>
        </td>
        <td>
            <input type="number" class="form-control quantity" name="quantities[]" min="1" value="1" required>
        </td>
        <td>
            <input type="number" class="form-control price" name="prices[]" step="0.01" min="0" required>
        </td>
        <td>
            <input type="number" class="form-control amount" name="amounts[]" step="0.01" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-label-danger btn-sm" onclick="removeInvoiceItem(this)">
                <i class="ri-delete-bin-line"></i>
            </button>
        </td>
    `;
            tbody.appendChild(newRow);

            // Add event listeners to new inputs
            const row = tbody.lastElementChild;
            const quantity = row.querySelector('.quantity');
            const price = row.querySelector('.price');
            const amount = row.querySelector('.amount');

            quantity.addEventListener('input', () => calculateRowAmount(row));
            price.addEventListener('input', () => calculateRowAmount(row));
        }

        function removeInvoiceItem(button) {
            const tbody = document.getElementById('invoiceItems');
            if (tbody.children.length > 1) {
                button.closest('tr').remove();
                updateInvoiceCalculations();
            }
        }

        function calculateRowAmount(row) {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const amount = quantity * price;
            row.querySelector('.amount').value = amount.toFixed(2);
            updateInvoiceCalculations();
        }

        function updateInvoiceCalculations() {
            let subtotal = 0;
            document.querySelectorAll('.amount').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });

            const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
            const taxAmount = subtotal * (taxRate / 100);
            const total = subtotal + taxAmount;

            document.querySelector('input[name="subtotal"]').value = subtotal.toFixed(2);
            document.querySelector('input[name="tax_amount"]').value = taxAmount.toFixed(2);
            document.querySelector('input[name="total"]').value = total.toFixed(2);
        }

        // Add event listener to tax rate input
        document.querySelector('input[name="tax_rate"]').addEventListener('input', updateInvoiceCalculations);
    </script>
@endsection

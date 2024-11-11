@extends('layouts/contentNavbarLayout')

@section('title', 'Invoices')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Invoices</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Invoice Date</th>
                                        <th>Due Date</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>#{{ $invoice->id }}</td>
                                            <td>#{{ $invoice->order->id }}</td>
                                            <td>{{ $invoice->order->customer->name }}</td>
                                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                            <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewInvoiceModal{{ $invoice->id }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                        @if ($invoice->status === 'pending')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="markAsPaid({{ $invoice->id }})">
                                                                <i class="ri-checkbox-circle-line me-1"></i> Mark as Paid
                                                            </a>
                                                        @endif
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="downloadInvoice({{ $invoice->id }})">
                                                            <i class="ri-download-2-line me-1"></i> Download PDF
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="sendInvoice({{ $invoice->id }})">
                                                            <i class="ri-mail-send-line me-1"></i> Send to Customer
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($invoices as $invoice)
        <!-- View Invoice Modal -->
        <div class="modal fade" id="viewInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Invoice Details #{{ $invoice->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Customer Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ $invoice->order->customer->name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $invoice->order->customer->email }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $invoice->order->customer->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Invoice Information</h6>
                                <p class="mb-1"><strong>Invoice Date:</strong>
                                    {{ $invoice->invoice_date->format('M d, Y') }}</p>
                                <p class="mb-1"><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price, 2) }}</td>
                                            <td>{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th>{{ number_format($invoice->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if ($invoice->payment_date)
                            <div class="mt-3">
                                <h6>Payment Information</h6>
                                <p class="mb-1"><strong>Payment Date:</strong>
                                    {{ $invoice->payment_date->format('M d, Y') }}</p>
                                <p class="mb-1"><strong>Payment Method:</strong> {{ $invoice->payment_method }}</p>
                                <p class="mb-1"><strong>Reference:</strong> {{ $invoice->payment_reference }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('page-script')
    <script>
        function markAsPaid(invoiceId) {
            if (confirm('Are you sure you want to mark this invoice as paid?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/invoices/${invoiceId}/status`;
                form.innerHTML = `
            @csrf
            <input type="hidden" name="status" value="paid">
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function downloadInvoice(invoiceId) {
            window.location.href = `/sales/invoices/${invoiceId}/download`;
        }

        function sendInvoice(invoiceId) {
            if (confirm('Are you sure you want to send this invoice to the customer?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/invoices/${invoiceId}/send`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection

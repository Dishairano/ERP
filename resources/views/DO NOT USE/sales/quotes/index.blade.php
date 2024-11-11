@extends('layouts/contentNavbarLayout')

@section('title', 'Quotations')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Quotations</h5>
                        <a href="{{ route('sales.quotes.create') }}" class="btn btn-primary">
                            Create Quote
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Quote #</th>
                                        <th>Customer</th>
                                        <th>Valid Until</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotes as $quote)
                                        <tr>
                                            <td>#{{ $quote->id }}</td>
                                            <td>{{ $quote->customer->name }}</td>
                                            <td>{{ $quote->valid_until->format('M d, Y') }}</td>
                                            <td>{{ number_format($quote->total_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $quote->status === 'accepted' ? 'success' : ($quote->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($quote->status) }}
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
                                                            data-bs-target="#viewQuoteModal{{ $quote->id }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                        @if ($quote->status === 'pending')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="updateStatus({{ $quote->id }}, 'accepted')">
                                                                <i class="ri-checkbox-circle-line me-1"></i> Mark as
                                                                Accepted
                                                            </a>
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="updateStatus({{ $quote->id }}, 'rejected')">
                                                                <i class="ri-close-circle-line me-1"></i> Mark as Rejected
                                                            </a>
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="convertToOrder({{ $quote->id }})">
                                                                <i class="ri-shopping-cart-line me-1"></i> Convert to Order
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $quotes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($quotes as $quote)
        <!-- View Quote Modal -->
        <div class="modal fade" id="viewQuoteModal{{ $quote->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quote Details #{{ $quote->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Customer Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ $quote->customer->name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $quote->customer->email }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $quote->customer->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Quote Information</h6>
                                <p class="mb-1"><strong>Created:</strong> {{ $quote->created_at->format('M d, Y') }}</p>
                                <p class="mb-1"><strong>Valid Until:</strong> {{ $quote->valid_until->format('M d, Y') }}
                                </p>
                                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($quote->status) }}</p>
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
                                    @foreach ($quote->items as $item)
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
                                        <th>{{ number_format($quote->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if ($quote->notes)
                            <div class="mt-3">
                                <h6>Notes</h6>
                                <p>{{ $quote->notes }}</p>
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
        function updateStatus(quoteId, status) {
            if (confirm(`Are you sure you want to mark this quote as ${status}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/quotes/${quoteId}/status`;
                form.innerHTML = `
            @csrf
            <input type="hidden" name="status" value="${status}">
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function convertToOrder(quoteId) {
            if (confirm('Are you sure you want to convert this quote to an order?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/quotes/${quoteId}/convert`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection

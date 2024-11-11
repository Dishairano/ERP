@extends('layouts/contentNavbarLayout')

@section('title', 'Sales Orders')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Sales Orders</h5>
                        <a href="{{ route('sales.orders.create') }}" class="btn btn-primary">
                            Create Order
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Order Date</th>
                                        <th>Delivery Date</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->customer->name }}</td>
                                            <td>{{ $order->order_date->format('M d, Y') }}</td>
                                            <td>{{ $order->delivery_date->format('M d, Y') }}</td>
                                            <td>{{ number_format($order->total_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'processing' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($order->status) }}
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
                                                            data-bs-target="#viewOrderModal{{ $order->id }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                        @if ($order->status === 'delivered' && !$order->invoice)
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="generateInvoice({{ $order->id }})">
                                                                <i class="ri-file-list-3-line me-1"></i> Generate Invoice
                                                            </a>
                                                        @endif
                                                        @if ($order->status === 'pending')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="updateStatus({{ $order->id }}, 'processing')">
                                                                <i class="ri-loader-4-line me-1"></i> Mark as Processing
                                                            </a>
                                                        @elseif($order->status === 'processing')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="updateStatus({{ $order->id }}, 'shipped')">
                                                                <i class="ri-truck-line me-1"></i> Mark as Shipped
                                                            </a>
                                                        @elseif($order->status === 'shipped')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="updateStatus({{ $order->id }}, 'delivered')">
                                                                <i class="ri-checkbox-circle-line me-1"></i> Mark as
                                                                Delivered
                                                            </a>
                                                        @endif
                                                        @if ($order->status === 'pending')
                                                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                                onclick="cancelOrder({{ $order->id }})">
                                                                <i class="ri-close-circle-line me-1"></i> Cancel Order
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
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($orders as $order)
        <!-- View Order Modal -->
        <div class="modal fade" id="viewOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order Details #{{ $order->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Customer Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ $order->customer->name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $order->customer->email }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Order Information</h6>
                                <p class="mb-1"><strong>Order Date:</strong> {{ $order->order_date->format('M d, Y') }}
                                </p>
                                <p class="mb-1"><strong>Delivery Date:</strong>
                                    {{ $order->delivery_date->format('M d, Y') }}</p>
                                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
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
                                    @foreach ($order->items as $item)
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
                                        <th>{{ number_format($order->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if ($order->notes)
                            <div class="mt-3">
                                <h6>Notes</h6>
                                <p>{{ $order->notes }}</p>
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
        function generateInvoice(orderId) {
            if (confirm('Are you sure you want to generate an invoice for this order?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/orders/${orderId}/invoice`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function updateStatus(orderId, status) {
            if (confirm(`Are you sure you want to mark this order as ${status}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/orders/${orderId}/status`;
                form.innerHTML = `
            @csrf
            <input type="hidden" name="status" value="${status}">
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/orders/${orderId}/status`;
                form.innerHTML = `
            @csrf
            <input type="hidden" name="status" value="cancelled">
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection

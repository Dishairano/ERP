@extends('layouts/contentNavbarLayout')

@section('title', 'Sales Returns')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Sales Returns</h5>
                        <a href="{{ route('sales.returns.create') }}" class="btn btn-primary">
                            Create Return
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Return #</th>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Reason</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($returns as $return)
                                        <tr>
                                            <td>#{{ $return->id }}</td>
                                            <td>#{{ $return->order->id }}</td>
                                            <td>{{ $return->order->customer->name }}</td>
                                            <td>{{ Str::limit($return->reason, 30) }}</td>
                                            <td>{{ number_format($return->total_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $return->status === 'completed' ? 'success' : ($return->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($return->status) }}
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
                                                            data-bs-target="#viewReturnModal{{ $return->id }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                        @if ($return->status === 'pending')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="updateStatus({{ $return->id }}, 'approved')">
                                                                <i class="ri-checkbox-circle-line me-1"></i> Approve
                                                            </a>
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="updateStatus({{ $return->id }}, 'rejected')">
                                                                <i class="ri-close-circle-line me-1"></i> Reject
                                                            </a>
                                                        @endif
                                                        @if ($return->status === 'approved')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="updateStatus({{ $return->id }}, 'completed')">
                                                                <i class="ri-check-double-line me-1"></i> Mark as Completed
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
                        {{ $returns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($returns as $return)
        <!-- View Return Modal -->
        <div class="modal fade" id="viewReturnModal{{ $return->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Return Details #{{ $return->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Customer Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ $return->order->customer->name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $return->order->customer->email }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $return->order->customer->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Return Information</h6>
                                <p class="mb-1"><strong>Created:</strong> {{ $return->created_at->format('M d, Y') }}</p>
                                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($return->status) }}</p>
                                <p class="mb-1"><strong>Reason:</strong> {{ $return->reason }}</p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Original Qty</th>
                                        <th>Return Qty</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($return->items as $item)
                                        <tr>
                                            <td>{{ $item->orderItem->product->name }}</td>
                                            <td>{{ $item->orderItem->quantity }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price, 2) }}</td>
                                            <td>{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th>{{ number_format($return->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if ($return->notes)
                            <div class="mt-3">
                                <h6>Notes</h6>
                                <p>{{ $return->notes }}</p>
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
        function updateStatus(returnId, status) {
            if (confirm(`Are you sure you want to mark this return as ${status}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/returns/${returnId}/status`;
                form.innerHTML = `
            @csrf
            <input type="hidden" name="status" value="${status}">
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection

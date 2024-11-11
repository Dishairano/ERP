@extends('layouts/contentNavbarLayout')

@section('title', 'Production Orders')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Production /</span> Orders
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Production Orders</h5>
                <button type="button" class="btn btn-primary">
                    <i class="ri-add-line me-1"></i> New Order
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productionOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->product_name }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->start_date }}</td>
                                    <td>{{ $order->due_date }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $order->status === 'Completed'
                                                ? 'success'
                                                : ($order->status === 'In Progress'
                                                    ? 'info'
                                                    : ($order->status === 'Pending'
                                                        ? 'warning'
                                                        : 'danger')) }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $order->progress }}%"
                                                aria-valuenow="{{ $order->progress }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $order->progress }}%</small>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-eye-line me-2"></i> View Details
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-pencil-line me-2"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-play-line me-2"></i> Start Production
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-stop-line me-2"></i> Stop Production
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $productionOrders->links() }}
            </div>
        </div>
    </div>
@endsection

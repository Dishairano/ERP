@extends('layouts/contentNavbarLayout')

@section('title', 'Purchase Orders')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Purchase Orders</h4>
                <a href="{{ route('purchase_orders.create') }}" class="btn btn-success ms-auto">Create Purchase Order</a>
            </div>
            <div class="card-body">
                <!-- Purchase Orders Table -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->supplier->name }}</td>
                                <td>{{ $order->order_date }}</td>
                                <td>{{ $order->total_amount }}</td>
                                <td>{{ $order->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

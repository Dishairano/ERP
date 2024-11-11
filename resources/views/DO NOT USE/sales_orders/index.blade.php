@extends('layouts/contentNavbarLayout')

@section('title', 'Sales Orders')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Sales Orders</h4>
                <a href="{{ route('sales_orders.create') }}" class="btn btn-success">Create Sales Order</a>
            </div>
            <div class="card-body">
                <!-- Sales Orders Table -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salesOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->customer->name }}</td>
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

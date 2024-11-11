@extends('layouts/contentNavbarLayout')

@section('title', 'Goods Receipts')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Goods Receipts</h4>
                <a href="{{ route('goods_receipts.create') }}" class="btn btn-success ms-auto">Add Goods Receipt</a>
            </div>
            <div class="card-body">
                <!-- Goods Receipts Table -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Purchase Order ID</th>
                            <th>Receipt Date</th>
                            <th>Quantity Received</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($goodsReceipts as $receipt)
                            <tr>
                                <td>{{ $receipt->id }}</td>
                                <td>{{ $receipt->purchase_order_id }}</td>
                                <td>{{ $receipt->receipt_date }}</td>
                                <td>{{ $receipt->quantity_received }}</td>
                                <td>{{ $receipt->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

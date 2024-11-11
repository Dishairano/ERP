@extends('layouts/contentNavbarLayout')

@section('title', 'Add Goods Receipt')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Add Goods Receipt</h4>
            </div>
            <div class="card-body">
                <!-- Goods Receipt Form -->
                <form action="{{ route('goods_receipts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="purchase_order_id" class="form-label">Purchase Order</label>
                        <select name="purchase_order_id" id="purchase_order_id" class="form-control">
                            @foreach ($purchaseOrders as $order)
                                <option value="{{ $order->id }}">Order ID: {{ $order->id }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="receipt_date" class="form-label">Receipt Date</label>
                        <input type="date" name="receipt_date" id="receipt_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="quantity_received" class="form-label">Quantity Received</label>
                        <input type="number" name="quantity_received" id="quantity_received" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection

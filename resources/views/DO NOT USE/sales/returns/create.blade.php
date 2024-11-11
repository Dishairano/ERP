@extends('layouts/contentNavbarLayout')

@section('title', 'Create Return')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Create Return</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sales.returns.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Order</label>
                                    <select class="form-select @error('order_id') is-invalid @enderror" name="order_id"
                                        required onchange="loadOrderItems(this.value)">
                                        <option value="">Select Order</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->id }}"
                                                {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                #{{ $order->id }} - {{ $order->customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('order_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Return Reason</label>
                                    <select class="form-select @error('reason') is-invalid @enderror" name="reason"
                                        required>
                                        <option value="">Select Reason</option>
                                        <option value="defective" {{ old('reason') == 'defective' ? 'selected' : '' }}>
                                            Defective Product</option>
                                        <option value="wrong_item" {{ old('reason') == 'wrong_item' ? 'selected' : '' }}>
                                            Wrong Item Received</option>
                                        <option value="not_satisfied"
                                            {{ old('reason') == 'not_satisfied' ? 'selected' : '' }}>Not Satisfied</option>
                                        <option value="damaged" {{ old('reason') == 'damaged' ? 'selected' : '' }}>Damaged
                                            in Transit</option>
                                        <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div id="orderItems" class="mb-3">
                                <!-- Order items will be loaded here dynamically -->
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Additional Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <a href="{{ route('sales.returns') }}" class="btn btn-label-secondary me-1">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Return</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        function loadOrderItems(orderId) {
            if (!orderId) {
                document.getElementById('orderItems').innerHTML = '';
                return;
            }

            fetch(`/sales/orders/${orderId}/items`)
                .then(response => response.json())
                .then(items => {
                    let html = `
                <label class="form-label">Return Items</label>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Original Quantity</th>
                                <th>Return Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

                    items.forEach(item => {
                        html += `
                    <tr>
                        <td>${item.product.name}</td>
                        <td>${item.quantity}</td>
                        <td>
                            <input type="number" class="form-control"
                                name="items[${item.id}][quantity]"
                                min="1" max="${item.quantity}"
                                placeholder="Qty to return">
                            <input type="hidden" name="items[${item.id}][price]"
                                value="${item.price}">
                        </td>
                        <td>${item.price}</td>
                    </tr>
                `;
                    });

                    html += `
                        </tbody>
                    </table>
                </div>
            `;

                    document.getElementById('orderItems').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading order items:', error);
                    document.getElementById('orderItems').innerHTML =
                        '<div class="alert alert-danger">Error loading order items</div>';
                });
        }
    </script>
@endsection

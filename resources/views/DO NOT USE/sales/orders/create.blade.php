@extends('layouts/contentNavbarLayout')

@section('title', 'Create Sales Order')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Create Sales Order</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sales.orders.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Customer</label>
                                    <select class="form-select @error('customer_id') is-invalid @enderror"
                                        name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Order Date</label>
                                    <input type="date" class="form-control @error('order_date') is-invalid @enderror"
                                        name="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}" required>
                                    @error('order_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Delivery Date</label>
                                    <input type="date" class="form-control @error('delivery_date') is-invalid @enderror"
                                        name="delivery_date" value="{{ old('delivery_date') }}" required>
                                    @error('delivery_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Items</label>
                                <div id="orderItems">
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <select class="form-select" name="items[0][product_id]" required
                                                onchange="updatePrice(this, 0)">
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="items[0][quantity]"
                                                placeholder="Quantity" min="1" required onchange="updateSubtotal(0)">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="items[0][price]"
                                                placeholder="Price" step="0.01" required readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger" onclick="removeItem(this)">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addItem()">
                                    Add Item
                                </button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <a href="{{ route('sales.orders') }}" class="btn btn-label-secondary me-1">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Order</button>
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
        let itemCount = 1;

        function addItem() {
            const template = `
        <div class="row mb-2">
            <div class="col-md-4">
                <select class="form-select" name="items[${itemCount}][product_id]" required
                    onchange="updatePrice(this, ${itemCount})">
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                        {{ $product->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="items[${itemCount}][quantity]"
                    placeholder="Quantity" min="1" required onchange="updateSubtotal(${itemCount})">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="items[${itemCount}][price]"
                    placeholder="Price" step="0.01" required readonly>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="removeItem(this)">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `;
            document.getElementById('orderItems').insertAdjacentHTML('beforeend', template);
            itemCount++;
        }

        function removeItem(button) {
            button.closest('.row').remove();
        }

        function updatePrice(select, index) {
            const price = select.options[select.selectedIndex].dataset.price;
            const priceInput = document.querySelector(`[name="items[${index}][price]"]`);
            priceInput.value = price;
            updateSubtotal(index);
        }

        function updateSubtotal(index) {
            const quantity = document.querySelector(`[name="items[${index}][quantity]"]`).value;
            const price = document.querySelector(`[name="items[${index}][price]"]`).value;
            const subtotal = quantity * price;
            // You might want to display the subtotal somewhere
        }
    </script>
@endsection

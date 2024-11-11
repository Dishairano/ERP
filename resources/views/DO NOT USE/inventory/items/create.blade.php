@extends('layouts/contentNavbarLayout')

@section('title', 'Add New Item')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add New Item</h5>
                        <a href="{{ route('inventory.items.index') }}" class="btn btn-label-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Items
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('inventory.items.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <h6 class="mb-3">Basic Information</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Item Code</label>
                                        <input type="text" class="form-control" name="code" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" name="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Unit</label>
                                        <select class="form-select" name="unit_id" required>
                                            <option value="">Select Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Pricing & Stock -->
                                <div class="col-md-6">
                                    <h6 class="mb-3">Pricing & Stock</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Unit Cost</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" name="unit_cost" step="0.01"
                                                min="0" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Unit Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" name="unit_price" step="0.01"
                                                min="0" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tax Rate (%)</label>
                                        <input type="number" class="form-control" name="tax_rate" step="0.01"
                                            min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="discontinued">Discontinued</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="is_stockable"
                                                id="isStockable" checked>
                                            <label class="form-check-label" for="isStockable">Stockable Item</label>
                                        </div>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="is_purchasable"
                                                id="isPurchasable" checked>
                                            <label class="form-check-label" for="isPurchasable">Purchasable Item</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_sellable"
                                                id="isSellable" checked>
                                            <label class="form-check-label" for="isSellable">Sellable Item</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Information -->
                                <div class="col-12">
                                    <h6 class="mb-3 mt-3">Additional Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Barcode</label>
                                                <input type="text" class="form-control" name="barcode">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Manufacturer</label>
                                                <input type="text" class="form-control" name="manufacturer">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Supplier</label>
                                                <select class="form-select" name="supplier_id">
                                                    <option value="">Select Supplier</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Weight</label>
                                                <input type="number" class="form-control" name="weight" step="0.01"
                                                    min="0">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Dimensions (L x W x H)</label>
                                                <input type="text" class="form-control" name="dimensions"
                                                    placeholder="e.g., 10 x 5 x 3 cm">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notes</label>
                                                <textarea class="form-control" name="notes" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stock Levels -->
                                <div class="col-12 stock-levels-section">
                                    <h6 class="mb-3 mt-3">Initial Stock Levels</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Warehouse</th>
                                                    <th>Initial Quantity</th>
                                                    <th>Minimum Stock</th>
                                                    <th>Maximum Stock</th>
                                                    <th>Reorder Point</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (\App\Models\Warehouse::active()->get() as $warehouse)
                                                    <tr>
                                                        <td>{{ $warehouse->name }}</td>
                                                        <td>
                                                            <input type="number" class="form-control"
                                                                name="stock_levels[{{ $warehouse->id }}][quantity]"
                                                                min="0" value="0">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control"
                                                                name="stock_levels[{{ $warehouse->id }}][minimum_stock]"
                                                                min="0" value="0">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control"
                                                                name="stock_levels[{{ $warehouse->id }}][maximum_stock]"
                                                                min="0" value="0">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control"
                                                                name="stock_levels[{{ $warehouse->id }}][reorder_point]"
                                                                min="0" value="0">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary">Create Item</button>
                                    <a href="{{ route('inventory.items.index') }}"
                                        class="btn btn-label-secondary">Cancel</a>
                                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const isStockableCheckbox = document.getElementById('isStockable');
            const stockLevelsSection = document.querySelector('.stock-levels-section');

            function toggleStockLevels() {
                stockLevelsSection.style.display = isStockableCheckbox.checked ? 'block' : 'none';
            }

            isStockableCheckbox.addEventListener('change', toggleStockLevels);
            toggleStockLevels();
        });
    </script>
@endsection

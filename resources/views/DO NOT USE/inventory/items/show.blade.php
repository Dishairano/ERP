@extends('layouts/contentNavbarLayout')

@section('title', 'Item Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Item Details</h5>
                        <div>
                            <a href="{{ route('inventory.items.edit', $item) }}" class="btn btn-primary me-2">
                                <i class="ri-edit-line me-1"></i> Edit Item
                            </a>
                            <a href="{{ route('inventory.items.index') }}" class="btn btn-label-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Back to Items
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Basic Information</h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Item Code</th>
                                            <td>{{ $item->code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $item->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $item->description }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>{{ $item->category->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Unit</th>
                                            <td>{{ $item->unit->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span
                                                    class="badge bg-label-{{ $item->status === 'active' ? 'success' : ($item->status === 'inactive' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Pricing & Stock -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Pricing & Stock</h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Unit Cost</th>
                                            <td>${{ number_format($item->unit_cost, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Unit Price</th>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tax Rate</th>
                                            <td>{{ number_format($item->tax_rate, 2) }}%</td>
                                        </tr>
                                        <tr>
                                            <th>Total Stock</th>
                                            <td>{{ number_format($item->total_stock_quantity) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Stock Value</th>
                                            <td>${{ number_format($item->total_stock_value, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Properties</th>
                                            <td>
                                                @if ($item->is_stockable)
                                                    <span class="badge bg-label-primary me-1">Stockable</span>
                                                @endif
                                                @if ($item->is_purchasable)
                                                    <span class="badge bg-label-info me-1">Purchasable</span>
                                                @endif
                                                @if ($item->is_sellable)
                                                    <span class="badge bg-label-success">Sellable</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Additional Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="30%">Barcode</th>
                                                    <td>{{ $item->barcode ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Manufacturer</th>
                                                    <td>{{ $item->manufacturer ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Weight</th>
                                                    <td>{{ $item->weight ? number_format($item->weight, 2) : 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="30%">Supplier</th>
                                                    <td>{{ $item->supplier->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Dimensions</th>
                                                    <td>{{ $item->dimensions ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Notes</th>
                                                    <td>{{ $item->notes ?? 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Levels -->
                            @if ($item->is_stockable)
                                <div class="col-12">
                                    <h6 class="mb-3 mt-4">Stock Levels</h6>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Warehouse</th>
                                                    <th>Current Stock</th>
                                                    <th>Minimum Stock</th>
                                                    <th>Maximum Stock</th>
                                                    <th>Reorder Point</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->stockLevels as $stockLevel)
                                                    <tr>
                                                        <td>{{ $stockLevel->warehouse->name }}</td>
                                                        <td>{{ number_format($stockLevel->quantity) }}</td>
                                                        <td>{{ number_format($stockLevel->minimum_stock) }}</td>
                                                        <td>{{ number_format($stockLevel->maximum_stock) }}</td>
                                                        <td>{{ number_format($stockLevel->reorder_point) }}</td>
                                                        <td>
                                                            @if ($stockLevel->quantity <= $stockLevel->minimum_stock)
                                                                <span class="badge bg-label-danger">Critical</span>
                                                            @elseif($stockLevel->quantity <= $stockLevel->reorder_point)
                                                                <span class="badge bg-label-warning">Low</span>
                                                            @elseif($stockLevel->quantity >= $stockLevel->maximum_stock)
                                                                <span class="badge bg-label-info">Excess</span>
                                                            @else
                                                                <span class="badge bg-label-success">Normal</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

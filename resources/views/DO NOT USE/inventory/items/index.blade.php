@extends('layouts/contentNavbarLayout')

@section('title', 'Items & Products')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Items</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($statistics['total_items']) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-stack-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Low Stock Items</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($statistics['low_stock']) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-alert-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Stock Value</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">${{ number_format($statistics['total_value'], 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Active Items</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($statistics['active_items']) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-checkbox-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('inventory.items.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Supplier</label>
                        <select class="form-select" name="supplier">
                            <option value="">All Suppliers</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                            <option value="discontinued" {{ request('status') === 'discontinued' ? 'selected' : '' }}>
                                Discontinued</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Stock Status</label>
                        <select class="form-select" name="stock_status">
                            <option value="">All Stock Status</option>
                            <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock
                            </option>
                            <option value="excess" {{ request('stock_status') === 'excess' ? 'selected' : '' }}>Excess
                                Stock</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                            placeholder="Search...">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('inventory.items.index') }}" class="btn btn-label-secondary">Reset</a>
                        <div class="float-end">
                            <button type="button" class="btn btn-label-success"
                                onclick="window.location.href='{{ route('inventory.items.export') }}'">
                                <i class="ri-file-excel-line me-1"></i> Export
                            </button>
                            <button type="button" class="btn btn-primary"
                                onclick="window.location.href='{{ route('inventory.items.create') }}'">
                                <i class="ri-add-line me-1"></i> Add Item
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Items Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->code }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $item->name }}</h6>
                                            <small class="text-muted">{{ $item->supplier->name ?? 'No Supplier' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->category->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{ number_format($item->total_stock_quantity) }}
                                        @if ($item->stock_status === 'critical')
                                            <span class="badge bg-label-danger ms-2">Critical</span>
                                        @elseif($item->stock_status === 'low')
                                            <span class="badge bg-label-warning ms-2">Low</span>
                                        @elseif($item->stock_status === 'excess')
                                            <span class="badge bg-label-info ms-2">Excess</span>
                                        @endif
                                    </div>
                                </td>
                                <td>${{ number_format($item->total_stock_value, 2) }}</td>
                                <td>
                                    <span
                                        class="badge bg-label-{{ $item->status === 'active' ? 'success' : ($item->status === 'inactive' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('inventory.items.show', $item) }}">
                                                    <i class="ri-eye-line me-1"></i> View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('inventory.items.edit', $item) }}">
                                                    <i class="ri-edit-line me-1"></i> Edit
                                                </a>
                                            </li>
                                            @if (!$item->movements()->exists() && !$item->adjustments()->exists())
                                                <li>
                                                    <form action="{{ route('inventory.items.destroy', $item) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this item?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection

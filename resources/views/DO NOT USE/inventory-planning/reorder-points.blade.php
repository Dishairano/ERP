@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Inventory Planning /</span> Reorder Points
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Manage Reorder Points</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('inventory-planning.update-reorder-points') }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Current Stock</th>
                                    <th>Monthly Consumption</th>
                                    <th>Lead Time (Days)</th>
                                    <th>Reorder Point</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->stockLevels->sum('quantity') }}</td>
                                        <td>{{ $product->monthly_consumption ?? 0 }}</td>
                                        <td>{{ $product->lead_time ?? '-' }}</td>
                                        <td>
                                            <input type="number" name="reorder_points[{{ $product->id }}]"
                                                class="form-control form-control-sm"
                                                value="{{ old("reorder_points.{$product->id}", $product->reorder_point) }}"
                                                min="0">
                                        </td>
                                        <td>
                                            @php
                                                $currentStock = $product->stockLevels->sum('quantity');
                                                $status =
                                                    $currentStock <= $product->reorder_point ? 'danger' : 'success';
                                                $statusText =
                                                    $currentStock <= $product->reorder_point
                                                        ? 'Reorder Required'
                                                        : 'OK';
                                            @endphp
                                            <span class="badge bg-label-{{ $status }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            Update Reorder Points
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

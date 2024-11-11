@extends('layouts/contentNavbarLayout')

@section('title', 'BOM Cost Analysis')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Bill of Materials /</span> Cost Analysis
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">BOM Cost Analysis</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Version</th>
                                <th>Material Cost</th>
                                <th>Labor Cost</th>
                                <th>Overhead Cost</th>
                                <th>Total Cost</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boms as $bom)
                                <tr>
                                    <td>{{ $bom->id }}</td>
                                    <td>{{ $bom->name }}</td>
                                    <td>{{ $bom->version }}</td>
                                    <td>{{ number_format($bom->material_cost, 2) }}</td>
                                    <td>{{ number_format($bom->labor_cost, 2) }}</td>
                                    <td>{{ number_format($bom->overhead_cost, 2) }}</td>
                                    <td>{{ number_format($bom->total_cost, 2) }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-eye-line me-2"></i> View Details
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-file-chart-line me-2"></i> Cost Breakdown
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-history-line me-2"></i> Cost History
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $boms->links() }}
            </div>
        </div>
    </div>
@endsection

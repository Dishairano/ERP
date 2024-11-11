@extends('layouts/contentNavbarLayout')

@section('title', 'Non-Conformance')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manufacturing Quality /</span> Non-Conformance
        </h4>

        <div class="row">
            <!-- Non-Conformance Reports -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Non-Conformance Reports</h5>
                        <button type="button" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i> New Report
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>NCR ID</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Severity</th>
                                        <th>Reported By</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nonConformances as $ncr)
                                        <tr>
                                            <td>{{ $ncr->id }}</td>
                                            <td>{{ $ncr->product_name }}</td>
                                            <td>{{ $ncr->type }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $ncr->severity === 'Critical' ? 'danger' : ($ncr->severity === 'Major' ? 'warning' : 'info') }}">
                                                    {{ $ncr->severity }}
                                                </span>
                                            </td>
                                            <td>{{ $ncr->reported_by }}</td>
                                            <td>{{ $ncr->reported_date }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $ncr->status === 'Resolved' ? 'success' : ($ncr->status === 'In Progress' ? 'warning' : 'danger') }}">
                                                    {{ $ncr->status }}
                                                </span>
                                            </td>
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
                                                            <i class="ri-pencil-line me-2"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-file-list-3-line me-2"></i> Add Action
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-check-line me-2"></i> Close Report
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $nonConformances->links() }}
                    </div>
                </div>
            </div>

            <!-- Corrective Actions -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Corrective Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Action ID</th>
                                        <th>NCR Reference</th>
                                        <th>Action Type</th>
                                        <th>Description</th>
                                        <th>Assigned To</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($correctiveActions as $action)
                                        <tr>
                                            <td>{{ $action->id }}</td>
                                            <td>{{ $action->ncr_reference }}</td>
                                            <td>{{ $action->type }}</td>
                                            <td>{{ Str::limit($action->description, 50) }}</td>
                                            <td>{{ $action->assigned_to }}</td>
                                            <td>{{ $action->due_date }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $action->status === 'Completed' ? 'success' : ($action->status === 'In Progress' ? 'warning' : 'info') }}">
                                                    {{ $action->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary">
                                                    Update Status
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $correctiveActions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

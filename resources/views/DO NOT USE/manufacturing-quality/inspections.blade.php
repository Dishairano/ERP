@extends('layouts/contentNavbarLayout')

@section('title', 'Quality Inspections')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manufacturing Quality /</span> Inspections
        </h4>

        <div class="row">
            <!-- Quality Inspections List -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Quality Inspections</h5>
                        <button type="button" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i> New Inspection
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Inspection ID</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Inspector</th>
                                        <th>Date</th>
                                        <th>Result</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inspections as $inspection)
                                        <tr>
                                            <td>{{ $inspection->id }}</td>
                                            <td>{{ $inspection->product_name }}</td>
                                            <td>{{ $inspection->type }}</td>
                                            <td>{{ $inspection->inspector_name }}</td>
                                            <td>{{ $inspection->inspection_date }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $inspection->result === 'Pass'
                                                        ? 'success'
                                                        : ($inspection->result === 'Conditional Pass'
                                                            ? 'warning'
                                                            : 'danger') }}">
                                                    {{ $inspection->result }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $inspection->status === 'Completed'
                                                        ? 'success'
                                                        : ($inspection->status === 'In Progress'
                                                            ? 'info'
                                                            : 'warning') }}">
                                                    {{ $inspection->status }}
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
                                                            <i class="ri-file-list-3-line me-2"></i> Generate Report
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $inspections->links() }}
                    </div>
                </div>
            </div>

            <!-- Recent Issues -->
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Quality Issues</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Issue ID</th>
                                        <th>Description</th>
                                        <th>Severity</th>
                                        <th>Reported Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($qualityIssues as $issue)
                                        <tr>
                                            <td>{{ $issue->id }}</td>
                                            <td>{{ $issue->description }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $issue->severity === 'High' ? 'danger' : ($issue->severity === 'Medium' ? 'warning' : 'info') }}">
                                                    {{ $issue->severity }}
                                                </span>
                                            </td>
                                            <td>{{ $issue->reported_date }}</td>
                                            <td>{{ $issue->status }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Quality Standards')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manufacturing Quality /</span> Standards
        </h4>

        <div class="row">
            <!-- Quality Standards List -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Quality Standards</h5>
                        <button type="button" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i> New Standard
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Standard ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Version</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($standards as $standard)
                                        <tr>
                                            <td>{{ $standard->id }}</td>
                                            <td>{{ $standard->name }}</td>
                                            <td>{{ $standard->category }}</td>
                                            <td>{{ Str::limit($standard->description, 50) }}</td>
                                            <td>v{{ $standard->version }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $standard->status === 'Active' ? 'success' : ($standard->status === 'Draft' ? 'warning' : 'danger') }}">
                                                    {{ $standard->status }}
                                                </span>
                                            </td>
                                            <td>{{ $standard->updated_at->format('Y-m-d') }}</td>
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
                                                            <i class="ri-git-branch-line me-2"></i> Create New Version
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-archive-line me-2"></i> Archive
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $standards->links() }}
                    </div>
                </div>
            </div>

            <!-- Compliance Matrix -->
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Standards Compliance Matrix</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Category</th>
                                        <th>Applicable Standards</th>
                                        <th>Compliance Level</th>
                                        <th>Last Audit</th>
                                        <th>Next Audit</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complianceMatrix as $item)
                                        <tr>
                                            <td>{{ $item->category }}</td>
                                            <td>{{ $item->standards_count }} Standards</td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $item->compliance_level }}%"
                                                        aria-valuenow="{{ $item->compliance_level }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $item->compliance_level }}%</small>
                                            </td>
                                            <td>{{ $item->last_audit_date }}</td>
                                            <td>{{ $item->next_audit_date }}</td>
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

@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Requirements')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Compliance /</span> Requirements
        </h4>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Compliance Requirements</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createRequirementModal">
                            <i class="ri-add-line"></i> Add Requirement
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>Priority</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($requirements as $requirement)
                                        <tr>
                                            <td>{{ $requirement->id }}</td>
                                            <td>{{ $requirement->title }}</td>
                                            <td>{{ $requirement->category }}</td>
                                            <td>
                                                <span class="badge bg-{{ $requirement->status_color }}">
                                                    {{ $requirement->status }}
                                                </span>
                                            </td>
                                            <td>{{ $requirement->due_date->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $requirement->priority_color }}">
                                                    {{ $requirement->priority }}
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
                                                            <i class="ri-delete-bin-line me-2"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No requirements found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Requirement Modal -->
    <div class="modal fade" id="createRequirementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Requirement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('compliance.requirements.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Legal">Legal</option>
                                    <option value="Regulatory">Regulatory</option>
                                    <option value="Internal">Internal</option>
                                    <option value="Industry">Industry Standards</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Requirement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Add any page-specific JavaScript here
    </script>
@endsection

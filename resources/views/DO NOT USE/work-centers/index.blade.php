@extends('layouts/contentNavbarLayout')

@section('title', 'Work Centers Overview')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Work Centers /</span> Overview
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Work Centers List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Capacity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workCenters as $workCenter)
                                <tr>
                                    <td>{{ $workCenter->id }}</td>
                                    <td>{{ $workCenter->name }}</td>
                                    <td>{{ $workCenter->type }}</td>
                                    <td>{{ $workCenter->location }}</td>
                                    <td>
                                        <span class="badge bg-{{ $workCenter->status === 'Active' ? 'success' : 'danger' }}">
                                            {{ $workCenter->status }}
                                        </span>
                                    </td>
                                    <td>{{ $workCenter->capacity }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0);">
                                                    <i class="ri-eye-line me-2"></i> View
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $workCenters->links() }}
            </div>
        </div>
    </div>
@endsection

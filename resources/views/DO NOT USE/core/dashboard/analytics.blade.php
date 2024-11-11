@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Analytics')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Dashboard Analytics</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createDashboardModal">
                            <i class="ri-add-line"></i> Create New Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            @foreach ($dashboards as $dashboard)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title mb-0">{{ $dashboard->title }}</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#editDashboardModal{{ $dashboard->id }}">
                                            <i class="ri-edit-line me-1"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('dashboard.toggle-active', $dashboard) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                <i class="ri-toggle-line me-1"></i>
                                                {{ $dashboard->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('dashboard.destroy', $dashboard) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ri-delete-bin-line me-1"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $dashboard->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-label-primary">{{ $dashboard->category->name }}</span>
                                <small class="text-muted">Refresh: {{ $dashboard->refresh_interval }}s</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Created by: {{ $dashboard->creator->name }}</small>
                                <small class="text-muted">{{ $dashboard->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Create Dashboard Modal -->
    <div class="modal fade" id="createDashboardModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('dashboard.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Dashboard</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category_id" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Refresh Interval (seconds)</label>
                                <input type="number" class="form-control" name="refresh_interval" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Dashboard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Dashboard Modals -->
    @foreach ($dashboards as $dashboard)
        <div class="modal fade" id="editDashboardModal{{ $dashboard->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form action="{{ route('dashboard.update', $dashboard) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Dashboard</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ $dashboard->title }}" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3">{{ $dashboard->description }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category_id" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $dashboard->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Refresh Interval (seconds)</label>
                                    <input type="number" class="form-control" name="refresh_interval" min="0"
                                        value="{{ $dashboard->refresh_interval }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Dashboard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('page-script')
    <script>
        // Add any custom JavaScript for the dashboard page here
    </script>
@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Projects')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Projects</h4>
                                <p class="mb-0">Manage and track all your projects</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line"></i> New Project
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="on-hold">On Hold</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Priority</label>
                                <select class="form-select">
                                    <option value="">All Priorities</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" placeholder="Search projects...">
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-3-line"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row">
            @forelse($projects ?? [] as $project)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(substr($project->name ?? 'P', 0, 1)) }}
                                        </span>
                                    </div>
                                    <h5 class="card-title mb-0">{{ $project->name ?? 'Project Name' }}</h5>
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('projects.show', $project ?? 1) }}">View
                                                Details</a></li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('projects.edit', $project ?? 1) }}">Edit</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('projects.destroy', $project ?? 1) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p class="card-text">{{ Str::limit($project->description ?? 'No description available', 100) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-label-primary">{{ $project->status ?? 'Active' }}</span>
                                <small>Due: {{ $project->due_date ?? 'No date set' }}</small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="progress w-100" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $project->progress ?? 0 }}%"></div>
                                </div>
                                <span class="ms-3">{{ $project->progress ?? 0 }}%</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="avatar-group">
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="avatar avatar-xs">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                U{{ $i + 1 }}
                                            </span>
                                        </div>
                                    @endfor
                                </div>
                                <div>
                                    <i class="ri-task-line me-1"></i>
                                    <span>{{ $project->tasks_count ?? 0 }} Tasks</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center p-5">
                            <i class="ri-folder-open-line ri-3x text-primary mb-3"></i>
                            <h5>No Projects Found</h5>
                            <p class="mb-3">Get started by creating your first project</p>
                            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                <i class="ri-add-line"></i> Create Project
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if (!empty($projects))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any necessary JavaScript functionality
        });
    </script>
@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Project Templates')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Project Templates</h5>
                        <a href="{{ route('projects.templates.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i> Create Template
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <select class="form-select" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    <option value="Development">Development</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Research">Research</option>
                                    <option value="Design">Design</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                                    <input type="text" class="form-control" placeholder="Search templates..."
                                        id="searchInput">
                                </div>
                            </div>
                        </div>

                        <!-- Templates Grid -->
                        <div class="row g-4">
                            @forelse ($templates as $template)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="card-title mb-0">
                                                        <a href="{{ route('projects.templates.show', $template) }}"
                                                            class="text-body">
                                                            {{ $template->name }}
                                                        </a>
                                                    </h5>
                                                    <small class="text-muted">{{ $template->category }}</small>
                                                </div>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('projects.templates.show', $template) }}">
                                                            <i class="ri-eye-line me-1"></i> View
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('projects.templates.edit', $template) }}">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('projects.templates.duplicate', $template) }}">
                                                            <i class="ri-file-copy-line me-1"></i> Duplicate
                                                        </a>
                                                        @if ($template->status === 'active')
                                                            <a class="dropdown-item"
                                                                href="{{ route('projects.templates.archive', $template) }}"
                                                                onclick="return confirm('Are you sure you want to archive this template?')">
                                                                <i class="ri-archive-line me-1"></i> Archive
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item"
                                                                href="{{ route('projects.templates.restore', $template) }}">
                                                                <i class="ri-refresh-line me-1"></i> Restore
                                                            </a>
                                                        @endif
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('projects.templates.destroy', $template) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Are you sure you want to delete this template?')">
                                                                <i class="ri-delete-bin-line me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="card-text">{{ Str::limit($template->description, 100) }}</p>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <i class="ri-time-line me-1"></i>
                                                    {{ $template->duration_string }}
                                                </div>
                                                <span
                                                    class="badge bg-label-{{ $template->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($template->status) }}
                                                </span>
                                            </div>

                                            <div class="d-flex justify-content-between text-muted">
                                                <small>
                                                    <i class="ri-task-line me-1"></i>
                                                    {{ $template->default_tasks_count }} Tasks
                                                </small>
                                                <small>
                                                    <i class="ri-flag-line me-1"></i>
                                                    {{ $template->default_milestones_count }} Milestones
                                                </small>
                                            </div>

                                            @if ($template->tags)
                                                <div class="mt-3">
                                                    @foreach ($template->tags as $tag)
                                                        <span
                                                            class="badge bg-label-primary me-1">{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center p-5">
                                        <h4 class="text-muted mb-3">No Templates Found</h4>
                                        <p class="mb-3">Get started by creating your first project template.</p>
                                        <a href="{{ route('projects.templates.create') }}" class="btn btn-primary">
                                            <i class="ri-add-line me-1"></i> Create Template
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $templates->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category filter
            const categoryFilter = document.getElementById('categoryFilter');
            categoryFilter.addEventListener('change', function() {
                applyFilters();
            });

            // Status filter
            const statusFilter = document.getElementById('statusFilter');
            statusFilter.addEventListener('change', function() {
                applyFilters();
            });

            // Search input
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(applyFilters, 500);
            });

            function applyFilters() {
                const category = categoryFilter.value;
                const status = statusFilter.value;
                const search = searchInput.value;

                const url = new URL(window.location.href);
                if (category) url.searchParams.set('category', category);
                else url.searchParams.delete('category');

                if (status) url.searchParams.set('status', status);
                else url.searchParams.delete('status');

                if (search) url.searchParams.set('search', search);
                else url.searchParams.delete('search');

                window.location.href = url.toString();
            }

            // Set initial filter values from URL
            const urlParams = new URLSearchParams(window.location.search);
            categoryFilter.value = urlParams.get('category') || '';
            statusFilter.value = urlParams.get('status') || '';
            searchInput.value = urlParams.get('search') || '';
        });
    </script>
@endsection

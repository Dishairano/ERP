@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Project Management /</span> Project Templates
        </h4>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Total Templates</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ $templateStats['total'] }}</h3>
                                    <span class="badge bg-label-primary">
                                        {{ $templateStats['active'] }} Active
                                    </span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri-file-list-3-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Usage Count</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ $templateStats['usage_count'] }}</h3>
                                    <span class="badge bg-label-success">Projects</span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri-folder-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Avg Tasks</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ round($templateStats['avg_tasks']) }}</h3>
                                    <span class="badge bg-label-info">Per Template</span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ri-task-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Most Used</span>
                                <div class="d-flex align-items-center">
                                    @if ($templateStats['most_used'])
                                        <h3 class="mb-0 me-2">{{ $templateStats['most_used']->projects_count }}</h3>
                                        <span class="badge bg-label-warning">Projects</span>
                                    @else
                                        <h3 class="mb-0 me-2">0</h3>
                                        <span class="badge bg-label-secondary">No Data</span>
                                    @endif
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ri-star-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Templates List -->
        <div class="card">
            <div class="card-header border-bottom">
                <div class="card-title mb-3 mb-md-0">
                    <h5 class="m-0">Project Templates</h5>
                </div>
                <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                    <div class="col-md-4 user_role"></div>
                    <div class="col-md-4 user_plan"></div>
                    <div class="col-md-4 user_status">
                        <button class="btn btn-primary"
                            onclick="window.location.href='{{ route('projects.templates.create') }}'">
                            <i class="ri-add-line me-1"></i> Create Template
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Template Name</th>
                                <th>Category</th>
                                <th>Duration</th>
                                <th>Tasks</th>
                                <th>Projects</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templates as $template)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="avatar-wrapper">
                                                <div class="avatar me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($template->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium">{{ $template->name }}</span>
                                                <small class="text-muted">Created
                                                    {{ $template->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $template->category }}</td>
                                    <td>{{ $template->estimated_duration }}
                                        {{ Str::plural($template->duration_unit, $template->estimated_duration) }}</td>
                                    <td>{{ $template->tasks_count }}</td>
                                    <td>{{ $template->projects_count }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $template->is_active ? 'success' : 'secondary' }}">
                                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('projects.templates.show', $template) }}"
                                                class="btn btn-icon btn-text-secondary rounded-pill btn-sm me-2">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('projects.templates.edit', $template) }}"
                                                class="btn btn-icon btn-text-secondary rounded-pill btn-sm me-2">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            @if ($template->projects_count === 0)
                                                <form action="{{ route('projects.templates.destroy', $template) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-icon btn-text-secondary rounded-pill btn-sm">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $templates->links() }}
                </div>
            </div>
        </div>

        <!-- Recent Templates -->
        @if ($templateStats['recent_templates']->isNotEmpty())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recently Added Templates</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @foreach ($templateStats['recent_templates'] as $template)
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card shadow-none border">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($template->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="me-2">
                                                <h5 class="mb-0">
                                                    <a href="{{ route('projects.templates.show', $template) }}"
                                                        class="text-body">
                                                        {{ $template->name }}
                                                    </a>
                                                </h5>
                                                <small class="text-muted">{{ $template->category }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-3">{{ Str::limit($template->description, 100) }}</p>
                                        <div class="d-flex align-items-center pt-1">
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">
                                                    <i class="ri-time-line text-muted"></i>
                                                </span>
                                                <span class="text-muted">
                                                    {{ $template->estimated_duration }}
                                                    {{ Str::plural($template->duration_unit, $template->estimated_duration) }}
                                                </span>
                                            </div>
                                            <div class="ms-auto">
                                                <a href="{{ route('projects.templates.edit', $template) }}"
                                                    class="btn btn-text-secondary btn-icon rounded-pill">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

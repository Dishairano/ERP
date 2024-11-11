@extends('layouts/contentNavbarLayout')

@section('title', 'Projects')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Projects</h4>
                    <div class="heading-elements d-flex gap-2">
                        <a href="{{ route('projects.risks.overall-report') }}" class="btn btn-secondary">
                            <i class="ri-file-chart-line me-1"></i>
                            Risk Report
                        </a>
                        <a href="{{ route('projects.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>
                            Create Project
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Manager</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                    <tr>
                                        <td>
                                            <a href="{{ route('projects.show', $project) }}">
                                                {{ $project->name }}
                                            </a>
                                        </td>
                                        <td>{{ $project->manager->name }}</td>
                                        <td>{{ $project->start_date->format('M d, Y') }}</td>
                                        <td>{{ $project->end_date->format('M d, Y') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'planning' ? 'info' : 'warning') }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $project->priority === 'high' ? 'danger' : ($project->priority === 'medium' ? 'warning' : 'info') }}">
                                                {{ ucfirst($project->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 6px">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $project->progress_percentage }}%"
                                                    aria-valuenow="{{ $project->progress_percentage }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small
                                                class="text-muted">{{ number_format($project->progress_percentage, 1) }}%</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('projects.show', $project) }}">
                                                        <i class="ri-eye-line me-1"></i>
                                                        <span>View</span>
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('projects.edit', $project) }}">
                                                        <i class="ri-pencil-line me-1"></i>
                                                        <span>Edit</span>
                                                    </a>
                                                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="ri-delete-bin-line me-1"></i>
                                                            <span>Delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No projects found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-2">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

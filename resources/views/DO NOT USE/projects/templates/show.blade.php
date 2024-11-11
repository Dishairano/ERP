@extends('layouts/contentNavbarLayout')

@section('title', 'View Project Template')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">{{ $template->name }}</h5>
                            <div>
                                <a href="{{ route('projects.templates.edit', $template) }}" class="btn btn-primary">
                                    <i class="ri-pencil-line"></i> Edit Template
                                </a>
                                <form action="{{ route('projects.templates.duplicate', $template) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="ri-file-copy-line"></i> Duplicate
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h6>Description</h6>
                                <p>{{ $template->description }}</p>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Default Phases</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @forelse(json_decode($template->default_phases ?? '[]', true) as $phase)
                                                <li class="list-group-item">{{ $phase }}</li>
                                            @empty
                                                <li class="list-group-item text-muted">No phases defined</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Default Tasks</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @forelse(json_decode($template->default_tasks ?? '[]', true) as $task)
                                                <li class="list-group-item">{{ $task }}</li>
                                            @empty
                                                <li class="list-group-item text-muted">No tasks defined</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Default Milestones</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @forelse(json_decode($template->default_milestones ?? '[]', true) as $milestone)
                                                <li class="list-group-item">{{ $milestone }}</li>
                                            @empty
                                                <li class="list-group-item text-muted">No milestones defined</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Default Team Structure</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @forelse(json_decode($template->default_team_structure ?? '[]', true) as $role)
                                                <li class="list-group-item">{{ $role }}</li>
                                            @empty
                                                <li class="list-group-item text-muted">No roles defined</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Template Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Status:</strong>
                                                    @if ($template->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </p>
                                                <p><strong>Created By:</strong> {{ $template->creator->name ?? 'N/A' }}</p>
                                                <p><strong>Created At:</strong>
                                                    {{ $template->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Last Updated By:</strong>
                                                    {{ $template->updater->name ?? 'N/A' }}</p>
                                                <p><strong>Last Updated At:</strong>
                                                    {{ $template->updated_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Create Risk')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Projects / Risks /</span> Create
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">New Risk</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('projects.risks.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="project_id">Project</label>
                                    <select class="form-select @error('project_id') is-invalid @enderror" id="project_id"
                                        name="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="title">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="priority">Priority</label>
                                    <select class="form-select @error('priority') is-invalid @enderror" id="priority"
                                        name="priority">
                                        <option value="">Select Priority</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium
                                        </option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High
                                        </option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open
                                        </option>
                                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                                            In Progress</option>
                                        <option value="mitigated" {{ old('status') == 'mitigated' ? 'selected' : '' }}>
                                            Mitigated</option>
                                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="identified_date">Identified Date</label>
                                    <input type="date"
                                        class="form-control @error('identified_date') is-invalid @enderror"
                                        id="identified_date" name="identified_date"
                                        value="{{ old('identified_date', date('Y-m-d')) }}" required>
                                    @error('identified_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="resolution_date">Resolution Date</label>
                                    <input type="date"
                                        class="form-control @error('resolution_date') is-invalid @enderror"
                                        id="resolution_date" name="resolution_date" value="{{ old('resolution_date') }}">
                                    @error('resolution_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="mitigation_strategy">Mitigation Strategy</label>
                                <textarea class="form-control @error('mitigation_strategy') is-invalid @enderror" id="mitigation_strategy"
                                    name="mitigation_strategy" rows="3">{{ old('mitigation_strategy') }}</textarea>
                                @error('mitigation_strategy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Create Risk</button>
                                <a href="{{ route('projects.risks') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

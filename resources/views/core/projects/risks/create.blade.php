@extends('layouts/contentNavbarLayout')

@section('title', 'Create Risk')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Create New Risk</h5>
                        <a href="{{ route('projects.risks.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Risks
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('projects.risks.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
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

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="title">Risk Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="category">Category</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category"
                                        name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Technical" {{ old('category') == 'Technical' ? 'selected' : '' }}>
                                            Technical</option>
                                        <option value="Schedule" {{ old('category') == 'Schedule' ? 'selected' : '' }}>
                                            Schedule</option>
                                        <option value="Cost" {{ old('category') == 'Cost' ? 'selected' : '' }}>Cost
                                        </option>
                                        <option value="Resource" {{ old('category') == 'Resource' ? 'selected' : '' }}>
                                            Resource</option>
                                        <option value="Scope" {{ old('category') == 'Scope' ? 'selected' : '' }}>Scope
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="severity">Severity (1-5)</label>
                                    <input type="number" class="form-control @error('severity') is-invalid @enderror"
                                        id="severity" name="severity" min="1" max="5"
                                        value="{{ old('severity') }}" required>
                                    @error('severity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="likelihood">Likelihood (1-5)</label>
                                    <input type="number" class="form-control @error('likelihood') is-invalid @enderror"
                                        id="likelihood" name="likelihood" min="1" max="5"
                                        value="{{ old('likelihood') }}" required>
                                    @error('likelihood')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="impact">Impact</label>
                                    <textarea class="form-control @error('impact') is-invalid @enderror" id="impact" name="impact" rows="2"
                                        required>{{ old('impact') }}</textarea>
                                    @error('impact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="mitigation_strategy">Mitigation Strategy</label>
                                    <textarea class="form-control @error('mitigation_strategy') is-invalid @enderror" id="mitigation_strategy"
                                        name="mitigation_strategy" rows="3" required>{{ old('mitigation_strategy') }}</textarea>
                                    @error('mitigation_strategy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="identified" {{ old('status') == 'identified' ? 'selected' : '' }}>
                                            Identified</option>
                                        <option value="assessed" {{ old('status') == 'assessed' ? 'selected' : '' }}>
                                            Assessed</option>
                                        <option value="mitigated" {{ old('status') == 'mitigated' ? 'selected' : '' }}>
                                            Mitigated</option>
                                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="due_date">Due Date</label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                        id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="owner">Risk Owner</label>
                                    <input type="text" class="form-control @error('owner') is-invalid @enderror"
                                        id="owner" name="owner" value="{{ old('owner') }}" required>
                                    @error('owner')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary me-2">Create Risk</button>
                                <a href="{{ route('projects.risks.index') }}" class="btn btn-label-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        // Add any custom JavaScript for form validation or enhancement here
    </script>
@endsection

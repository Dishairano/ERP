@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Risk')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Edit Risk</h4>
                            <small class="text-muted">Project: {{ $project->name }}</small>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('projects.risks.show', [$project->id, $risk->id]) }}"
                                class="btn btn-outline-secondary">
                                <i data-feather="x"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.risks.update', [$project->id, $risk->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $risk->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required>{{ old('description', $risk->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="category">Category</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category"
                                        name="category" required>
                                        <option value="technical"
                                            {{ old('category', $risk->category) === 'technical' ? 'selected' : '' }}>
                                            Technical</option>
                                        <option value="schedule"
                                            {{ old('category', $risk->category) === 'schedule' ? 'selected' : '' }}>Schedule
                                        </option>
                                        <option value="resource"
                                            {{ old('category', $risk->category) === 'resource' ? 'selected' : '' }}>Resource
                                        </option>
                                        <option value="cost"
                                            {{ old('category', $risk->category) === 'cost' ? 'selected' : '' }}>Cost
                                        </option>
                                        <option value="quality"
                                            {{ old('category', $risk->category) === 'quality' ? 'selected' : '' }}>Quality
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="severity">Severity (1-5)</label>
                                            <input type="number"
                                                class="form-control @error('severity') is-invalid @enderror" id="severity"
                                                name="severity" min="1" max="5"
                                                value="{{ old('severity', $risk->severity) }}" required>
                                            @error('severity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="likelihood">Likelihood (1-5)</label>
                                            <input type="number"
                                                class="form-control @error('likelihood') is-invalid @enderror"
                                                id="likelihood" name="likelihood" min="1" max="5"
                                                value="{{ old('likelihood', $risk->likelihood) }}" required>
                                            @error('likelihood')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="impact">Impact</label>
                                    <textarea class="form-control @error('impact') is-invalid @enderror" id="impact" name="impact" rows="2"
                                        required>{{ old('impact', $risk->impact) }}</textarea>
                                    @error('impact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="mitigation_strategy">Mitigation Strategy</label>
                                    <textarea class="form-control @error('mitigation_strategy') is-invalid @enderror" id="mitigation_strategy"
                                        name="mitigation_strategy" rows="2" required>{{ old('mitigation_strategy', $risk->mitigation_strategy) }}</textarea>
                                    @error('mitigation_strategy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="due_date">Due Date</label>
                                            <input type="date"
                                                class="form-control @error('due_date') is-invalid @enderror" id="due_date"
                                                name="due_date"
                                                value="{{ old('due_date', $risk->due_date->format('Y-m-d')) }}" required>
                                            @error('due_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="owner">Owner</label>
                                            <input type="text"
                                                class="form-control @error('owner') is-invalid @enderror" id="owner"
                                                name="owner" value="{{ old('owner', $risk->owner) }}" required>
                                            @error('owner')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="identified"
                                            {{ old('status', $risk->status) === 'identified' ? 'selected' : '' }}>
                                            Identified</option>
                                        <option value="assessed"
                                            {{ old('status', $risk->status) === 'assessed' ? 'selected' : '' }}>Assessed
                                        </option>
                                        <option value="mitigated"
                                            {{ old('status', $risk->status) === 'mitigated' ? 'selected' : '' }}>Mitigated
                                        </option>
                                        <option value="closed"
                                            {{ old('status', $risk->status) === 'closed' ? 'selected' : '' }}>Closed
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-1">Update Risk</button>
                                    <a href="{{ route('projects.risks.show', [$project->id, $risk->id]) }}"
                                        class="btn btn-outline-secondary">Cancel</a>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Current Risk Level</h6>
                                        @php
                                            $riskLevel = $risk->severity * $risk->likelihood;
                                            $severityClass =
                                                $riskLevel >= 16
                                                    ? 'danger'
                                                    : ($riskLevel >= 9
                                                        ? 'warning'
                                                        : ($riskLevel >= 4
                                                            ? 'info'
                                                            : 'success'));
                                        @endphp
                                        <div class="d-flex align-items-center mt-3">
                                            <div class="badge rounded-pill badge-light-{{ $severityClass }} me-2">
                                                {{ $riskLevel }} / 25
                                            </div>
                                            <small class="text-muted">
                                                (Severity × Likelihood)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(function() {
            'use strict';

            // Initialize feather icons
            feather.replace();
        });
    </script>
@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Project')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Project</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('projects.update', $project) }}" class="validate-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="name">Project Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $project->name) }}" required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="manager_id">Project Manager</label>
                                    <select class="form-select @error('manager_id') is-invalid @enderror" id="manager_id"
                                        name="manager_id" required>
                                        <option value="">Select Manager</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('manager_id', $project->manager_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('manager_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="start_date">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date"
                                        value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" required />
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="end_date">End Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="end_date" name="end_date"
                                        value="{{ old('end_date', $project->end_date->format('Y-m-d')) }}" required />
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="planning"
                                            {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning
                                        </option>
                                        <option value="active"
                                            {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="on-hold"
                                            {{ old('status', $project->status) == 'on-hold' ? 'selected' : '' }}>On Hold
                                        </option>
                                        <option value="completed"
                                            {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled"
                                            {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="priority">Priority</label>
                                    <select class="form-select @error('priority') is-invalid @enderror" id="priority"
                                        name="priority" required>
                                        <option value="low"
                                            {{ old('priority', $project->priority) == 'low' ? 'selected' : '' }}>Low
                                        </option>
                                        <option value="medium"
                                            {{ old('priority', $project->priority) == 'medium' ? 'selected' : '' }}>Medium
                                        </option>
                                        <option value="high"
                                            {{ old('priority', $project->priority) == 'high' ? 'selected' : '' }}>High
                                        </option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="budget">Budget</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('budget') is-invalid @enderror" id="budget"
                                        name="budget" value="{{ old('budget', $project->budget) }}" required />
                                    @error('budget')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3">{{ old('description', $project->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-1">Update Project</button>
                                <a href="{{ route('projects.show', $project) }}"
                                    class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

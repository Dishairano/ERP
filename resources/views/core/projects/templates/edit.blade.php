@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Project Template')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Project Template</h5>
                        <a href="{{ route('projects.templates.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Templates
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('projects.templates.update', $template) }}" method="POST" id="templateForm">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="name">Template Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $template->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="category">Category</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category"
                                        name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Development"
                                            {{ old('category', $template->category) == 'Development' ? 'selected' : '' }}>
                                            Development</option>
                                        <option value="Marketing"
                                            {{ old('category', $template->category) == 'Marketing' ? 'selected' : '' }}>
                                            Marketing</option>
                                        <option value="Research"
                                            {{ old('category', $template->category) == 'Research' ? 'selected' : '' }}>
                                            Research</option>
                                        <option value="Design"
                                            {{ old('category', $template->category) == 'Design' ? 'selected' : '' }}>Design
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required>{{ old('description', $template->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="estimated_duration">Estimated Duration</label>
                                    <input type="number"
                                        class="form-control @error('estimated_duration') is-invalid @enderror"
                                        id="estimated_duration" name="estimated_duration"
                                        value="{{ old('estimated_duration', $template->estimated_duration) }}"
                                        min="1" required>
                                    @error('estimated_duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="duration_unit">Duration Unit</label>
                                    <select class="form-select @error('duration_unit') is-invalid @enderror"
                                        id="duration_unit" name="duration_unit" required>
                                        <option value="days"
                                            {{ old('duration_unit', $template->duration_unit) == 'days' ? 'selected' : '' }}>
                                            Days</option>
                                        <option value="weeks"
                                            {{ old('duration_unit', $template->duration_unit) == 'weeks' ? 'selected' : '' }}>
                                            Weeks</option>
                                        <option value="months"
                                            {{ old('duration_unit', $template->duration_unit) == 'months' ? 'selected' : '' }}>
                                            Months</option>
                                    </select>
                                    @error('duration_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="tags">Tags</label>
                                    <select class="select2 form-select @error('tags') is-invalid @enderror" id="tags"
                                        name="tags[]" multiple>
                                        @php $currentTags = old('tags', $template->tags ?? []); @endphp
                                        <option value="Agile" {{ in_array('Agile', $currentTags) ? 'selected' : '' }}>
                                            Agile</option>
                                        <option value="Waterfall"
                                            {{ in_array('Waterfall', $currentTags) ? 'selected' : '' }}>Waterfall</option>
                                        <option value="Sprint" {{ in_array('Sprint', $currentTags) ? 'selected' : '' }}>
                                            Sprint</option>
                                        <option value="MVP" {{ in_array('MVP', $currentTags) ? 'selected' : '' }}>MVP
                                        </option>
                                    </select>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Default Tasks -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6>Default Tasks</h6>
                                    <div id="defaultTasks">
                                        @foreach (old('default_tasks', $template->default_tasks ?? []) as $index => $task)
                                            <div class="task-item mb-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Task Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="default_tasks[{{ $index }}][name]"
                                                                    value="{{ $task['name'] }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Priority</label>
                                                                <select class="form-select"
                                                                    name="default_tasks[{{ $index }}][priority]"
                                                                    required>
                                                                    <option value="low"
                                                                        {{ $task['priority'] == 'low' ? 'selected' : '' }}>
                                                                        Low</option>
                                                                    <option value="medium"
                                                                        {{ $task['priority'] == 'medium' ? 'selected' : '' }}>
                                                                        Medium</option>
                                                                    <option value="high"
                                                                        {{ $task['priority'] == 'high' ? 'selected' : '' }}>
                                                                        High</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label">Description</label>
                                                                <textarea class="form-control" name="default_tasks[{{ $index }}][description]" rows="2" required>{{ $task['description'] }}</textarea>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Duration</label>
                                                                <input type="number" class="form-control"
                                                                    name="default_tasks[{{ $index }}][duration]"
                                                                    value="{{ $task['duration'] }}" min="1"
                                                                    required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Duration Unit</label>
                                                                <select class="form-select"
                                                                    name="default_tasks[{{ $index }}][duration_unit]"
                                                                    required>
                                                                    <option value="hours"
                                                                        {{ $task['duration_unit'] == 'hours' ? 'selected' : '' }}>
                                                                        Hours</option>
                                                                    <option value="days"
                                                                        {{ $task['duration_unit'] == 'days' ? 'selected' : '' }}>
                                                                        Days</option>
                                                                    <option value="weeks"
                                                                        {{ $task['duration_unit'] == 'weeks' ? 'selected' : '' }}>
                                                                        Weeks</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @if (!$loop->first)
                                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                                onclick="this.closest('.task-item').remove()">
                                                                <i class="ri-delete-bin-line me-1"></i> Remove Task
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addTask()">
                                        <i class="ri-add-line me-1"></i> Add Task
                                    </button>
                                </div>
                            </div>

                            <!-- Default Milestones -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6>Default Milestones</h6>
                                    <div id="defaultMilestones">
                                        @foreach (old('default_milestones', $template->default_milestones ?? []) as $index => $milestone)
                                            <div class="milestone-item mb-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Milestone Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="default_milestones[{{ $index }}][name]"
                                                                    value="{{ $milestone['name'] }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Due Day</label>
                                                                <input type="number" class="form-control"
                                                                    name="default_milestones[{{ $index }}][due_day]"
                                                                    value="{{ $milestone['due_day'] }}" min="1"
                                                                    required>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label">Description</label>
                                                                <textarea class="form-control" name="default_milestones[{{ $index }}][description]" rows="2" required>{{ $milestone['description'] }}</textarea>
                                                            </div>
                                                        </div>
                                                        @if (!$loop->first)
                                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                                onclick="this.closest('.milestone-item').remove()">
                                                                <i class="ri-delete-bin-line me-1"></i> Remove Milestone
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addMilestone()">
                                        <i class="ri-add-line me-1"></i> Add Milestone
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Update Template</button>
                                <a href="{{ route('projects.templates.index') }}"
                                    class="btn btn-label-secondary">Cancel</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2').select2();
        });

        // The addTask and addMilestone functions are the same as in create.blade.php
        @include('core.projects.templates._form_scripts')
    </script>
@endsection

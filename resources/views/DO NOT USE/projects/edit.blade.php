@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Project - ' . $project->name)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Edit Project</h4>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Project
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('projects.update', $project) }}" method="POST" id="editProjectForm">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5>Basic Information</h5>
                                    <hr>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="name">Project Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $project->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="manager_id">Project Manager</label>
                                    <select class="form-select @error('manager_id') is-invalid @enderror" id="manager_id"
                                        name="manager_id" required>
                                        <option value="">Select Manager</option>
                                        @foreach ($managers as $manager)
                                            <option value="{{ $manager->id }}" @selected(old('manager_id', $project->manager_id) == $manager->id)>
                                                {{ $manager->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('manager_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3">{{ old('description', $project->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="start_date">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date"
                                        value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="end_date">End Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="end_date" name="end_date"
                                        value="{{ old('end_date', $project->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="budget">Budget</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('budget') is-invalid @enderror"
                                            id="budget" name="budget" value="{{ old('budget', $project->budget) }}"
                                            step="0.01" min="0" required>
                                    </div>
                                    @error('budget')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}" @selected(old('status', $project->status) == $status)>
                                                {{ config("project.statuses.$status.name") }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="priority">Priority</label>
                                    <select class="form-select @error('priority') is-invalid @enderror" id="priority"
                                        name="priority" required>
                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority }}" @selected(old('priority', $project->priority) == $priority)>
                                                {{ config("project.tasks.priorities.$priority.name") }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Project Phases -->
                            <div class="row mb-4">
                                <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Project Phases</h5>
                                    <button type="button" class="btn btn-primary btn-sm" id="addPhaseBtn">
                                        <i class="ri-add-line me-1"></i> Add Phase
                                    </button>
                                </div>

                                <div class="col-12">
                                    <div id="phasesContainer">
                                        @foreach ($project->phases as $index => $phase)
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h6 class="mb-0">Phase {{ $index + 1 }}</h6>
                                                        <button type="button" class="btn btn-danger btn-sm remove-phase">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-3">
                                                        <input type="hidden" name="phases[{{ $index }}][id]"
                                                            value="{{ $phase->id }}">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Phase Name</label>
                                                            <input type="text" class="form-control"
                                                                name="phases[{{ $index }}][name]"
                                                                value="{{ $phase->name }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Description</label>
                                                            <input type="text" class="form-control"
                                                                name="phases[{{ $index }}][description]"
                                                                value="{{ $phase->description }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Start Date</label>
                                                            <input type="date" class="form-control"
                                                                name="phases[{{ $index }}][start_date]"
                                                                value="{{ $phase->start_date->format('Y-m-d') }}"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">End Date</label>
                                                            <input type="date" class="form-control"
                                                                name="phases[{{ $index }}][end_date]"
                                                                value="{{ $phase->end_date->format('Y-m-d') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Fields -->
                            <div class="row mb-4">
                                <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Custom Fields</h5>
                                    <button type="button" class="btn btn-primary btn-sm" id="addCustomFieldBtn">
                                        <i class="ri-add-line me-1"></i> Add Custom Field
                                    </button>
                                </div>

                                <div class="col-12">
                                    <div id="customFieldsContainer">
                                        @foreach ($project->custom_fields ?? [] as $key => $value)
                                            <div class="row mb-3 align-items-end">
                                                <div class="col-md-5">
                                                    <label class="form-label">Field Name</label>
                                                    <input type="text" class="form-control"
                                                        name="custom_fields[{{ $loop->index }}][name]"
                                                        value="{{ $key }}" required>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">Value</label>
                                                    <input type="text" class="form-control"
                                                        name="custom_fields[{{ $loop->index }}][value]"
                                                        value="{{ $value }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger remove-custom-field">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary me-2">Update Project</button>
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Phase Management
            const phasesContainer = document.getElementById('phasesContainer');
            const addPhaseBtn = document.getElementById('addPhaseBtn');
            let phaseCount = {{ $project->phases->count() }};

            function addPhase() {
                const phaseDiv = document.createElement('div');
                phaseDiv.className = 'card mb-3';
                phaseDiv.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Phase ${phaseCount + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-phase">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Phase Name</label>
                        <input type="text" class="form-control" name="phases[${phaseCount}][name]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="phases[${phaseCount}][description]">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="phases[${phaseCount}][start_date]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="phases[${phaseCount}][end_date]" required>
                    </div>
                </div>
            </div>
        `;

                phasesContainer.appendChild(phaseDiv);
                phaseCount++;

                // Add event listener to remove button
                phaseDiv.querySelector('.remove-phase').addEventListener('click', function() {
                    phaseDiv.remove();
                    updatePhaseNumbers();
                });
            }

            function updatePhaseNumbers() {
                const phases = phasesContainer.querySelectorAll('.card');
                phases.forEach((phase, index) => {
                    phase.querySelector('h6').textContent = `Phase ${index + 1}`;
                });
            }

            // Add event listeners to existing remove phase buttons
            document.querySelectorAll('.remove-phase').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.card').remove();
                    updatePhaseNumbers();
                });
            });

            addPhaseBtn.addEventListener('click', addPhase);

            // Custom Fields Management
            const customFieldsContainer = document.getElementById('customFieldsContainer');
            const addCustomFieldBtn = document.getElementById('addCustomFieldBtn');
            let customFieldCount = {{ count($project->custom_fields ?? []) }};

            function addCustomField() {
                const fieldDiv = document.createElement('div');
                fieldDiv.className = 'row mb-3 align-items-end';
                fieldDiv.innerHTML = `
            <div class="col-md-5">
                <label class="form-label">Field Name</label>
                <input type="text" class="form-control" name="custom_fields[${customFieldCount}][name]" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">Value</label>
                <input type="text" class="form-control" name="custom_fields[${customFieldCount}][value]" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-custom-field">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        `;

                customFieldsContainer.appendChild(fieldDiv);
                customFieldCount++;

                // Add event listener to remove button
                fieldDiv.querySelector('.remove-custom-field').addEventListener('click', function() {
                    fieldDiv.remove();
                });
            }

            // Add event listeners to existing remove custom field buttons
            document.querySelectorAll('.remove-custom-field').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.row').remove();
                });
            });

            addCustomFieldBtn.addEventListener('click', addCustomField);

            // Form Validation
            const form = document.getElementById('editProjectForm');
            form.addEventListener('submit', function(e) {
                const startDate = new Date(document.getElementById('start_date').value);
                const endDate = new Date(document.getElementById('end_date').value);

                if (endDate <= startDate) {
                    e.preventDefault();
                    alert('End date must be after start date');
                }
            });
        });
    </script>
@endpush

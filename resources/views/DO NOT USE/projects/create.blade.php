@extends('layouts/contentNavbarLayout')

@section('title', 'Create Project')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create New Project</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('projects.store') }}" method="POST" id="createProjectForm">
                            @csrf

                            <!-- Basic Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5>Basic Information</h5>
                                    <hr>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="name">Project Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
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
                                            <option value="{{ $manager->id }}" @selected(old('manager_id') == $manager->id)>
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
                                        rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="start_date">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="end_date">End Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="budget">Budget</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('budget') is-invalid @enderror"
                                            id="budget" name="budget" value="{{ old('budget') }}" step="0.01"
                                            min="0" required>
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
                                            <option value="{{ $status }}" @selected(old('status') == $status)>
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
                                            <option value="{{ $priority }}" @selected(old('priority') == $priority)>
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
                                        <!-- Dynamic phases will be added here -->
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
                                        <!-- Dynamic custom fields will be added here -->
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary me-2">Create Project</button>
                                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
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
            let phaseCount = 0;

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

            addPhaseBtn.addEventListener('click', addPhase);

            // Custom Fields Management
            const customFieldsContainer = document.getElementById('customFieldsContainer');
            const addCustomFieldBtn = document.getElementById('addCustomFieldBtn');
            let customFieldCount = 0;

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

            addCustomFieldBtn.addEventListener('click', addCustomField);

            // Form Validation
            const form = document.getElementById('createProjectForm');
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

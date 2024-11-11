@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Compliance Requirement')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Edit Compliance Requirement</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('compliance.requirements.update', $requirement) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $requirement->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ $requirement->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="regulation_type" class="form-label">Regulation Type</label>
                        <select class="form-select" id="regulation_type" name="regulation_type" required>
                            <option value="GDPR" {{ $requirement->regulation_type === 'GDPR' ? 'selected' : '' }}>GDPR
                            </option>
                            <option value="SOX" {{ $requirement->regulation_type === 'SOX' ? 'selected' : '' }}>SOX
                            </option>
                            <option value="HIPAA" {{ $requirement->regulation_type === 'HIPAA' ? 'selected' : '' }}>HIPAA
                            </option>
                            <option value="ISO27001" {{ $requirement->regulation_type === 'ISO27001' ? 'selected' : '' }}>
                                ISO 27001</option>
                            <option value="PCI_DSS" {{ $requirement->regulation_type === 'PCI_DSS' ? 'selected' : '' }}>PCI
                                DSS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="effective_date" class="form-label">Effective Date</label>
                        <input type="date" class="form-control" id="effective_date" name="effective_date"
                            value="{{ $requirement->effective_date->format('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="review_date" class="form-label">Review Date</label>
                        <input type="date" class="form-control" id="review_date" name="review_date"
                            value="{{ $requirement->review_date->format('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="requirements" class="form-label">Requirements</label>
                        <textarea class="form-control" id="requirements" name="requirements" rows="4" required>{{ $requirement->requirements }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="actions_needed" class="form-label">Actions Needed</label>
                        <textarea class="form-control" id="actions_needed" name="actions_needed" rows="3">{{ $requirement->actions_needed }}</textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_mandatory" name="is_mandatory"
                                {{ $requirement->is_mandatory ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_mandatory">
                                Mandatory Requirement
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="risk_level" class="form-label">Risk Level</label>
                        <select class="form-select" id="risk_level" name="risk_level" required>
                            <option value="low" {{ $requirement->risk_level === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $requirement->risk_level === 'medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="high" {{ $requirement->risk_level === 'high' ? 'selected' : '' }}>High
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="department_scope" class="form-label">Department Scope</label>
                        <input type="text" class="form-control" id="department_scope" name="department_scope"
                            value="{{ $requirement->department_scope }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Update Requirement</button>
                    <a href="{{ route('compliance.requirements.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection

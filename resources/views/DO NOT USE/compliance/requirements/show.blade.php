@extends('layouts/contentNavbarLayout')

@section('title', 'View Compliance Requirement')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4">View Compliance Requirement</h4>
            <div>
                <a href="{{ route('compliance.requirements.edit', $requirement) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('compliance.requirements.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Title:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $requirement->title }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Description:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $requirement->description }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Regulation Type:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $requirement->regulation_type }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-9">
                        <span class="badge bg-{{ $requirement->status === 'active' ? 'success' : 'warning' }}">
                            {{ $requirement->status }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Effective Date:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $requirement->effective_date->format('Y-m-d') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Review Date:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $requirement->review_date->format('Y-m-d') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Requirements:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $requirement->requirements }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Actions Needed:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $requirement->actions_needed ?: 'No actions specified' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Mandatory:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $requirement->is_mandatory ? 'Yes' : 'No' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Risk Level:</strong>
                    </div>
                    <div class="col-md-9">
                        <span
                            class="badge bg-{{ $requirement->risk_level === 'high' ? 'danger' : ($requirement->risk_level === 'medium' ? 'warning' : 'info') }}">
                            {{ ucfirst($requirement->risk_level) }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Department Scope:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $requirement->department_scope ?: 'All Departments' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

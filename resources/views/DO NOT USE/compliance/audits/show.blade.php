@extends('layouts/contentNavbarLayout')

@section('title', 'View Compliance Audit')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4">View Compliance Audit</h4>
            <div>
                <a href="{{ route('compliance.audits.edit', $audit) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('compliance.audits.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Audit Type:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ ucfirst($audit->audit_type) }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-9">
                        <span
                            class="badge bg-{{ $audit->status === 'completed' ? 'success' : ($audit->status === 'in_progress' ? 'warning' : 'info') }}">
                            {{ str_replace('_', ' ', ucfirst($audit->status)) }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Scheduled Date:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $audit->scheduled_date->format('Y-m-d') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Completion Date:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $audit->completion_date ? $audit->completion_date->format('Y-m-d') : 'Not completed' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Findings:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $audit->findings ?: 'No findings recorded' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Recommendations:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $audit->recommendations ?: 'No recommendations provided' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Auditor Name:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $audit->auditor_name }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Department:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $audit->department }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Scope:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $audit->scope }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Action Items:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $audit->action_items ?: 'No action items specified' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Follow-up Date:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $audit->follow_up_date ? $audit->follow_up_date->format('Y-m-d') : 'No follow-up scheduled' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

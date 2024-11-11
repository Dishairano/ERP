@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Compliance Audit')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Edit Compliance Audit</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('compliance.audits.update', $audit) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="audit_type" class="form-label">Audit Type</label>
                        <select class="form-select" id="audit_type" name="audit_type" required>
                            <option value="internal" {{ $audit->audit_type === 'internal' ? 'selected' : '' }}>Internal
                                Audit</option>
                            <option value="external" {{ $audit->audit_type === 'external' ? 'selected' : '' }}>External
                                Audit</option>
                            <option value="regulatory" {{ $audit->audit_type === 'regulatory' ? 'selected' : '' }}>
                                Regulatory Audit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="scheduled" {{ $audit->status === 'scheduled' ? 'selected' : '' }}>Scheduled
                            </option>
                            <option value="in_progress" {{ $audit->status === 'in_progress' ? 'selected' : '' }}>In Progress
                            </option>
                            <option value="completed" {{ $audit->status === 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="scheduled_date" class="form-label">Scheduled Date</label>
                        <input type="date" class="form-control" id="scheduled_date" name="scheduled_date"
                            value="{{ $audit->scheduled_date->format('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="completion_date" class="form-label">Completion Date</label>
                        <input type="date" class="form-control" id="completion_date" name="completion_date"
                            value="{{ $audit->completion_date ? $audit->completion_date->format('Y-m-d') : '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="findings" class="form-label">Findings</label>
                        <textarea class="form-control" id="findings" name="findings" rows="3">{{ $audit->findings }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="recommendations" class="form-label">Recommendations</label>
                        <textarea class="form-control" id="recommendations" name="recommendations" rows="3">{{ $audit->recommendations }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="auditor_name" class="form-label">Auditor Name</label>
                        <input type="text" class="form-control" id="auditor_name" name="auditor_name"
                            value="{{ $audit->auditor_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department"
                            value="{{ $audit->department }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="scope" class="form-label">Audit Scope</label>
                        <textarea class="form-control" id="scope" name="scope" rows="3" required>{{ $audit->scope }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="action_items" class="form-label">Action Items</label>
                        <textarea class="form-control" id="action_items" name="action_items" rows="3">{{ $audit->action_items }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="follow_up_date" class="form-label">Follow-up Date</label>
                        <input type="date" class="form-control" id="follow_up_date" name="follow_up_date"
                            value="{{ $audit->follow_up_date ? $audit->follow_up_date->format('Y-m-d') : '' }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Update Audit</button>
                    <a href="{{ route('compliance.audits.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection

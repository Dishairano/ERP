@extends('layouts/contentNavbarLayout')

@section('title', 'Schedule Compliance Audit')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Schedule Compliance Audit</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('compliance.audits.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="audit_type" class="form-label">Audit Type</label>
                        <select class="form-select" id="audit_type" name="audit_type" required>
                            <option value="internal">Internal Audit</option>
                            <option value="external">External Audit</option>
                            <option value="regulatory">Regulatory Audit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="scheduled_date" class="form-label">Scheduled Date</label>
                        <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="auditor_name" class="form-label">Auditor Name</label>
                        <input type="text" class="form-control" id="auditor_name" name="auditor_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>

                    <div class="mb-3">
                        <label for="scope" class="form-label">Audit Scope</label>
                        <textarea class="form-control" id="scope" name="scope" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="action_items" class="form-label">Action Items</label>
                        <textarea class="form-control" id="action_items" name="action_items" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="follow_up_date" class="form-label">Follow-up Date</label>
                        <input type="date" class="form-control" id="follow_up_date" name="follow_up_date">
                    </div>

                    <button type="submit" class="btn btn-primary">Schedule Audit</button>
                    <a href="{{ route('compliance.audits.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection

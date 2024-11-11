@extends('layouts/contentNavbarLayout')

@section('title', 'Create Compliance Requirement')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Create Compliance Requirement</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('compliance.requirements.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="regulation_type" class="form-label">Regulation Type</label>
                        <select class="form-select" id="regulation_type" name="regulation_type" required>
                            <option value="GDPR">GDPR</option>
                            <option value="SOX">SOX</option>
                            <option value="HIPAA">HIPAA</option>
                            <option value="ISO27001">ISO 27001</option>
                            <option value="PCI_DSS">PCI DSS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="effective_date" class="form-label">Effective Date</label>
                        <input type="date" class="form-control" id="effective_date" name="effective_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="review_date" class="form-label">Review Date</label>
                        <input type="date" class="form-control" id="review_date" name="review_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="requirements" class="form-label">Requirements</label>
                        <textarea class="form-control" id="requirements" name="requirements" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="actions_needed" class="form-label">Actions Needed</label>
                        <textarea class="form-control" id="actions_needed" name="actions_needed" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_mandatory" name="is_mandatory" checked>
                            <label class="form-check-label" for="is_mandatory">
                                Mandatory Requirement
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="risk_level" class="form-label">Risk Level</label>
                        <select class="form-select" id="risk_level" name="risk_level" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="department_scope" class="form-label">Department Scope</label>
                        <input type="text" class="form-control" id="department_scope" name="department_scope">
                    </div>

                    <button type="submit" class="btn btn-primary">Create Requirement</button>
                    <a href="{{ route('compliance.requirements.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection

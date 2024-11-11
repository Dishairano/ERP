@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Create Job Posting')

@section('content')
    <h4 class="fw-bold">Create New Job Posting</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('hrm.recruitment.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="position" class="form-label">Position Title</label>
                        <input type="text" name="position" id="position"
                            class="form-control @error('position') is-invalid @enderror" required>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" name="department" id="department"
                            class="form-control @error('department') is-invalid @enderror" required>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Job Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror" required></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="requirements" class="form-label">Requirements</label>
                        <textarea name="requirements" id="requirements" rows="4"
                            class="form-control @error('requirements') is-invalid @enderror" required></textarea>
                        @error('requirements')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="employment_type" class="form-label">Employment Type</label>
                        <select name="employment_type" id="employment_type"
                            class="form-select @error('employment_type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="contract">Contract</option>
                            <option value="temporary">Temporary</option>
                        </select>
                        @error('employment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location"
                            class="form-control @error('location') is-invalid @enderror" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="salary_range" class="form-label">Salary Range</label>
                        <input type="text" name="salary_range" id="salary_range"
                            class="form-control @error('salary_range') is-invalid @enderror"
                            placeholder="e.g., $50,000 - $70,000" required>
                        @error('salary_range')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="deadline" class="form-label">Application Deadline</label>
                        <input type="date" name="deadline" id="deadline"
                            class="form-control @error('deadline') is-invalid @enderror" required>
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror"
                            required>
                            <option value="open">Open</option>
                            <option value="in-progress">In Progress</option>
                            <option value="closed">Closed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Create Job Posting</button>
                    <a href="{{ route('hrm.recruitment') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

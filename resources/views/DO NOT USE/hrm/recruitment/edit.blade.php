@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Edit Job Posting')

@section('content')
    <h4 class="fw-bold">Edit Job Posting</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('hrm.recruitment.update', $recruitment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="position" class="form-label">Position Title</label>
                        <input type="text" name="position" id="position"
                            class="form-control @error('position') is-invalid @enderror"
                            value="{{ $recruitment->position }}" required>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" name="department" id="department"
                            class="form-control @error('department') is-invalid @enderror"
                            value="{{ $recruitment->department }}" required>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Job Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror" required>{{ $recruitment->description }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="requirements" class="form-label">Requirements</label>
                        <textarea name="requirements" id="requirements" rows="4"
                            class="form-control @error('requirements') is-invalid @enderror" required>{{ $recruitment->requirements }}</textarea>
                        @error('requirements')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="employment_type" class="form-label">Employment Type</label>
                        <select name="employment_type" id="employment_type"
                            class="form-select @error('employment_type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="full-time" {{ $recruitment->employment_type === 'full-time' ? 'selected' : '' }}>
                                Full Time</option>
                            <option value="part-time"
                                {{ $recruitment->employment_type === 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ $recruitment->employment_type === 'contract' ? 'selected' : '' }}>
                                Contract</option>
                            <option value="temporary"
                                {{ $recruitment->employment_type === 'temporary' ? 'selected' : '' }}>Temporary</option>
                        </select>
                        @error('employment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location"
                            class="form-control @error('location') is-invalid @enderror"
                            value="{{ $recruitment->location }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="salary_range" class="form-label">Salary Range</label>
                        <input type="text" name="salary_range" id="salary_range"
                            class="form-control @error('salary_range') is-invalid @enderror"
                            value="{{ $recruitment->salary_range }}" required>
                        @error('salary_range')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="deadline" class="form-label">Application Deadline</label>
                        <input type="date" name="deadline" id="deadline"
                            class="form-control @error('deadline') is-invalid @enderror"
                            value="{{ $recruitment->deadline->format('Y-m-d') }}" required>
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror"
                            required>
                            <option value="open" {{ $recruitment->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in-progress" {{ $recruitment->status === 'in-progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="closed" {{ $recruitment->status === 'closed' ? 'selected' : '' }}>Closed
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Job Posting</button>
                    <a href="{{ route('hrm.recruitment') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

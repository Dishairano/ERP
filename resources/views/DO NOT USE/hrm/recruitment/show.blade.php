@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - View Job Posting')

@section('content')
    <h4 class="fw-bold">Job Posting Details</h4>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Position Title</h6>
                    <p class="fs-5">{{ $recruitment->position }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Department</h6>
                    <p class="fs-5">{{ $recruitment->department }}</p>
                </div>

                <div class="col-12 mb-4">
                    <h6 class="text-muted">Job Description</h6>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($recruitment->description)) !!}
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <h6 class="text-muted">Requirements</h6>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($recruitment->requirements)) !!}
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Employment Type</h6>
                    <p class="fs-5">{{ ucfirst($recruitment->employment_type) }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Location</h6>
                    <p class="fs-5">{{ $recruitment->location }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Salary Range</h6>
                    <p class="fs-5">{{ $recruitment->salary_range }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Application Deadline</h6>
                    <p class="fs-5">{{ $recruitment->deadline->format('Y-m-d') }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Status</h6>
                    <p>
                        <span
                            class="badge bg-{{ $recruitment->status === 'open' ? 'success' : ($recruitment->status === 'in-progress' ? 'warning' : 'secondary') }} fs-6">
                            {{ ucfirst($recruitment->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('hrm.recruitment.edit', $recruitment->id) }}" class="btn btn-primary">Edit Job Posting</a>
                <a href="{{ route('hrm.recruitment') }}" class="btn btn-secondary">Back to List</a>
                <form action="{{ route('hrm.recruitment.destroy', $recruitment->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this job posting?')">
                        Delete Job Posting
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

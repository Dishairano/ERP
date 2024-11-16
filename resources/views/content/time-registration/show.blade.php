@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Time Registration Details</h5>
                    <div>
                        <a href="{{ route('time-registration.edit', ['time_registration' => $registration]) }}" class="btn btn-primary me-2">
                            <i class="ri-edit-line me-1"></i> Edit
                        </a>
                        <a href="{{ route('time-registration.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Project</h6>
                            <p>{{ optional($registration->project)->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Task</h6>
                            <p>{{ optional($registration->task)->title }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="text-muted">Date</h6>
                            <p>{{ $registration->date->format('Y-m-d') }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Start Time</h6>
                            <p>{{ \Carbon\Carbon::parse($registration->start_time)->format('H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">End Time</h6>
                            <p>{{ \Carbon\Carbon::parse($registration->end_time)->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="text-muted">Hours</h6>
                            <p>{{ number_format($registration->hours, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Billable</h6>
                            <p>{{ $registration->billable ? 'Yes' : 'No' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Overtime</h6>
                            <p>{{ $registration->overtime ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-muted">Description</h6>
                            <p>{{ $registration->description }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-muted">Status</h6>
                            <span class="badge bg-{{ $registration->status === 'approved' ? 'success' : ($registration->status === 'rejected' ? 'danger' : ($registration->status === 'submitted' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </div>
                        @if($registration->status === 'rejected')
                        <div class="col-md-8">
                            <h6 class="text-muted">Rejection Reason</h6>
                            <p class="text-danger">{{ $registration->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>

                    @if($registration->status === 'draft')
                    <div class="mt-4">
                        <form action="{{ route('time-registration.submit', ['time_registration' => $registration]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-send-plane-line me-1"></i> Submit for Approval
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

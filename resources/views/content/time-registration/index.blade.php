@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registrations')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Time Registrations</h5>
                    <a href="{{ route('time-registration.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> New Registration
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form action="{{ route('time-registration.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control flatpickr-date" name="start_date"
                                    placeholder="Start Date" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control flatpickr-date" name="end_date"
                                    placeholder="End Date" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ route('time-registration.index') }}" class="btn btn-label-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Time Registrations Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Project</th>
                                    <th>Task</th>
                                    <th>Hours</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $registration)
                                    <tr>
                                        <td>{{ $registration->date->format('Y-m-d') }}</td>
                                        <td>{{ optional($registration->project)->name }}</td>
                                        <td>{{ optional($registration->task)->title }}</td>
                                        <td>{{ number_format($registration->hours, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $registration->status === 'approved' ? 'success' : ($registration->status === 'rejected' ? 'danger' : ($registration->status === 'submitted' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('time-registration.show', ['time_registration' => $registration]) }}">
                                                        <i class="ri-eye-line me-2"></i> View
                                                    </a>
                                                    @if($registration->status === 'draft')
                                                    <a class="dropdown-item" href="{{ route('time-registration.edit', ['time_registration' => $registration]) }}">
                                                        <i class="ri-pencil-line me-2"></i> Edit
                                                    </a>
                                                    <form action="{{ route('time-registration.destroy', ['time_registration' => $registration]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this registration?')">
                                                            <i class="ri-delete-bin-line me-2"></i> Delete
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No time registrations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $registrations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date pickers
        flatpickr('.flatpickr-date', {
            dateFormat: 'Y-m-d',
            maxDate: 'today'
        });
    });
</script>
@endsection

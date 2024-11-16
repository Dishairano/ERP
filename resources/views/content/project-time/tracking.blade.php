@extends('layouts/contentNavbarLayout')

@section('title', 'Project Time Tracking')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Project Overview -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $project->name }}</h5>
                    <div>
                        <a href="{{ route('time-registration.create', ['project_id' => $project->id]) }}" class="btn btn-primary me-2">
                            <i class="ri-add-line me-1"></i> Log Time
                        </a>
                        <a href="{{ route('projects.show.page', ['project' => $project->id]) }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Project
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-muted">Total Hours</h6>
                            <h4>{{ number_format($totalHours, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Billable Hours</h6>
                            <h4>{{ number_format($billableHours, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Overtime Hours</h6>
                            <h4>{{ number_format($overtimeHours, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Team Members</h6>
                            <h4>{{ $teamMembersCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Entries -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Time Entries</h5>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form action="{{ route('project-time.tracking', ['project' => $project->id]) }}" method="GET" class="mb-4">
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
                                <select class="form-select" name="user_id">
                                    <option value="">All Team Members</option>
                                    @foreach($teamMembers as $member)
                                        <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ route('project-time.tracking', ['project' => $project->id]) }}" class="btn btn-label-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Time Entries Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Team Member</th>
                                    <th>Task</th>
                                    <th>Hours</th>
                                    <th>Billable</th>
                                    <th>Overtime</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($timeEntries as $entry)
                                    <tr>
                                        <td>{{ $entry->date->format('Y-m-d') }}</td>
                                        <td>{{ optional($entry->user)->name }}</td>
                                        <td>{{ optional($entry->task)->title }}</td>
                                        <td>{{ number_format($entry->hours, 2) }}</td>
                                        <td>
                                            @if($entry->billable)
                                                <i class="ri-checkbox-circle-fill text-success"></i>
                                            @else
                                                <i class="ri-close-circle-fill text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($entry->overtime)
                                                <i class="ri-checkbox-circle-fill text-success"></i>
                                            @else
                                                <i class="ri-close-circle-fill text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $entry->status === 'approved' ? 'success' : ($entry->status === 'rejected' ? 'danger' : ($entry->status === 'submitted' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($entry->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('time-registration.show', ['time_registration' => $entry->id]) }}"
                                                class="btn btn-sm btn-icon btn-label-primary me-1">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @if($entry->status === 'draft' && $entry->user_id === auth()->id())
                                            <a href="{{ route('time-registration.edit', ['time_registration' => $entry->id]) }}"
                                                class="btn btn-sm btn-icon btn-label-warning me-1">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No time entries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $timeEntries->links() }}
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

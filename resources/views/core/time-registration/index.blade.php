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
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Time Registrations</h5>
                <a href="{{ route('time-registration.create') }}" class="btn btn-primary">
                    <i class="ri-add-line me-1"></i> Register Time
                </a>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="input-group">
                            <input type="text" class="form-control flatpickr-range" placeholder="Select date range"
                                value="{{ request('start_date') && request('end_date') ? request('start_date') . ' to ' . request('end_date') : '' }}">
                            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Project</label>
                        <select class="form-select" name="project_id" id="project_filter">
                            <option value="">All Projects</option>
                            @foreach ($projects ?? [] as $project)
                                <option value="{{ $project->id }}"
                                    {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="status_filter">
                            <option value="">All Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="applyFilters()">
                            <i class="ri-filter-3-line me-1"></i> Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Time Registrations Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Task</th>
                                <th>Hours</th>
                                <th>Billable</th>
                                <th>Overtime</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $registration)
                                <tr>
                                    <td>{{ $registration->date->format('M d, Y') }}</td>
                                    <td>{{ $registration->project->name }}</td>
                                    <td>{{ $registration->task->name }}</td>
                                    <td>{{ number_format($registration->hours, 1) }}</td>
                                    <td>
                                        @if ($registration->billable)
                                            <span class="badge bg-label-success">Yes</span>
                                        @else
                                            <span class="badge bg-label-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($registration->overtime)
                                            <span class="badge bg-label-warning">Yes</span>
                                        @else
                                            <span class="badge bg-label-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match ($registration->status) {
                                                'draft' => 'secondary',
                                                'submitted' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                default => 'primary',
                                            };
                                        @endphp
                                        <span class="badge bg-label-{{ $statusClass }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('time-registration.show', $registration) }}">
                                                    <i class="ri-eye-line me-1"></i> View
                                                </a>
                                                @if ($registration->status === 'draft')
                                                    <a class="dropdown-item"
                                                        href="{{ route('time-registration.edit', $registration) }}">
                                                        <i class="ri-pencil-line me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('time-registration.submit', $registration) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ri-send-plane-line me-1"></i> Submit
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($registration->status !== 'approved')
                                                    <form action="{{ route('time-registration.destroy', $registration) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this time registration?')">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No time registrations found</td>
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
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date range picker
            flatpickr('.flatpickr-range', {
                mode: 'range',
                dateFormat: 'Y-m-d',
                defaultDate: [
                    document.getElementById('start_date').value,
                    document.getElementById('end_date').value
                ],
                onChange: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        document.getElementById('start_date').value = formatDate(selectedDates[0]);
                        document.getElementById('end_date').value = formatDate(selectedDates[1]);
                    }
                }
            });
        });

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        function applyFilters() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const projectId = document.getElementById('project_filter').value;
            const status = document.getElementById('status_filter').value;

            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (projectId) params.append('project_id', projectId);
            if (status) params.append('status', status);

            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }
    </script>
@endsection

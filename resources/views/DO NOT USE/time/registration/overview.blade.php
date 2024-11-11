@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Overview')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Hours</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($statistics['total_hours'], 2) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-time-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Billable Hours</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($statistics['billable_hours'], 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Cost</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">${{ number_format($statistics['total_cost'], 2) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-coins-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Billable</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">${{ number_format($statistics['total_billable'], 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="ri-bank-card-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('time-registration.overview') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date"
                            value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Project</label>
                        <select class="form-select" name="project_id">
                            <option value="">All Projects</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('time-registration.overview') }}" class="btn btn-label-secondary">Reset</a>
                        <div class="float-end">
                            <button type="button" class="btn btn-label-success"
                                onclick="window.location.href='{{ route('time-registration.export') }}'">
                                <i class="ri-file-excel-line me-1"></i> Export
                            </button>
                            <button type="button" class="btn btn-primary"
                                onclick="window.location.href='{{ route('time-registration.create') }}'">
                                <i class="ri-add-line me-1"></i> Register Time
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Time Registrations Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Time Registrations</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Task</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Duration</th>
                            <th>Billable</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($timeRegistrations as $registration)
                            <tr>
                                <td>{{ $registration->date->format('M d, Y') }}</td>
                                <td>{{ $registration->project->name }}</td>
                                <td>{{ $registration->task->name }}</td>
                                <td>{{ Carbon\Carbon::parse($registration->start_time)->format('H:i') }}</td>
                                <td>{{ Carbon\Carbon::parse($registration->end_time)->format('H:i') }}</td>
                                <td>{{ $registration->formatted_duration }}</td>
                                <td>
                                    @if ($registration->billable)
                                        <span class="badge bg-label-success">Yes</span>
                                    @else
                                        <span class="badge bg-label-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-label-{{ $registration->status_color }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('time-registration.show', $registration) }}">
                                                    <i class="ri-eye-line me-1"></i> View Details
                                                </a>
                                            </li>
                                            @if ($registration->isEditable())
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('time-registration.edit', $registration) }}">
                                                        <i class="ri-edit-line me-1"></i> Edit
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($registration->isDeletable())
                                                <li>
                                                    <form action="{{ route('time-registration.destroy', $registration) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this time registration?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $timeRegistrations->links() }}
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        // Add any custom JavaScript for the overview page here
    </script>
@endsection

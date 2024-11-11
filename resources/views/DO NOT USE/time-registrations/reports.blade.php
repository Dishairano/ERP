@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Reports')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Time Registration Reports</h5>
                    </div>

                    <div class="card-body">
                        <!-- Filters -->
                        <form action="{{ route('time-registrations.reports') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Project</label>
                                    <select name="project" class="form-select">
                                        <option value="">All Projects</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @selected(request('project') == $project->id)>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block w-100">
                                        <i class="ri-filter-2-line"></i> Generate Report
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Summary Cards -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">Total Hours</h6>
                                        <h2 class="mb-0">{{ number_format($summary['total_hours'], 2) }}</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">Billable Hours</h6>
                                        <h2 class="mb-0">{{ number_format($summary['billable_hours'], 2) }}</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">Total Amount</h6>
                                        <h2 class="mb-0">${{ number_format($summary['total_amount'], 2) }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Time Registrations Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Project</th>
                                        <th>Category</th>
                                        <th>Duration</th>
                                        <th>Billable Hours</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($timeRegistrations as $registration)
                                        <tr>
                                            <td>{{ $registration->start_time->format('Y-m-d') }}</td>
                                            <td>{{ $registration->project?->name ?? 'No Project' }}</td>
                                            <td>
                                                <span class="badge"
                                                    style="background-color: {{ $registration->category->color }}">
                                                    {{ $registration->category->name }}
                                                </span>
                                            </td>
                                            <td>{{ $registration->total_hours }} hours</td>
                                            <td>{{ $registration->is_billable ? $registration->total_hours : '0' }} hours
                                            </td>
                                            <td>${{ number_format($registration->billable_amount, 2) }}</td>
                                            <td>
                                                @if ($registration->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($registration->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No time registrations found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $timeRegistrations->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

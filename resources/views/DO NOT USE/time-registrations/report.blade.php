@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Time Registration Report</h4>

        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('time-registrations.report') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Project</label>
                            <select name="project_id" class="form-select">
                                <option value="">All Projects</option>
                                @foreach (\App\Models\Project::all() as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('time-registrations.report') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Hours</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($timeRegistrations as $registration)
                                <tr>
                                    <td>{{ $registration->date }}</td>
                                    <td>{{ $registration->project->name }}</td>
                                    <td>{{ $registration->hours }}</td>
                                    <td>{{ $registration->description }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No time registrations found</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total Hours:</strong></td>
                                <td><strong>{{ $timeRegistrations->sum('hours') }}</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

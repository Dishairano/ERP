@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - View Training Program')

@section('content')
    <h4 class="fw-bold">Training Program Details</h4>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Program Title</h6>
                    <p class="fs-5">{{ $training->title }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Trainer</h6>
                    <p class="fs-5">{{ $training->trainer }}</p>
                </div>

                <div class="col-12 mb-4">
                    <h6 class="text-muted">Description</h6>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($training->description)) !!}
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Start Date</h6>
                    <p class="fs-5">{{ $training->start_date->format('Y-m-d') }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">End Date</h6>
                    <p class="fs-5">{{ $training->end_date->format('Y-m-d') }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Duration</h6>
                    <p class="fs-5">{{ $training->start_date->diffInDays($training->end_date) + 1 }} days</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Location</h6>
                    <p class="fs-5">{{ $training->location }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Participants</h6>
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1 me-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ ($training->participants_count / $training->max_participants) * 100 }}%">
                            </div>
                        </div>
                        <span class="fs-5">{{ $training->participants_count }}/{{ $training->max_participants }}</span>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">Status</h6>
                    @php
                        $status = 'secondary';
                        if ($training->start_date->isFuture()) {
                            $status = 'info';
                        } elseif ($training->end_date->isFuture() && $training->start_date->isPast()) {
                            $status = 'success';
                        }
                    @endphp
                    <span class="badge bg-{{ $status }} fs-6">
                        {{ $training->start_date->isFuture() ? 'Upcoming' : ($training->end_date->isFuture() ? 'In Progress' : 'Completed') }}
                    </span>
                </div>

                <div class="col-12 mb-4">
                    <h6 class="text-muted">Prerequisites</h6>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($training->prerequisites)) !!}
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <h6 class="text-muted">Training Materials</h6>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($training->materials)) !!}
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('hrm.training-development.edit', $training->id) }}" class="btn btn-primary">Edit
                    Program</a>
                <a href="{{ route('hrm.training-development') }}" class="btn btn-secondary">Back to List</a>
                <form action="{{ route('hrm.training-development.destroy', $training->id) }}" method="POST"
                    class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this training program?')">
                        Delete Program
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if ($training->participants_count > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Enrolled Participants</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Enrollment Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($training->participants as $participant)
                            <tr>
                                <td>{{ $participant->name }}</td>
                                <td>{{ $participant->department }}</td>
                                <td>{{ $participant->pivot->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $participant->pivot->status === 'completed' ? 'success' : 'info' }}">
                                        {{ ucfirst($participant->pivot->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

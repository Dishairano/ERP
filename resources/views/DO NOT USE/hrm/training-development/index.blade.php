@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Training & Development')

@section('content')
    <h4 class="fw-bold">Training & Development Programs</h4>
    <a href="{{ route('hrm.training-development.create') }}" class="btn btn-primary mb-4">Create New Program</a>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Trainer</th>
                        <th>Location</th>
                        <th>Duration</th>
                        <th>Participants</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainings as $training)
                        <tr>
                            <td>{{ $training->title }}</td>
                            <td>{{ $training->trainer }}</td>
                            <td>{{ $training->location }}</td>
                            <td>
                                {{ $training->start_date->format('Y-m-d') }} to {{ $training->end_date->format('Y-m-d') }}
                                <br>
                                <small class="text-muted">
                                    {{ $training->start_date->diffInDays($training->end_date) + 1 }} days
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ ($training->participants_count / $training->max_participants) * 100 }}%">
                                        </div>
                                    </div>
                                    <span>{{ $training->participants_count }}/{{ $training->max_participants }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $status = 'secondary';
                                    if ($training->start_date->isFuture()) {
                                        $status = 'info';
                                    } elseif ($training->end_date->isFuture() && $training->start_date->isPast()) {
                                        $status = 'success';
                                    }
                                @endphp
                                <span class="badge bg-{{ $status }}">
                                    {{ $training->start_date->isFuture() ? 'Upcoming' : ($training->end_date->isFuture() ? 'In Progress' : 'Completed') }}
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
                                            href="{{ route('hrm.training-development.show', $training->id) }}">
                                            <i class="ri-eye-line me-2"></i> View
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('hrm.training-development.edit', $training->id) }}">
                                            <i class="ri-pencil-line me-2"></i> Edit
                                        </a>
                                        <form action="{{ route('hrm.training-development.destroy', $training->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this training program?')">
                                                <i class="ri-delete-bin-line me-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

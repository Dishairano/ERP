@extends('layouts/contentNavbarLayout')

@section('title', 'Compliance Trainings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4">Compliance Trainings</h4>
            <a href="{{ route('compliance.trainings.create') }}" class="btn btn-primary">Add New Training</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Training Type</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Department</th>
                                <th>Duration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainings as $training)
                                <tr>
                                    <td>{{ $training->title }}</td>
                                    <td>{{ $training->training_type }}</td>
                                    <td>
                                        <span class="badge bg-{{ $training->status === 'active' ? 'success' : 'warning' }}">
                                            {{ $training->status }}
                                        </span>
                                    </td>
                                    <td>{{ $training->due_date->format('Y-m-d') }}</td>
                                    <td>{{ $training->department }}</td>
                                    <td>{{ $training->duration_minutes }} minutes</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('compliance.trainings.show', $training) }}">
                                                    <i class="bx bx-show-alt me-1"></i> View
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('compliance.trainings.edit', $training) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('compliance.trainings.complete', $training) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bx bx-check-circle me-1"></i> Mark as Completed
                                                    </button>
                                                </form>
                                                <form action="{{ route('compliance.trainings.destroy', $training) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this training?')">
                                                        <i class="bx bx-trash me-1"></i> Delete
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
                <div class="mt-3">
                    {{ $trainings->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

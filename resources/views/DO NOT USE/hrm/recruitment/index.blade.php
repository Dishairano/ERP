@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Recruitment')

@section('content')
    <h4 class="fw-bold">Recruitment Management</h4>
    <a href="{{ route('hrm.recruitment.create') }}" class="btn btn-primary mb-4">Create New Job Posting</a>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recruitments as $recruitment)
                        <tr>
                            <td>{{ $recruitment->position }}</td>
                            <td>{{ $recruitment->department }}</td>
                            <td>{{ $recruitment->location }}</td>
                            <td>{{ ucfirst($recruitment->employment_type) }}</td>
                            <td>{{ $recruitment->deadline->format('Y-m-d') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $recruitment->status === 'open' ? 'success' : ($recruitment->status === 'in-progress' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($recruitment->status) }}
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
                                            href="{{ route('hrm.recruitment.show', $recruitment->id) }}">
                                            <i class="ri-eye-line me-2"></i> View
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('hrm.recruitment.edit', $recruitment->id) }}">
                                            <i class="ri-pencil-line me-2"></i> Edit
                                        </a>
                                        <form action="{{ route('hrm.recruitment.destroy', $recruitment->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this job posting?')">
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

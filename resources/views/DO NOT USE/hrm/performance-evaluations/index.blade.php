@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Performance Evaluations')

@section('content')
    <h4 class="fw-bold">Performance Evaluations</h4>
    <a href="{{ route('hrm.performance-evaluations.create') }}" class="btn btn-primary mb-4">Create New Evaluation</a>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Evaluator</th>
                        <th>Evaluation Date</th>
                        <th>Performance Score</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluations as $evaluation)
                        <tr>
                            <td>
                                {{ $evaluation->employee->name }}
                            </td>
                            <td>
                                {{ $evaluation->evaluator->name }}
                            </td>
                            <td>{{ $evaluation->evaluation_date->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress w-100 me-3" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ ($evaluation->performance_score / 5) * 100 }}%"
                                            aria-valuenow="{{ $evaluation->performance_score }}" aria-valuemin="0"
                                            aria-valuemax="5">
                                        </div>
                                    </div>
                                    <span>{{ $evaluation->performance_score }}/5</span>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('hrm.performance-evaluations.show', $evaluation->id) }}">
                                            <i class="ri-eye-line me-2"></i> View
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('hrm.performance-evaluations.edit', $evaluation->id) }}">
                                            <i class="ri-pencil-line me-2"></i> Edit
                                        </a>
                                        <form action="{{ route('hrm.performance-evaluations.destroy', $evaluation->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this evaluation?')">
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

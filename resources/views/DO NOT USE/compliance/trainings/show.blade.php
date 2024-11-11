@extends('layouts/contentNavbarLayout')

@section('title', 'View Compliance Training')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4">View Compliance Training</h4>
            <div>
                <form action="{{ route('compliance.trainings.complete', $training) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Mark as Completed</button>
                </form>
                <a href="{{ route('compliance.trainings.edit', $training) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('compliance.trainings.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Title:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $training->title }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Description:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $training->description }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Training Type:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ ucfirst($training->training_type) }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-9">
                        <span class="badge bg-{{ $training->status === 'active' ? 'success' : 'warning' }}">
                            {{ $training->status }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Due Date:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $training->due_date->format('Y-m-d') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Content:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $training->content }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Department:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $training->department }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Mandatory:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $training->is_mandatory ? 'Yes' : 'No' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Duration:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $training->duration_minutes }} minutes
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Created At:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $training->created_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Last Updated:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $training->updated_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Compliance Training')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Edit Compliance Training</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('compliance.trainings.update', $training) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $training->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ $training->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="training_type" class="form-label">Training Type</label>
                        <select class="form-select" id="training_type" name="training_type" required>
                            <option value="compliance" {{ $training->training_type === 'compliance' ? 'selected' : '' }}>
                                Compliance</option>
                            <option value="security" {{ $training->training_type === 'security' ? 'selected' : '' }}>
                                Security</option>
                            <option value="privacy" {{ $training->training_type === 'privacy' ? 'selected' : '' }}>Privacy
                            </option>
                            <option value="regulatory" {{ $training->training_type === 'regulatory' ? 'selected' : '' }}>
                                Regulatory</option>
                            <option value="ethics" {{ $training->training_type === 'ethics' ? 'selected' : '' }}>Ethics
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date"
                            value="{{ $training->due_date->format('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Training Content</label>
                        <textarea class="form-control" id="content" name="content" rows="5" required>{{ $training->content }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department"
                            value="{{ $training->department }}" required>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_mandatory" name="is_mandatory"
                                {{ $training->is_mandatory ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_mandatory">
                                Mandatory Training
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                        <input type="number" class="form-control" id="duration_minutes" name="duration_minutes"
                            value="{{ $training->duration_minutes }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Training</button>
                    <a href="{{ route('compliance.trainings.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection

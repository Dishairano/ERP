@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Edit Training Program')

@section('content')
    <h4 class="fw-bold">Edit Training Program</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('hrm.training-development.update', $training->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Program Title</label>
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror" value="{{ $training->title }}"
                            required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="trainer" class="form-label">Trainer Name</label>
                        <input type="text" name="trainer" id="trainer"
                            class="form-control @error('trainer') is-invalid @enderror" value="{{ $training->trainer }}"
                            required>
                        @error('trainer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Program Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror" required>{{ $training->description }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ $training->start_date->format('Y-m-d') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                            class="form-control @error('end_date') is-invalid @enderror"
                            value="{{ $training->end_date->format('Y-m-d') }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location"
                            class="form-control @error('location') is-invalid @enderror" value="{{ $training->location }}"
                            required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="max_participants" class="form-label">Maximum Participants</label>
                        <input type="number" name="max_participants" id="max_participants"
                            class="form-control @error('max_participants') is-invalid @enderror" min="1"
                            value="{{ $training->max_participants }}" required>
                        @error('max_participants')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="prerequisites" class="form-label">Prerequisites</label>
                        <textarea name="prerequisites" id="prerequisites" rows="3"
                            class="form-control @error('prerequisites') is-invalid @enderror">{{ $training->prerequisites }}</textarea>
                        @error('prerequisites')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="materials" class="form-label">Training Materials</label>
                        <textarea name="materials" id="materials" rows="3" class="form-control @error('materials') is-invalid @enderror">{{ $training->materials }}</textarea>
                        @error('materials')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Program</button>
                    <a href="{{ route('hrm.training-development') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        // Validate end date is after start date
        document.getElementById('end_date').addEventListener('change', function() {
            const startDate = document.getElementById('start_date').value;
            const endDate = this.value;

            if (startDate && endDate && endDate < startDate) {
                alert('End date must be after start date');
                this.value = '';
            }
        });
    </script>
@endsection

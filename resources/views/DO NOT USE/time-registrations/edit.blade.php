@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Time Registration')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Time Registration</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('time-registrations.update', $timeRegistration) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Project</label>
                                    <select name="project_id" class="form-select @error('project_id') is-invalid @enderror">
                                        <option value="">No Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @selected(old('project_id', $timeRegistration->project_id) == $project->id)>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Task</label>
                                    <select name="project_task_id"
                                        class="form-select @error('project_task_id') is-invalid @enderror">
                                        <option value="">No Task</option>
                                        @foreach ($tasks as $task)
                                            <option value="{{ $task->id }}" @selected(old('project_task_id', $timeRegistration->project_task_id) == $task->id)>
                                                {{ $task->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_task_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="time_category_id"
                                        class="form-select @error('time_category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('time_category_id', $timeRegistration->time_category_id) == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('time_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Break Duration (minutes)</label>
                                    <input type="number" name="break_duration_minutes"
                                        class="form-control @error('break_duration_minutes') is-invalid @enderror"
                                        value="{{ old('break_duration_minutes', $timeRegistration->break_duration_minutes) }}"
                                        min="0">
                                    @error('break_duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Start Time</label>
                                    <input type="datetime-local" name="start_time"
                                        class="form-control @error('start_time') is-invalid @enderror"
                                        value="{{ old('start_time', $timeRegistration->start_time->format('Y-m-d\TH:i')) }}"
                                        required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">End Time</label>
                                    <input type="datetime-local" name="end_time"
                                        class="form-control @error('end_time') is-invalid @enderror"
                                        value="{{ old('end_time', $timeRegistration->end_time->format('Y-m-d\TH:i')) }}"
                                        required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mt-3">
                                        <input type="checkbox" name="is_billable"
                                            class="form-check-input @error('is_billable') is-invalid @enderror"
                                            value="1" @checked(old('is_billable', $timeRegistration->is_billable))>
                                        <label class="form-check-label">Billable</label>
                                    </div>
                                    @error('is_billable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hourly Rate</label>
                                    <input type="number" name="hourly_rate" step="0.01"
                                        class="form-control @error('hourly_rate') is-invalid @enderror"
                                        value="{{ old('hourly_rate', $timeRegistration->hourly_rate) }}">
                                    @error('hourly_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $timeRegistration->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if ($timeRegistration->attachments->count() > 0)
                                    <div class="col-12">
                                        <label class="form-label">Current Attachments</label>
                                        <div class="list-group">
                                            @foreach ($timeRegistration->attachments as $attachment)
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>{{ $attachment->file_name }}</span>
                                                    <form
                                                        action="{{ route('time-registrations.attachments.destroy', [$timeRegistration, $attachment]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <label class="form-label">Add New Attachments</label>
                                    <input type="file" name="attachments[]"
                                        class="form-control @error('attachments.*') is-invalid @enderror" multiple>
                                    @error('attachments.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line"></i> Update Time Registration
                                    </button>
                                    <a href="{{ route('time-registrations.index') }}" class="btn btn-secondary">
                                        <i class="ri-arrow-left-line"></i> Back to List
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-calculate duration when start or end time changes
            const startTimeInput = document.querySelector('input[name="start_time"]');
            const endTimeInput = document.querySelector('input[name="end_time"]');
            const breakDurationInput = document.querySelector('input[name="break_duration_minutes"]');

            function updateDuration() {
                const startTime = new Date(startTimeInput.value);
                const endTime = new Date(endTimeInput.value);

                if (startTime && endTime && endTime > startTime) {
                    const durationMinutes = Math.round((endTime - startTime) / 1000 / 60);
                    const breakDuration = parseInt(breakDurationInput.value) || 0;
                    const actualDuration = durationMinutes - breakDuration;

                    if (actualDuration < 0) {
                        alert('Break duration cannot be longer than the total time period.');
                        breakDurationInput.value = 0;
                    }
                }
            }

            startTimeInput.addEventListener('change', updateDuration);
            endTimeInput.addEventListener('change', updateDuration);
            breakDurationInput.addEventListener('change', updateDuration);
        });
    </script>
@endpush

@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Resource Availability Calendar</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAssignment">
                            <i class="fas fa-plus"></i> New Assignment
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Resource</th>
                                        @for ($i = 0; $i < 7; $i++)
                                            <th>{{ now()->addDays($i)->format('D, M d') }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resources as $resource)
                                        <tr>
                                            <td>
                                                <strong>{{ $resource->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ ucfirst($resource->type) }}</small>
                                            </td>
                                            @for ($i = 0; $i < 7; $i++)
                                                @php
                                                    $date = now()->addDays($i);
                                                    $assignments = $resource->assignments->filter(function (
                                                        $assignment,
                                                    ) use ($date) {
                                                        return $assignment->start_time->format('Y-m-d') <=
                                                            $date->format('Y-m-d') &&
                                                            $assignment->end_time->format('Y-m-d') >=
                                                                $date->format('Y-m-d');
                                                    });
                                                @endphp
                                                <td
                                                    class="{{ $assignments->isEmpty() ? 'bg-light' : 'bg-primary bg-opacity-10' }}">
                                                    @foreach ($assignments as $assignment)
                                                        <div class="mb-1">
                                                            <span
                                                                class="badge badge-{{ $assignment->status === 'completed' ? 'success' : ($assignment->status === 'active' ? 'primary' : 'warning') }}">
                                                                {{ $assignment->project->name ?? 'N/A' }}
                                                            </span>
                                                            <br>
                                                            <small>{{ $assignment->start_time->format('H:i') }} -
                                                                {{ $assignment->end_time->format('H:i') }}</small>
                                                        </div>
                                                    @endforeach
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Assignment Modal -->
    <div class="modal fade" id="newAssignment" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Resource Assignment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="newAssignmentForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="resource">Resource</label>
                            <select class="form-select" id="resource" name="resource_id" required>
                                <option value="">Select Resource</option>
                                @foreach ($resources as $resource)
                                    <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="project">Project</label>
                            <select class="form-select" id="project" name="project_id" required>
                                <option value="">Select Project</option>
                                <!-- Projects will be populated dynamically -->
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="start_time">Start Time</label>
                                    <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="end_time">End Time</label>
                                    <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('newAssignmentForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                fetch(`/resources/${formData.get('resource_id')}/assign`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'Error creating assignment');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error creating assignment');
                    });
            });
        });
    </script>
@endpush

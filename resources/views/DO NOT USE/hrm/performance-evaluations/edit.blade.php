@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Edit Performance Evaluation')

@section('content')
    <h4 class="fw-bold">Edit Performance Evaluation</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('hrm.performance-evaluations.update', $evaluation->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" id="employee_id"
                            class="form-select @error('employee_id') is-invalid @enderror" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ $evaluation->employee_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="evaluator_id" class="form-label">Evaluator</label>
                        <select name="evaluator_id" id="evaluator_id"
                            class="form-select @error('evaluator_id') is-invalid @enderror" required>
                            <option value="">Select Evaluator</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ $evaluation->evaluator_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('evaluator_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="evaluation_date" class="form-label">Evaluation Date</label>
                        <input type="date" name="evaluation_date" id="evaluation_date"
                            class="form-control @error('evaluation_date') is-invalid @enderror"
                            value="{{ $evaluation->evaluation_date->format('Y-m-d') }}" required>
                        @error('evaluation_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="performance_score" class="form-label">Performance Score (1-5)</label>
                        <div class="input-group">
                            <input type="number" name="performance_score" id="performance_score"
                                class="form-control @error('performance_score') is-invalid @enderror" min="1"
                                max="5" step="0.1" value="{{ $evaluation->performance_score }}" required>
                            <span class="input-group-text">/5</span>
                        </div>
                        @error('performance_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="comments" class="form-label">Comments</label>
                        <textarea name="comments" id="comments" rows="4" class="form-control @error('comments') is-invalid @enderror"
                            required>{{ $evaluation->comments }}</textarea>
                        @error('comments')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <h5 class="mb-3">Performance Criteria</h5>

                        <div class="mb-3">
                            <label class="form-label">Job Knowledge</label>
                            <div class="d-flex align-items-center">
                                <input type="range" class="form-range" min="1" max="5" step="1"
                                    id="job_knowledge" name="job_knowledge" value="{{ $evaluation->job_knowledge }}">
                                <span class="ms-2" id="job_knowledge_value">{{ $evaluation->job_knowledge }}</span>/5
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Work Quality</label>
                            <div class="d-flex align-items-center">
                                <input type="range" class="form-range" min="1" max="5" step="1"
                                    id="work_quality" name="work_quality" value="{{ $evaluation->work_quality }}">
                                <span class="ms-2" id="work_quality_value">{{ $evaluation->work_quality }}</span>/5
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Initiative</label>
                            <div class="d-flex align-items-center">
                                <input type="range" class="form-range" min="1" max="5" step="1"
                                    id="initiative" name="initiative" value="{{ $evaluation->initiative }}">
                                <span class="ms-2" id="initiative_value">{{ $evaluation->initiative }}</span>/5
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Communication Skills</label>
                            <div class="d-flex align-items-center">
                                <input type="range" class="form-range" min="1" max="5" step="1"
                                    id="communication" name="communication" value="{{ $evaluation->communication }}">
                                <span class="ms-2" id="communication_value">{{ $evaluation->communication }}</span>/5
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teamwork</label>
                            <div class="d-flex align-items-center">
                                <input type="range" class="form-range" min="1" max="5" step="1"
                                    id="teamwork" name="teamwork" value="{{ $evaluation->teamwork }}">
                                <span class="ms-2" id="teamwork_value">{{ $evaluation->teamwork }}</span>/5
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Evaluation</button>
                    <a href="{{ route('hrm.performance-evaluations') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        // Update range input values
        document.querySelectorAll('.form-range').forEach(range => {
            range.addEventListener('input', (e) => {
                document.getElementById(e.target.id + '_value').textContent = e.target.value;
            });
        });
    </script>
@endsection

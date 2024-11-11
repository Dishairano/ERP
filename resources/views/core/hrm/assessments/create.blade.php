@extends('layouts/contentNavbarLayout')

@section('title', 'Create Assessment')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Create Assessment</h4>
            <a href="{{ route('assessments.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line"></i> Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('assessments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h5>Basic Information</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="job_posting_id">Position <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('job_posting_id') is-invalid @enderror" id="job_posting_id"
                                name="job_posting_id" required>
                                <option value="">Select Position</option>
                                @foreach ($jobPostings as $posting)
                                    <option value="{{ $posting->id }}" @selected(old('job_posting_id', request('job_posting_id')) == $posting->id)>
                                        {{ $posting->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('job_posting_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="candidate_id">Candidate <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('candidate_id') is-invalid @enderror" id="candidate_id"
                                name="candidate_id" required>
                                <option value="">Select Candidate</option>
                                @foreach ($candidates as $candidate)
                                    <option value="{{ $candidate->id }}" @selected(old('candidate_id', request('candidate_id')) == $candidate->id)>
                                        {{ $candidate->first_name }} {{ $candidate->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('candidate_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Assessment Details -->
                        <div class="col-12 mt-4">
                            <h5>Assessment Details</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="assessment_type">Assessment Type <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('assessment_type') is-invalid @enderror" id="assessment_type"
                                name="assessment_type" required>
                                <option value="">Select Type</option>
                                @foreach ($assessmentTypes as $type)
                                    <option value="{{ $type }}" @selected(old('assessment_type') == $type)>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assessment_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="assessor_id">Assessor <span class="text-danger">*</span></label>
                            <select class="form-select @error('assessor_id') is-invalid @enderror" id="assessor_id"
                                name="assessor_id" required>
                                <option value="">Select Assessor</option>
                                @foreach ($assessors as $assessor)
                                    <option value="{{ $assessor->id }}" @selected(old('assessor_id') == $assessor->id)>
                                        {{ $assessor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assessor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="title">Assessment Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule -->
                        <div class="col-md-4">
                            <label class="form-label" for="scheduled_date">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('scheduled_date') is-invalid @enderror"
                                id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" required>
                            @error('scheduled_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="scheduled_time">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('scheduled_time') is-invalid @enderror"
                                id="scheduled_time" name="scheduled_time" value="{{ old('scheduled_time') }}" required>
                            @error('scheduled_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="duration_minutes">Duration (minutes) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}"
                                min="15" step="15" required>
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Platform Details -->
                        <div class="col-md-12">
                            <label class="form-label" for="platform">Assessment Platform</label>
                            <input type="text" class="form-control @error('platform') is-invalid @enderror"
                                id="platform" name="platform" value="{{ old('platform') }}">
                            @error('platform')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="access_link">Access Link</label>
                            <input type="url" class="form-control @error('access_link') is-invalid @enderror"
                                id="access_link" name="access_link" value="{{ old('access_link') }}">
                            @error('access_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="access_code">Access Code</label>
                            <input type="text" class="form-control @error('access_code') is-invalid @enderror"
                                id="access_code" name="access_code" value="{{ old('access_code') }}">
                            @error('access_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="expiry_date">Expiry Date</label>
                            <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Assessment Content -->
                        <div class="col-12 mt-4">
                            <h5>Assessment Content</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="instructions">Instructions</label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" id="instructions" name="instructions"
                                rows="3">{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="questions">Questions</label>
                            <textarea class="form-control @error('questions') is-invalid @enderror" id="questions" name="questions"
                                rows="5" placeholder="Enter questions separated by new lines">{{ old('questions') }}</textarea>
                            @error('questions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Scoring -->
                        <div class="col-md-6">
                            <label class="form-label" for="max_score">Maximum Score <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_score') is-invalid @enderror"
                                id="max_score" name="max_score" value="{{ old('max_score', 100) }}" min="1"
                                step="1" required>
                            @error('max_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="passing_score">Passing Score <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('passing_score') is-invalid @enderror"
                                id="passing_score" name="passing_score" value="{{ old('passing_score', 60) }}"
                                min="1" step="1" required>
                            @error('passing_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="skills_evaluated">Skills to Evaluate</label>
                            <input type="text" class="form-control @error('skills_evaluated') is-invalid @enderror"
                                id="skills_evaluated" name="skills_evaluated" value="{{ old('skills_evaluated') }}"
                                placeholder="Enter skills separated by commas">
                            @error('skills_evaluated')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Attachments -->
                        <div class="col-12">
                            <label class="form-label" for="attachments">Attachments</label>
                            <input type="file" class="form-control @error('attachments') is-invalid @enderror"
                                id="attachments" name="attachments[]" multiple>
                            @error('attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-save-line"></i> Create Assessment
                            </button>
                            <a href="{{ route('assessments.index') }}" class="btn btn-secondary">
                                <i class="ri-close-line"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Convert arrays before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const questionsInput = document.getElementById('questions');
            const skillsInput = document.getElementById('skills_evaluated');

            if (questionsInput.value) {
                const questionsArray = questionsInput.value.split('\n').filter(q => q.trim());
                questionsInput.value = JSON.stringify(questionsArray);
            }

            if (skillsInput.value) {
                const skillsArray = skillsInput.value.split(',').map(skill => skill.trim());
                skillsInput.value = JSON.stringify(skillsArray);
            }
        });

        // Validate scores
        document.getElementById('passing_score').addEventListener('input', function() {
            const maxScore = parseInt(document.getElementById('max_score').value) || 0;
            const passingScore = parseInt(this.value) || 0;

            if (passingScore > maxScore) {
                this.setCustomValidity('Passing score cannot be greater than maximum score');
            } else {
                this.setCustomValidity('');
            }
        });

        // Update candidates dropdown based on selected job posting
        document.getElementById('job_posting_id').addEventListener('change', function() {
            const jobPostingId = this.value;
            const candidateSelect = document.getElementById('candidate_id');

            // Clear current options
            candidateSelect.innerHTML = '<option value="">Select Candidate</option>';

            if (jobPostingId) {
                fetch(`/api/job-postings/${jobPostingId}/candidates`)
                    .then(response => response.json())
                    .then(candidates => {
                        candidates.forEach(candidate => {
                            const option = new Option(
                                `${candidate.first_name} ${candidate.last_name}`,
                                candidate.id,
                                false,
                                candidate.id ==
                                {{ old('candidate_id', request('candidate_id', 0)) }}
                            );
                            candidateSelect.add(option);
                        });
                    });
            }
        });

        // Trigger change event on page load if job posting is selected
        document.addEventListener('DOMContentLoaded', function() {
            const jobPostingSelect = document.getElementById('job_posting_id');
            if (jobPostingSelect.value) {
                jobPostingSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection

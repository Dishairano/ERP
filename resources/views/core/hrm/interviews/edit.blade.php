@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Interview')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Edit Interview</h4>
            <div>
                <a href="{{ route('interviews.show', $interview) }}" class="btn btn-primary me-2">
                    <i class="ri-eye-line"></i> View Interview
                </a>
                <a href="{{ route('interviews.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('interviews.update', $interview) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <!-- Candidate Information -->
                        <div class="col-12">
                            <h5>Candidate Information</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="job_posting_id">Position <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('job_posting_id') is-invalid @enderror" id="job_posting_id"
                                name="job_posting_id" required>
                                <option value="">Select Position</option>
                                @foreach ($jobPostings as $posting)
                                    <option value="{{ $posting->id }}" @selected(old('job_posting_id', $interview->job_posting_id) == $posting->id)>
                                        {{ $posting->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('job_posting_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="candidate_id">Candidate <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('candidate_id') is-invalid @enderror" id="candidate_id"
                                name="candidate_id" required>
                                <option value="">Select Candidate</option>
                                @foreach ($candidates as $candidate)
                                    <option value="{{ $candidate->id }}" @selected(old('candidate_id', $interview->candidate_id) == $candidate->id)>
                                        {{ $candidate->first_name }} {{ $candidate->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('candidate_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Interview Details -->
                        <div class="col-12 mt-4">
                            <h5>Interview Details</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="interview_type">Interview Type <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('interview_type') is-invalid @enderror" id="interview_type"
                                name="interview_type" required>
                                <option value="">Select Type</option>
                                @foreach ($interviewTypes as $type)
                                    <option value="{{ $type }}" @selected(old('interview_type', $interview->interview_type) == $type)>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('interview_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="round_number">Round Number <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('round_number') is-invalid @enderror"
                                id="round_number" name="round_number"
                                value="{{ old('round_number', $interview->round_number) }}" min="1" required>
                            @error('round_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="interviewer_id">Interviewer <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('interviewer_id') is-invalid @enderror" id="interviewer_id"
                                name="interviewer_id" required>
                                <option value="">Select Interviewer</option>
                                @foreach ($interviewers as $interviewer)
                                    <option value="{{ $interviewer->id }}" @selected(old('interviewer_id', $interview->interviewer_id) == $interviewer->id)>
                                        {{ $interviewer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('interviewer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule -->
                        <div class="col-md-4">
                            <label class="form-label" for="scheduled_date">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('scheduled_date') is-invalid @enderror"
                                id="scheduled_date" name="scheduled_date"
                                value="{{ old('scheduled_date', $interview->scheduled_date->format('Y-m-d')) }}" required>
                            @error('scheduled_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="scheduled_time">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('scheduled_time') is-invalid @enderror"
                                id="scheduled_time" name="scheduled_time"
                                value="{{ old('scheduled_time', $interview->scheduled_time->format('H:i')) }}" required>
                            @error('scheduled_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="duration_minutes">Duration (minutes) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                id="duration_minutes" name="duration_minutes"
                                value="{{ old('duration_minutes', $interview->duration_minutes) }}" min="15"
                                step="15" required>
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location/Meeting Details -->
                        <div class="col-md-6">
                            <label class="form-label" for="location">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                id="location" name="location" value="{{ old('location', $interview->location) }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="meeting_link">Meeting Link</label>
                            <input type="url" class="form-control @error('meeting_link') is-invalid @enderror"
                                id="meeting_link" name="meeting_link"
                                value="{{ old('meeting_link', $interview->meeting_link) }}">
                            @error('meeting_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="meeting_id">Meeting ID</label>
                            <input type="text" class="form-control @error('meeting_id') is-invalid @enderror"
                                id="meeting_id" name="meeting_id"
                                value="{{ old('meeting_id', $interview->meeting_id) }}">
                            @error('meeting_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="meeting_password">Meeting Password</label>
                            <input type="text" class="form-control @error('meeting_password') is-invalid @enderror"
                                id="meeting_password" name="meeting_password"
                                value="{{ old('meeting_password', $interview->meeting_password) }}">
                            @error('meeting_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Information -->
                        <div class="col-12">
                            <label class="form-label" for="preparation_notes">Preparation Notes</label>
                            <textarea class="form-control @error('preparation_notes') is-invalid @enderror" id="preparation_notes"
                                name="preparation_notes" rows="3">{{ old('preparation_notes', $interview->preparation_notes) }}</textarea>
                            @error('preparation_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="questions">Interview Questions</label>
                            <textarea class="form-control @error('questions') is-invalid @enderror" id="questions" name="questions"
                                rows="3" placeholder="Enter questions separated by new lines">{{ old('questions', is_array($interview->questions) ? implode("\n", $interview->questions) : '') }}</textarea>
                            @error('questions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="evaluation_criteria">Evaluation Criteria</label>
                            <textarea class="form-control @error('evaluation_criteria') is-invalid @enderror" id="evaluation_criteria"
                                name="evaluation_criteria" rows="3" placeholder="Enter criteria separated by new lines">{{ old('evaluation_criteria', is_array($interview->evaluation_criteria) ? implode("\n", $interview->evaluation_criteria) : '') }}</textarea>
                            @error('evaluation_criteria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Interview Results -->
                        @if ($interview->status !== 'scheduled')
                            <div class="col-12 mt-4">
                                <h5>Interview Results</h5>
                                <hr class="my-3">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="technical_skills_rating">Technical Skills Rating</label>
                                <input type="number"
                                    class="form-control @error('technical_skills_rating') is-invalid @enderror"
                                    id="technical_skills_rating" name="technical_skills_rating"
                                    value="{{ old('technical_skills_rating', $interview->technical_skills_rating) }}"
                                    min="1" max="5" step="0.5">
                                @error('technical_skills_rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="soft_skills_rating">Soft Skills Rating</label>
                                <input type="number"
                                    class="form-control @error('soft_skills_rating') is-invalid @enderror"
                                    id="soft_skills_rating" name="soft_skills_rating"
                                    value="{{ old('soft_skills_rating', $interview->soft_skills_rating) }}"
                                    min="1" max="5" step="0.5">
                                @error('soft_skills_rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="cultural_fit_rating">Cultural Fit Rating</label>
                                <input type="number"
                                    class="form-control @error('cultural_fit_rating') is-invalid @enderror"
                                    id="cultural_fit_rating" name="cultural_fit_rating"
                                    value="{{ old('cultural_fit_rating', $interview->cultural_fit_rating) }}"
                                    min="1" max="5" step="0.5">
                                @error('cultural_fit_rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="overall_rating">Overall Rating</label>
                                <input type="number" class="form-control @error('overall_rating') is-invalid @enderror"
                                    id="overall_rating" name="overall_rating"
                                    value="{{ old('overall_rating', $interview->overall_rating) }}" min="1"
                                    max="5" step="0.5">
                                @error('overall_rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="interviewer_notes">Interviewer Notes</label>
                                <textarea class="form-control @error('interviewer_notes') is-invalid @enderror" id="interviewer_notes"
                                    name="interviewer_notes" rows="3">{{ old('interviewer_notes', $interview->interviewer_notes) }}</textarea>
                                @error('interviewer_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="candidate_feedback">Candidate Feedback</label>
                                <textarea class="form-control @error('candidate_feedback') is-invalid @enderror" id="candidate_feedback"
                                    name="candidate_feedback" rows="3">{{ old('candidate_feedback', $interview->candidate_feedback) }}</textarea>
                                @error('candidate_feedback')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="next_steps">Next Steps</label>
                                <textarea class="form-control @error('next_steps') is-invalid @enderror" id="next_steps" name="next_steps"
                                    rows="3">{{ old('next_steps', $interview->next_steps) }}</textarea>
                                @error('next_steps')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- Status -->
                        <div class="col-md-12">
                            <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                name="status" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(old('status', $interview->status) == $status)>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12" id="cancellationReasonGroup" style="display: none;">
                            <label class="form-label" for="cancellation_reason">Cancellation Reason</label>
                            <textarea class="form-control @error('cancellation_reason') is-invalid @enderror" id="cancellation_reason"
                                name="cancellation_reason" rows="3">{{ old('cancellation_reason', $interview->cancellation_reason) }}</textarea>
                            @error('cancellation_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-save-line"></i> Update Interview
                            </button>
                            <a href="{{ route('interviews.show', $interview) }}" class="btn btn-secondary">
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
        // Convert questions and criteria to arrays before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const questionsInput = document.getElementById('questions');
            const criteriaInput = document.getElementById('evaluation_criteria');

            if (questionsInput.value) {
                const questionsArray = questionsInput.value.split('\n').filter(q => q.trim());
                questionsInput.value = JSON.stringify(questionsArray);
            }

            if (criteriaInput.value) {
                const criteriaArray = criteriaInput.value.split('\n').filter(c => c.trim());
                criteriaInput.value = JSON.stringify(criteriaArray);
            }
        });

        // Show/hide cancellation reason based on status
        document.getElementById('status').addEventListener('change', function() {
            const cancellationReasonGroup = document.getElementById('cancellationReasonGroup');
            const cancellationReason = document.getElementById('cancellation_reason');

            if (this.value === 'cancelled') {
                cancellationReasonGroup.style.display = 'block';
                cancellationReason.setAttribute('required', 'required');
            } else {
                cancellationReasonGroup.style.display = 'none';
                cancellationReason.removeAttribute('required');
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
                                candidate.id == {{ old('candidate_id', $interview->candidate_id) }}
                            );
                            candidateSelect.add(option);
                        });
                    });
            }
        });

        // Trigger change events on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('status').dispatchEvent(new Event('change'));
            const jobPostingSelect = document.getElementById('job_posting_id');
            if (jobPostingSelect.value) {
                jobPostingSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection

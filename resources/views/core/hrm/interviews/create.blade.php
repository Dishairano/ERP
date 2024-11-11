@extends('layouts/contentNavbarLayout')

@section('title', 'Schedule Interview')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Schedule Interview</h4>
            <a href="{{ route('interviews.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line"></i> Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('interviews.store') }}" method="POST">
                    @csrf

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
                                    <option value="{{ $posting->id }}" @selected(old('job_posting_id', request('job_posting_id')) == $posting->id)>
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
                                    <option value="{{ $candidate->id }}" @selected(old('candidate_id', request('candidate_id')) == $candidate->id)>
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
                                    <option value="{{ $type }}" @selected(old('interview_type') == $type)>
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
                                id="round_number" name="round_number" value="{{ old('round_number', 1) }}" min="1"
                                required>
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
                                    <option value="{{ $interviewer->id }}" @selected(old('interviewer_id') == $interviewer->id)>
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

                        <!-- Location/Meeting Details -->
                        <div class="col-md-6">
                            <label class="form-label" for="location">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                id="location" name="location" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="meeting_link">Meeting Link</label>
                            <input type="url" class="form-control @error('meeting_link') is-invalid @enderror"
                                id="meeting_link" name="meeting_link" value="{{ old('meeting_link') }}">
                            @error('meeting_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="meeting_id">Meeting ID</label>
                            <input type="text" class="form-control @error('meeting_id') is-invalid @enderror"
                                id="meeting_id" name="meeting_id" value="{{ old('meeting_id') }}">
                            @error('meeting_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="meeting_password">Meeting Password</label>
                            <input type="text" class="form-control @error('meeting_password') is-invalid @enderror"
                                id="meeting_password" name="meeting_password" value="{{ old('meeting_password') }}">
                            @error('meeting_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Information -->
                        <div class="col-12">
                            <label class="form-label" for="preparation_notes">Preparation Notes</label>
                            <textarea class="form-control @error('preparation_notes') is-invalid @enderror" id="preparation_notes"
                                name="preparation_notes" rows="3">{{ old('preparation_notes') }}</textarea>
                            @error('preparation_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="questions">Interview Questions</label>
                            <textarea class="form-control @error('questions') is-invalid @enderror" id="questions" name="questions"
                                rows="3" placeholder="Enter questions separated by new lines">{{ old('questions') }}</textarea>
                            @error('questions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="evaluation_criteria">Evaluation Criteria</label>
                            <textarea class="form-control @error('evaluation_criteria') is-invalid @enderror" id="evaluation_criteria"
                                name="evaluation_criteria" rows="3" placeholder="Enter criteria separated by new lines">{{ old('evaluation_criteria') }}</textarea>
                            @error('evaluation_criteria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-calendar-check-line"></i> Schedule Interview
                            </button>
                            <a href="{{ route('interviews.index') }}" class="btn btn-secondary">
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

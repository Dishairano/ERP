@extends('layouts/contentNavbarLayout')

@section('title', 'Add Candidate')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Add Candidate</h4>
            <a href="{{ route('candidates.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line"></i> Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- Job Information -->
                        <div class="col-12">
                            <h5>Job Information</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="job_posting_id">Position Applied For <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('job_posting_id') is-invalid @enderror" id="job_posting_id"
                                name="job_posting_id" required>
                                <option value="">Select Position</option>
                                @foreach ($jobPostings as $posting)
                                    <option value="{{ $posting->id }}" @selected(old('job_posting_id', request('job_posting_id')) == $posting->id)>
                                        {{ $posting->title }} ({{ $posting->department->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('job_posting_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Personal Information -->
                        <div class="col-12 mt-4">
                            <h5>Personal Information</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label class="form-label" for="address">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                                name="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="city">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                name="city" value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="state">State</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" id="state"
                                name="state" value="{{ old('state') }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="postal_code">Postal Code</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Professional Information -->
                        <div class="col-12 mt-4">
                            <h5>Professional Information</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="current_company">Current Company</label>
                            <input type="text" class="form-control @error('current_company') is-invalid @enderror"
                                id="current_company" name="current_company" value="{{ old('current_company') }}">
                            @error('current_company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="current_position">Current Position</label>
                            <input type="text" class="form-control @error('current_position') is-invalid @enderror"
                                id="current_position" name="current_position" value="{{ old('current_position') }}">
                            @error('current_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="experience_years">Years of Experience</label>
                            <input type="number" class="form-control @error('experience_years') is-invalid @enderror"
                                id="experience_years" name="experience_years" value="{{ old('experience_years') }}"
                                step="0.5" min="0">
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="education_level">Education Level</label>
                            <select class="form-select @error('education_level') is-invalid @enderror"
                                id="education_level" name="education_level">
                                <option value="">Select Level</option>
                                @foreach ($educationLevels as $level)
                                    <option value="{{ $level }}" @selected(old('education_level') == $level)>
                                        {{ ucfirst(str_replace('_', ' ', $level)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('education_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="field_of_study">Field of Study</label>
                            <input type="text" class="form-control @error('field_of_study') is-invalid @enderror"
                                id="field_of_study" name="field_of_study" value="{{ old('field_of_study') }}">
                            @error('field_of_study')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="skills">Skills</label>
                            <input type="text" class="form-control @error('skills') is-invalid @enderror"
                                id="skills" name="skills" value="{{ old('skills') }}"
                                placeholder="Enter skills separated by commas">
                            @error('skills')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Documents -->
                        <div class="col-12 mt-4">
                            <h5>Documents</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="resume">Resume</label>
                            <input type="file" class="form-control @error('resume') is-invalid @enderror"
                                id="resume" name="resume" accept=".pdf,.doc,.docx">
                            @error('resume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="cover_letter">Cover Letter</label>
                            <input type="file" class="form-control @error('cover_letter') is-invalid @enderror"
                                id="cover_letter" name="cover_letter" accept=".pdf,.doc,.docx">
                            @error('cover_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Links -->
                        <div class="col-12 mt-4">
                            <h5>Professional Links</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="portfolio_url">Portfolio URL</label>
                            <input type="url" class="form-control @error('portfolio_url') is-invalid @enderror"
                                id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url') }}">
                            @error('portfolio_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="linkedin_url">LinkedIn URL</label>
                            <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror"
                                id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}">
                            @error('linkedin_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="github_url">GitHub URL</label>
                            <input type="url" class="form-control @error('github_url') is-invalid @enderror"
                                id="github_url" name="github_url" value="{{ old('github_url') }}">
                            @error('github_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-save-line"></i> Save Candidate
                            </button>
                            <a href="{{ route('candidates.index') }}" class="btn btn-secondary">
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
        // Convert skills input to array before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const skillsInput = document.getElementById('skills');
            if (skillsInput.value) {
                const skillsArray = skillsInput.value.split(',').map(skill => skill.trim());
                skillsInput.value = JSON.stringify(skillsArray);
            }
        });
    </script>
@endsection

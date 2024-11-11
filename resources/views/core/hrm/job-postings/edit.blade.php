@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Job Posting')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Edit Job Posting</h4>
            <div>
                <a href="{{ route('job-postings.show', $jobPosting) }}" class="btn btn-primary me-2">
                    <i class="ri-eye-line"></i> View Posting
                </a>
                <a href="{{ route('job-postings.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('job-postings.update', $jobPosting) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h5>Basic Information</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="title">Job Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title', $jobPosting->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="department_id">Department <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('department_id') is-invalid @enderror" id="department_id"
                                name="department_id" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected(old('department_id', $jobPosting->department_id) == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="position_type">Position Type <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('position_type') is-invalid @enderror" id="position_type"
                                name="position_type" required>
                                <option value="">Select Type</option>
                                @foreach ($positionTypes as $type)
                                    <option value="{{ $type }}" @selected(old('position_type', $jobPosting->position_type) == $type)>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('position_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="experience_level">Experience Level <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('experience_level') is-invalid @enderror"
                                id="experience_level" name="experience_level" required>
                                <option value="">Select Level</option>
                                @foreach ($experienceLevels as $level)
                                    <option value="{{ $level }}" @selected(old('experience_level', $jobPosting->experience_level) == $level)>
                                        {{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('experience_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="number_of_positions">Number of Positions <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('number_of_positions') is-invalid @enderror"
                                id="number_of_positions" name="number_of_positions"
                                value="{{ old('number_of_positions', $jobPosting->number_of_positions) }}" min="1"
                                required>
                            @error('number_of_positions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location Information -->
                        <div class="col-12 mt-4">
                            <h5>Location Information</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="location_type">Location Type <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('location_type') is-invalid @enderror" id="location_type"
                                name="location_type" required>
                                <option value="">Select Type</option>
                                @foreach ($locationTypes as $type)
                                    <option value="{{ $type }}" @selected(old('location_type', $jobPosting->location_type) == $type)>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="location">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                id="location" name="location" value="{{ old('location', $jobPosting->location) }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Compensation -->
                        <div class="col-12 mt-4">
                            <h5>Compensation</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="salary_min">Minimum Salary</label>
                            <input type="number" class="form-control @error('salary_min') is-invalid @enderror"
                                id="salary_min" name="salary_min" value="{{ old('salary_min', $jobPosting->salary_min) }}"
                                step="0.01" min="0">
                            @error('salary_min')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="salary_max">Maximum Salary</label>
                            <input type="number" class="form-control @error('salary_max') is-invalid @enderror"
                                id="salary_max" name="salary_max"
                                value="{{ old('salary_max', $jobPosting->salary_max) }}" step="0.01" min="0">
                            @error('salary_max')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="salary_currency">Currency <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('salary_currency') is-invalid @enderror"
                                id="salary_currency" name="salary_currency"
                                value="{{ old('salary_currency', $jobPosting->salary_currency) }}" maxlength="3"
                                required>
                            @error('salary_currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="salary_period">Period <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('salary_period') is-invalid @enderror" id="salary_period"
                                name="salary_period" required>
                                <option value="">Select Period</option>
                                @foreach ($salaryPeriods as $period)
                                    <option value="{{ $period }}" @selected(old('salary_period', $jobPosting->salary_period) == $period)>
                                        {{ ucfirst($period) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('salary_period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Job Details -->
                        <div class="col-12 mt-4">
                            <h5>Job Details</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="description">Job Description <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="5" required>{{ old('description', $jobPosting->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="requirements">Requirements</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements"
                                rows="5">{{ old('requirements', $jobPosting->requirements) }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="responsibilities">Responsibilities</label>
                            <textarea class="form-control @error('responsibilities') is-invalid @enderror" id="responsibilities"
                                name="responsibilities" rows="5">{{ old('responsibilities', $jobPosting->responsibilities) }}</textarea>
                            @error('responsibilities')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="qualifications">Qualifications</label>
                            <textarea class="form-control @error('qualifications') is-invalid @enderror" id="qualifications"
                                name="qualifications" rows="5">{{ old('qualifications', $jobPosting->qualifications) }}</textarea>
                            @error('qualifications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="benefits">Benefits</label>
                            <textarea class="form-control @error('benefits') is-invalid @enderror" id="benefits" name="benefits"
                                rows="5">{{ old('benefits', $jobPosting->benefits) }}</textarea>
                            @error('benefits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="skills_required">Required Skills</label>
                            <input type="text" class="form-control @error('skills_required') is-invalid @enderror"
                                id="skills_required" name="skills_required"
                                value="{{ old('skills_required', is_array($jobPosting->skills_required) ? implode(', ', $jobPosting->skills_required) : '') }}"
                                placeholder="Enter skills separated by commas">
                            @error('skills_required')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Posting Details -->
                        <div class="col-12 mt-4">
                            <h5>Posting Details</h5>
                            <hr class="my-3">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="posting_date">Posting Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('posting_date') is-invalid @enderror"
                                id="posting_date" name="posting_date"
                                value="{{ old('posting_date', $jobPosting->posting_date?->format('Y-m-d')) }}" required>
                            @error('posting_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="application_deadline">Application Deadline</label>
                            <input type="date"
                                class="form-control @error('application_deadline') is-invalid @enderror"
                                id="application_deadline" name="application_deadline"
                                value="{{ old('application_deadline', $jobPosting->application_deadline?->format('Y-m-d')) }}">
                            @error('application_deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                name="status" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(old('status', $jobPosting->status) == $status)>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="internal_notes">Internal Notes</label>
                            <textarea class="form-control @error('internal_notes') is-invalid @enderror" id="internal_notes"
                                name="internal_notes" rows="3">{{ old('internal_notes', $jobPosting->internal_notes) }}</textarea>
                            @error('internal_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-save-line"></i> Update Job Posting
                            </button>
                            <a href="{{ route('job-postings.show', $jobPosting) }}" class="btn btn-secondary">
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
            const skillsInput = document.getElementById('skills_required');
            if (skillsInput.value) {
                const skillsArray = skillsInput.value.split(',').map(skill => skill.trim());
                skillsInput.value = JSON.stringify(skillsArray);
            }
        });

        // Validate salary range
        document.getElementById('salary_max').addEventListener('input', function() {
            const minSalary = parseFloat(document.getElementById('salary_min').value) || 0;
            const maxSalary = parseFloat(this.value) || 0;

            if (maxSalary < minSalary) {
                this.setCustomValidity('Maximum salary must be greater than minimum salary');
            } else {
                this.setCustomValidity('');
            }
        });

        // Update location field requirement based on location type
        document.getElementById('location_type').addEventListener('change', function() {
            const locationField = document.getElementById('location');
            if (this.value === 'remote') {
                locationField.removeAttribute('required');
            } else {
                locationField.setAttribute('required', 'required');
            }
        });

        // Trigger location type change event on page load
        document.addEventListener('DOMContentLoaded', function() {
            const locationTypeSelect = document.getElementById('location_type');
            locationTypeSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endsection

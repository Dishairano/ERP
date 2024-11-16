@extends('layouts/contentNavbarLayout')

@section('title', 'Create Leave Request')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">New Leave Request</h5>
                    <a href="{{ route('leave-requests.dashboard') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back to Dashboard
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('leave-requests.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Leave Type</label>
                                <select name="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} ({{ $type->days_per_year }} days/year)
                                        </option>
                                    @endforeach
                                </select>
                                @error('leave_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="text" name="start_date"
                                    class="form-control flatpickr-date @error('start_date') is-invalid @enderror"
                                    placeholder="YYYY-MM-DD"
                                    value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="text" name="end_date"
                                    class="form-control flatpickr-date @error('end_date') is-invalid @enderror"
                                    placeholder="YYYY-MM-DD"
                                    value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror"
                                rows="3" placeholder="Enter your leave reason" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Create Request</button>
                            <a href="{{ route('leave-requests.dashboard') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date pickers
        flatpickr('.flatpickr-date', {
            dateFormat: 'Y-m-d',
            minDate: 'today',
            allowInput: true
        });
    });
</script>
@endsection

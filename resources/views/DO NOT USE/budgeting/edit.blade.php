@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Budget')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Edit Budget</h4>
                                <p class="mb-0">Modify budget details and allocations</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <a href="{{ route('budgets.show', $budget ?? 1) }}" class="btn btn-label-secondary">
                                    <i class="ri-arrow-left-line"></i> Back to Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Budget Details</h5>
                            <span class="badge bg-label-success">Active</span>
                        </div>
                    </div>
                    <form action="{{ route('budgets.update', $budget ?? 1) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Budget Name</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $budget->name ?? 'Annual Budget 2024' }}" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Budget Type</label>
                                    <select class="form-select" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="department"
                                            {{ ($budget->type ?? '') == 'department' ? 'selected' : '' }}>Department Budget
                                        </option>
                                        <option value="project" {{ ($budget->type ?? '') == 'project' ? 'selected' : '' }}>
                                            Project Budget</option>
                                        <option value="campaign"
                                            {{ ($budget->type ?? '') == 'campaign' ? 'selected' : '' }}>Campaign Budget
                                        </option>
                                        <option value="other" {{ ($budget->type ?? '') == 'other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date"
                                        value="{{ $budget->start_date ?? '2024-01-01' }}" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date"
                                        value="{{ $budget->end_date ?? '2024-12-31' }}" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Total Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="total_amount" step="0.01"
                                            min="0" value="{{ $budget->total_amount ?? 100000 }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Currency</label>
                                    <select class="form-select" name="currency" required>
                                        <option value="USD" {{ ($budget->currency ?? '') == 'USD' ? 'selected' : '' }}>
                                            USD - US Dollar</option>
                                        <option value="EUR" {{ ($budget->currency ?? '') == 'EUR' ? 'selected' : '' }}>
                                            EUR - Euro</option>
                                        <option value="GBP" {{ ($budget->currency ?? '') == 'GBP' ? 'selected' : '' }}>
                                            GBP - British Pound</option>
                                        <!-- Add more currencies as needed -->
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Provide a description of this budget...">{{ $budget->description ?? '' }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Department/Project Assignment</label>
                                    <select class="form-select" name="assignment_id">
                                        <option value="">Select Assignment</option>
                                        <!-- Departments/Projects will be populated here -->
                                    </select>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="track_monthly"
                                            id="track_monthly" {{ $budget->track_monthly ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="track_monthly">
                                            Track budget by month
                                        </label>
                                    </div>
                                </div>

                                <!-- Monthly Allocation -->
                                <div
                                    class="col-12 monthly-allocation {{ $budget->track_monthly ?? false ? '' : 'd-none' }}">
                                    <label class="form-label">Monthly Allocation</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Amount ($)</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $month)
                                                    <tr>
                                                        <td>{{ $month }}</td>
                                                        <td>
                                                            <input type="number"
                                                                class="form-control form-control-sm monthly-amount"
                                                                name="monthly_amounts[]" step="0.01" min="0"
                                                                value="{{ $budget->monthly_amounts[$index] ?? 0 }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm"
                                                                name="monthly_notes[]"
                                                                placeholder="Notes for {{ $month }}"
                                                                value="{{ $budget->monthly_notes[$index] ?? '' }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                    <th class="monthly-total">$0.00</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Categories</label>
                                    <div class="repeater-categories">
                                        <div data-repeater-list="categories">
                                            @forelse($budget->categories ?? [] as $category)
                                                <div data-repeater-item class="row g-3 mb-3">
                                                    <div class="col-5">
                                                        <input type="text" class="form-control" name="category_name"
                                                            placeholder="Category Name" value="{{ $category->name }}">
                                                    </div>
                                                    <div class="col-5">
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" class="form-control"
                                                                name="category_amount" step="0.01" min="0"
                                                                value="{{ $category->amount }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-label-danger"
                                                            data-repeater-delete>
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @empty
                                                <div data-repeater-item class="row g-3 mb-3">
                                                    <div class="col-5">
                                                        <input type="text" class="form-control" name="category_name"
                                                            placeholder="Category Name">
                                                    </div>
                                                    <div class="col-5">
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" class="form-control"
                                                                name="category_amount" step="0.01" min="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-label-danger"
                                                            data-repeater-delete>
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                        <button type="button" class="btn btn-primary" data-repeater-create>
                                            <i class="ri-add-line"></i> Add Category
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Current Attachments</label>
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @forelse($budget->attachments ?? [] as $attachment)
                                            <div class="d-flex align-items-center border rounded p-2">
                                                <i class="ri-file-line me-2"></i>
                                                <span>{{ $attachment->name }}</span>
                                                <button type="button" class="btn btn-text-danger btn-sm ms-2">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>
                                        @empty
                                            <p class="text-muted mb-0">No attachments</p>
                                        @endforelse
                                    </div>

                                    <label class="form-label">Add New Attachments</label>
                                    <input type="file" class="form-control" name="attachments[]" multiple>
                                    <small class="text-muted">Upload any supporting documents (optional)</small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Tags</label>
                                    <input type="text" class="form-control" name="tags"
                                        placeholder="Enter tags separated by commas" value="{{ $budget->tags ?? '' }}">
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notifications_enabled"
                                            id="notifications_enabled"
                                            {{ $budget->notifications_enabled ?? true ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notifications_enabled">
                                            Enable budget notifications
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="auto_renewal"
                                            id="auto_renewal" {{ $budget->auto_renewal ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auto_renewal">
                                            Enable automatic renewal
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-label-secondary me-2"
                                onclick="history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Budget</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle monthly tracking toggle
            const trackMonthlyCheckbox = document.getElementById('track_monthly');
            const monthlyAllocation = document.querySelector('.monthly-allocation');

            trackMonthlyCheckbox.addEventListener('change', function() {
                monthlyAllocation.classList.toggle('d-none', !this.checked);
            });

            // Handle monthly amount calculations
            const monthlyAmounts = document.querySelectorAll('.monthly-amount');
            const monthlyTotal = document.querySelector('.monthly-total');

            monthlyAmounts.forEach(input => {
                input.addEventListener('input', updateMonthlyTotal);
            });

            function updateMonthlyTotal() {
                let total = 0;
                monthlyAmounts.forEach(input => {
                    total += parseFloat(input.value || 0);
                });
                monthlyTotal.textContent = `$${total.toFixed(2)}`;
            }

            // Initialize repeater for categories
            $('.repeater-categories').repeater({
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });

            // Initial calculations
            updateMonthlyTotal();
        });
    </script>
@endsection

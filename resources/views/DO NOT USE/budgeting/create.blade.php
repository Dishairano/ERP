@extends('layouts/contentNavbarLayout')

@section('title', 'Create Budget')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Create Budget</h4>
                                <p class="mb-0">Create a new budget for your organization</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <a href="{{ route('budgets.index') }}" class="btn btn-label-secondary">
                                    <i class="ri-arrow-left-line"></i> Back to List
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
                        <h5 class="card-title mb-0">Budget Details</h5>
                    </div>
                    <form action="{{ route('budgets.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Budget Name</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="e.g., Annual Budget 2024" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Budget Type</label>
                                    <select class="form-select" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="department">Department Budget</option>
                                        <option value="project">Project Budget</option>
                                        <option value="campaign">Campaign Budget</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Total Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="total_amount" step="0.01"
                                            min="0" required>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Currency</label>
                                    <select class="form-select" name="currency" required>
                                        <option value="USD">USD - US Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GBP">GBP - British Pound</option>
                                        <!-- Add more currencies as needed -->
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Provide a description of this budget..."></textarea>
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
                                            id="track_monthly">
                                        <label class="form-check-label" for="track_monthly">
                                            Track budget by month
                                        </label>
                                    </div>
                                </div>

                                <!-- Monthly Allocation (shown when track_monthly is checked) -->
                                <div class="col-12 monthly-allocation d-none">
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
                                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                                    <tr>
                                                        <td>{{ $month }}</td>
                                                        <td>
                                                            <input type="number"
                                                                class="form-control form-control-sm monthly-amount"
                                                                name="monthly_amounts[]" step="0.01" min="0">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm"
                                                                name="monthly_notes[]"
                                                                placeholder="Notes for {{ $month }}">
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
                                            <div data-repeater-item class="row g-3 mb-3">
                                                <div class="col-5">
                                                    <input type="text" class="form-control" name="category_name"
                                                        placeholder="Category Name">
                                                </div>
                                                <div class="col-5">
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" class="form-control" name="category_amount"
                                                            step="0.01" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <button type="button" class="btn btn-label-danger"
                                                        data-repeater-delete>
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" data-repeater-create>
                                            <i class="ri-add-line"></i> Add Category
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Attachments</label>
                                    <input type="file" class="form-control" name="attachments[]" multiple>
                                    <small class="text-muted">Upload any supporting documents (optional)</small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Tags</label>
                                    <input type="text" class="form-control" name="tags"
                                        placeholder="Enter tags separated by commas">
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notifications_enabled"
                                            id="notifications_enabled" checked>
                                        <label class="form-check-label" for="notifications_enabled">
                                            Enable budget notifications
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="auto_renewal"
                                            id="auto_renewal">
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
                            <button type="submit" class="btn btn-primary">Create Budget</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Budget Guidelines -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Budget Creation Guidelines</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Tips for Effective Budget Planning</h6>
                                <ul class="mb-0">
                                    <li>Set realistic budget amounts based on historical data</li>
                                    <li>Consider seasonal variations in monthly allocations</li>
                                    <li>Use categories to track different types of expenses</li>
                                    <li>Attach relevant documentation for reference</li>
                                    <li>Enable notifications to stay informed of budget status</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Common Budget Categories</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-label-primary">Personnel</span>
                                    <span class="badge bg-label-info">Equipment</span>
                                    <span class="badge bg-label-success">Marketing</span>
                                    <span class="badge bg-label-warning">Travel</span>
                                    <span class="badge bg-label-danger">Software</span>
                                    <span class="badge bg-label-secondary">Training</span>
                                </div>
                            </div>
                        </div>
                    </div>
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
        });
    </script>
@endsection

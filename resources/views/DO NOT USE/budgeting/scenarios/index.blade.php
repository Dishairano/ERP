@extends('layouts/contentNavbarLayout')

@section('title', 'Budget Scenarios')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Budgets /</span> Scenarios
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Budget Scenarios</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createScenarioModal">
                    <i class="bx bx-plus me-1"></i> Create Scenario
                </button>
            </div>
            <div class="card-body">
                @if ($scenarios->isEmpty())
                    <div class="text-center p-5">
                        <h6 class="text-muted">No budget scenarios found</h6>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Budget</th>
                                    <th>Original Amount</th>
                                    <th>Modified Amount</th>
                                    <th>Adjustment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($scenarios as $scenario)
                                    <tr>
                                        <td>{{ $scenario->name }}</td>
                                        <td>{{ $scenario->budget->category_name ?? 'N/A' }}</td>
                                        <td>{{ number_format($scenario->original_amount, 2) }}</td>
                                        <td>{{ number_format($scenario->modified_amount, 2) }}</td>
                                        <td>
                                            @if ($scenario->adjustment_type === 'fixed')
                                                Fixed Amount
                                            @else
                                                {{ $scenario->adjustment_type === 'increase' ? '+' : '-' }}{{ $scenario->adjustment_percentage }}%
                                            @endif
                                        </td>
                                        <td>
                                            @switch($scenario->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @break

                                                @case('applied')
                                                    <span class="badge bg-success">Applied</span>
                                                @break

                                                @case('rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @break

                                                @default
                                                    <span class="badge bg-secondary">{{ $scenario->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('budgets.scenarios.show', $scenario->id) }}">
                                                        <i class="bx bx-show-alt me-1"></i> View Details
                                                    </a>
                                                    @if ($scenario->status === 'pending')
                                                        <form action="{{ route('budgets.scenarios.apply', $scenario->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bx bx-check me-1"></i> Apply Scenario
                                                            </button>
                                                        </form>
                                                        <form
                                                            action="{{ route('budgets.scenarios.reject', $scenario->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bx bx-x me-1"></i> Reject Scenario
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Scenario Modal -->
    <div class="modal fade" id="createScenarioModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Budget Scenario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('budgets.scenarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Budget</label>
                            <select class="form-select" name="budget_id" required>
                                <option value="">Select Budget</option>
                                @foreach ($budgets as $budget)
                                    <option value="{{ $budget->id }}">
                                        {{ $budget->category_name }} ({{ number_format($budget->planned_amount, 2) }}
                                        {{ $budget->currency }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Scenario Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adjustment Type</label>
                            <select class="form-select" name="adjustment_type" required>
                                <option value="increase">Increase by Percentage</option>
                                <option value="decrease">Decrease by Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>
                        <div class="mb-3 adjustment-percentage">
                            <label class="form-label">Adjustment Percentage</label>
                            <input type="number" class="form-control" name="adjustment_percentage" min="0"
                                max="100" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Modified Amount</label>
                            <input type="number" class="form-control" name="modified_amount" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" name="reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Scenario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('select[name="adjustment_type"]').change(function() {
                const percentageField = $('.adjustment-percentage');
                if ($(this).val() === 'fixed') {
                    percentageField.hide();
                    $('input[name="adjustment_percentage"]').prop('required', false);
                } else {
                    percentageField.show();
                    $('input[name="adjustment_percentage"]').prop('required', true);
                }
            });

            $('select[name="budget_id"]').change(function() {
                const selectedOption = $(this).find('option:selected');
                const amount = selectedOption.text().match(/\(([\d,]+\.\d{2})/);
                if (amount) {
                    $('input[name="modified_amount"]').val(amount[1].replace(',', ''));
                }
            });

            $('input[name="adjustment_percentage"]').change(function() {
                const budgetId = $('select[name="budget_id"]').val();
                if (!budgetId) return;

                const selectedOption = $('select[name="budget_id"]').find('option:selected');
                const amount = selectedOption.text().match(/\(([\d,]+\.\d{2})/);
                if (!amount) return;

                const originalAmount = parseFloat(amount[1].replace(',', ''));
                const percentage = parseFloat($(this).val()) / 100;
                const adjustmentType = $('select[name="adjustment_type"]').val();

                let modifiedAmount;
                if (adjustmentType === 'increase') {
                    modifiedAmount = originalAmount * (1 + percentage);
                } else if (adjustmentType === 'decrease') {
                    modifiedAmount = originalAmount * (1 - percentage);
                }

                if (modifiedAmount) {
                    $('input[name="modified_amount"]').val(modifiedAmount.toFixed(2));
                }
            });
        });
    </script>
@endpush

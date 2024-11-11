@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgets')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Budgets /</span> Projects
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Project Budgets</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBudgetModal">
                    <i class="bx bx-plus me-1"></i> Add Budget
                </button>
            </div>
            <div class="card-body">
                @if ($projects->isEmpty())
                    <div class="text-center p-5">
                        <h6 class="text-muted">No project budgets found</h6>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Budget Amount</th>
                                    <th>Fiscal Year</th>
                                    <th>Spent</th>
                                    <th>Remaining</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $project)
                                    <tr>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ number_format($project->budget->planned_amount ?? 0, 2) }}</td>
                                        <td>{{ $project->budget->fiscal_year ?? date('Y') }}</td>
                                        <td>{{ number_format($project->budget->actual_amount ?? 0, 2) }}</td>
                                        <td>{{ number_format(($project->budget->planned_amount ?? 0) - ($project->budget->actual_amount ?? 0), 2) }}
                                        </td>
                                        <td>
                                            @if (isset($project->budget))
                                                @if ($project->budget->actual_amount <= $project->budget->planned_amount * 0.75)
                                                    <span class="badge bg-success">Healthy</span>
                                                @elseif($project->budget->actual_amount <= $project->budget->planned_amount)
                                                    <span class="badge bg-warning">Warning</span>
                                                @else
                                                    <span class="badge bg-danger">Over Budget</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">No Budget</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('budgets.projects.show', $project->id) }}">
                                                        <i class="bx bx-show-alt me-1"></i> View Details
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editBudgetModal{{ $project->id }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Edit Budget
                                                    </a>
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

    <!-- Create Budget Modal -->
    <div class="modal fade" id="createBudgetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Project Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('budgets.projects.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Project</label>
                            <select class="form-select" name="project_id" required>
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Planned Amount</label>
                            <input type="number" class="form-control" name="planned_amount" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Currency</label>
                            <select class="form-select" name="currency" required>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alert Threshold (%)</label>
                            <input type="number" class="form-control" name="alert_threshold_percentage" value="80"
                                min="0" max="100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Requires Approval</label>
                            <select class="form-select" name="requires_approval" required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Period Type</label>
                            <select class="form-select" name="period_type" required>
                                <option value="yearly">Yearly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fiscal Year</label>
                            <input type="number" class="form-control" name="fiscal_year" value="{{ date('Y') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Period Number</label>
                            <input type="number" class="form-control" name="period_number" min="1" max="12">
                            <small class="text-muted">Required for quarterly (1-4) or monthly (1-12) periods</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Budget</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($projects as $project)
        <!-- Edit Budget Modal -->
        <div class="modal fade" id="editBudgetModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Project Budget</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('budgets.projects.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Project</label>
                                <input type="text" class="form-control" value="{{ $project->name }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Planned Amount</label>
                                <input type="number" class="form-control" name="planned_amount" step="0.01"
                                    value="{{ $project->budget->planned_amount ?? 0 }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Currency</label>
                                <select class="form-select" name="currency" required>
                                    <option value="EUR"
                                        {{ ($project->budget->currency ?? 'EUR') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="USD"
                                        {{ ($project->budget->currency ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="GBP"
                                        {{ ($project->budget->currency ?? '') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alert Threshold (%)</label>
                                <input type="number" class="form-control" name="alert_threshold_percentage"
                                    value="{{ $project->budget->alert_threshold_percentage ?? 80 }}" min="0"
                                    max="100" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Requires Approval</label>
                                <select class="form-select" name="requires_approval" required>
                                    <option value="0"
                                        {{ ($project->budget->requires_approval ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1"
                                        {{ ($project->budget->requires_approval ?? 0) == 1 ? 'selected' : '' }}>Yes
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Period Type</label>
                                <select class="form-select" name="period_type" required>
                                    <option value="yearly"
                                        {{ ($project->budget->period_type ?? 'yearly') == 'yearly' ? 'selected' : '' }}>
                                        Yearly</option>
                                    <option value="quarterly"
                                        {{ ($project->budget->period_type ?? '') == 'quarterly' ? 'selected' : '' }}>
                                        Quarterly</option>
                                    <option value="monthly"
                                        {{ ($project->budget->period_type ?? '') == 'monthly' ? 'selected' : '' }}>Monthly
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Fiscal Year</label>
                                <input type="number" class="form-control" name="fiscal_year"
                                    value="{{ $project->budget->fiscal_year ?? date('Y') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Period Number</label>
                                <input type="number" class="form-control" name="period_number"
                                    value="{{ $project->budget->period_number ?? '' }}" min="1" max="12">
                                <small class="text-muted">Required for quarterly (1-4) or monthly (1-12) periods</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Budget</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('select[name="period_type"]').change(function() {
                const periodNumber = $(this).closest('form').find('input[name="period_number"]');
                if ($(this).val() === 'yearly') {
                    periodNumber.prop('disabled', true).val('');
                } else {
                    periodNumber.prop('disabled', false);
                    if ($(this).val() === 'quarterly') {
                        periodNumber.attr('max', '4');
                    } else {
                        periodNumber.attr('max', '12');
                    }
                }
            }).trigger('change');
        });
    </script>
@endpush

@extends('layouts/contentNavbarLayout')

@section('title', 'Budgets')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Budgets</h5>
                        <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                            Create Budget
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Department/Project</th>
                                        <th>Fiscal Year</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($budgets as $budget)
                                        <tr>
                                            <td>{{ $budget->name }}</td>
                                            <td>{{ ucfirst($budget->type) }}</td>
                                            <td>
                                                @if ($budget->type === 'department')
                                                    {{ $budget->department->name }}
                                                @else
                                                    {{ $budget->project->name }}
                                                @endif
                                            </td>
                                            <td>{{ $budget->fiscal_year }}</td>
                                            <td>{{ number_format($budget->total_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $budget->status === 'approved' ? 'success' : ($budget->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($budget->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewBudgetModal{{ $budget->id }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                        @if ($budget->status === 'draft')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="submitBudget({{ $budget->id }})">
                                                                <i class="ri-send-plane-line me-1"></i> Submit
                                                            </a>
                                                        @endif
                                                        @if (auth()->user()->can('approve_budgets') && $budget->status === 'submitted')
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="approveBudget({{ $budget->id }})">
                                                                <i class="ri-checkbox-circle-line me-1"></i> Approve
                                                            </a>
                                                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                                onclick="rejectBudget({{ $budget->id }})">
                                                                <i class="ri-close-circle-line me-1"></i> Reject
                                                            </a>
                                                        @endif
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addKpiModal{{ $budget->id }}">
                                                            <i class="ri-line-chart-line me-1"></i> Add KPIs
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $budgets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($budgets as $budget)
        <!-- View Budget Modal -->
        <div class="modal fade" id="viewBudgetModal{{ $budget->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Budget Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Name</h6>
                                <p>{{ $budget->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Type</h6>
                                <p>{{ ucfirst($budget->type) }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>{{ $budget->type === 'department' ? 'Department' : 'Project' }}</h6>
                                <p>
                                    @if ($budget->type === 'department')
                                        {{ $budget->department->name }}
                                    @else
                                        {{ $budget->project->name }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Fiscal Year</h6>
                                <p>{{ $budget->fiscal_year }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Start Date</h6>
                                <p>{{ $budget->start_date->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>End Date</h6>
                                <p>{{ $budget->end_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>Categories</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($budget->categories as $category)
                                            <tr>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ number_format($category->amount, 2) }}</td>
                                                <td>{{ number_format(($category->amount / $budget->total_amount) * 100, 1) }}%
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th>{{ number_format($budget->total_amount, 2) }}</th>
                                            <th>100%</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @if ($budget->kpis->isNotEmpty())
                            <div class="mb-3">
                                <h6>KPIs</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Target</th>
                                                <th>Actual</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($budget->kpis as $kpi)
                                                <tr>
                                                    <td>{{ $kpi->name }}</td>
                                                    <td>{{ $kpi->target }} {{ $kpi->unit }}</td>
                                                    <td>{{ $kpi->actual ?? 'N/A' }} {{ $kpi->unit }}</td>
                                                    <td>
                                                        @if ($kpi->status)
                                                            <span
                                                                class="badge bg-{{ $kpi->status === 'on_track' ? 'success' : ($kpi->status === 'at_risk' ? 'warning' : 'danger') }}">
                                                                {{ str_replace('_', ' ', ucfirst($kpi->status)) }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Not Started</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        @if ($budget->notes)
                            <div class="mb-3">
                                <h6>Notes</h6>
                                <p>{{ $budget->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Add KPI Modal -->
        <div class="modal fade" id="addKpiModal{{ $budget->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add KPIs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('budgets.kpis.store', $budget) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div id="kpiFields{{ $budget->id }}">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="kpis[0][name]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Target</label>
                                        <input type="number" class="form-control" name="kpis[0][target]" step="0.01"
                                            required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Unit</label>
                                        <input type="text" class="form-control" name="kpis[0][unit]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Frequency</label>
                                        <select class="form-select" name="kpis[0][frequency]" required>
                                            <option value="monthly">Monthly</option>
                                            <option value="quarterly">Quarterly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" onclick="addKpiField({{ $budget->id }})">
                                Add Another KPI
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save KPIs</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Budget Modal -->
        <div class="modal fade" id="rejectBudgetModal{{ $budget->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Budget</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('budgets.update-status', $budget) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Rejection Reason</label>
                                <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('page-script')
    <script>
        let kpiCounts = {};

        function addKpiField(budgetId) {
            if (!kpiCounts[budgetId]) {
                kpiCounts[budgetId] = 1;
            } else {
                kpiCounts[budgetId]++;
            }

            const template = `
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="kpis[${kpiCounts[budgetId]}][name]" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Target</label>
                <input type="number" class="form-control" name="kpis[${kpiCounts[budgetId]}][target]" step="0.01" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Unit</label>
                <input type="text" class="form-control" name="kpis[${kpiCounts[budgetId]}][unit]" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Frequency</label>
                <select class="form-select" name="kpis[${kpiCounts[budgetId]}][frequency]" required>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>
    `;
            document.getElementById(`kpiFields${budgetId}`).insertAdjacentHTML('beforeend', template);
        }

        function submitBudget(id) {
            if (confirm('Are you sure you want to submit this budget?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/budgets/${id}/status`;
                form.innerHTML = `
            @csrf
            <input type="hidden" name="status" value="submitted">
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function approveBudget(id) {
            if (confirm('Are you sure you want to approve this budget?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/budgets/${id}/status`;
                form.innerHTML = `
            @csrf
            <input type="hidden" name="status" value="approved">
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function rejectBudget(id) {
            const modal = new bootstrap.Modal(document.getElementById(`rejectBudgetModal${id}`));
            modal.show();
        }
    </script>
@endsection

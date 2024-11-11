@extends('layouts/contentNavbarLayout')

@section('title', 'Budget Scenarios')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Budget Scenarios</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createScenarioModal">
                            Create Scenario
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Budget</th>
                                        <th>Total Adjustments</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scenarios as $scenario)
                                        <tr>
                                            <td>{{ $scenario->name }}</td>
                                            <td>{{ $scenario->budget->name }}</td>
                                            <td>{{ number_format($scenario->adjustments->sum('amount'), 2) }}</td>
                                            <td>{{ $scenario->creator->name }}</td>
                                            <td>{{ $scenario->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewScenarioModal{{ $scenario->id }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="applyScenario({{ $scenario->id }})">
                                                            <i class="ri-check-line me-1"></i> Apply Scenario
                                                        </a>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                            onclick="deleteScenario({{ $scenario->id }})">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $scenarios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Scenario Modal -->
    <div class="modal fade" id="createScenarioModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Budget Scenario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('budgets.scenarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Budget</label>
                                <select class="form-select" name="budget_id" required
                                    onchange="loadBudgetCategories(this.value)">
                                    <option value="">Select Budget</option>
                                    @foreach ($budgets as $budget)
                                        <option value="{{ $budget->id }}">{{ $budget->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adjustments</label>
                            <div id="categoryAdjustments">
                                <!-- Categories will be loaded dynamically -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Scenario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($scenarios as $scenario)
        <!-- View Scenario Modal -->
        <div class="modal fade" id="viewScenarioModal{{ $scenario->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Scenario Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Name</h6>
                                <p>{{ $scenario->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Budget</h6>
                                <p>{{ $scenario->budget->name }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>Description</h6>
                            <p>{{ $scenario->description }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Adjustments</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Original Amount</th>
                                            <th>Adjustment</th>
                                            <th>New Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($scenario->adjustments as $adjustment)
                                            <tr>
                                                <td>{{ $adjustment->category->name }}</td>
                                                <td>{{ number_format($adjustment->category->amount, 2) }}</td>
                                                <td>{{ number_format($adjustment->amount, 2) }}</td>
                                                <td>{{ number_format($adjustment->category->amount + $adjustment->amount, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th>{{ number_format($scenario->budget->total_amount, 2) }}</th>
                                            <th>{{ number_format($scenario->adjustments->sum('amount'), 2) }}</th>
                                            <th>{{ number_format($scenario->budget->total_amount + $scenario->adjustments->sum('amount'), 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('page-script')
    <script>
        function loadBudgetCategories(budgetId) {
            if (!budgetId) {
                document.getElementById('categoryAdjustments').innerHTML = '';
                return;
            }

            fetch(`/budgets/${budgetId}/categories`)
                .then(response => response.json())
                .then(categories => {
                    let html = '';
                    categories.forEach((category, index) => {
                        html += `
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control" value="${category.name}" readonly>
                            <input type="hidden" name="adjustments[${index}][category_id]" value="${category.id}">
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" name="adjustments[${index}][amount]"
                                placeholder="Adjustment Amount" step="0.01" required>
                        </div>
                    </div>
                `;
                    });
                    document.getElementById('categoryAdjustments').innerHTML = html;
                });
        }

        function applyScenario(id) {
            if (confirm('Are you sure you want to apply this scenario? This will update the budget amounts.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/budgets/scenarios/${id}/apply`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function deleteScenario(id) {
            if (confirm('Are you sure you want to delete this scenario?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/budgets/scenarios/${id}`;
                form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Budget Scenarios')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Budget Scenarios</h4>
                                <p class="mb-0">Create and analyze different budget scenarios</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createScenarioModal">
                                    <i class="ri-add-line"></i> Create Scenario
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scenario Comparison -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-4">
                                <h5 class="card-title mb-0">Scenario Comparison</h5>
                            </div>
                            <div class="col-sm-8">
                                <div class="row justify-content-end">
                                    <div class="col-md-4">
                                        <select class="form-select" id="scenario1">
                                            <option value="">Select Scenario 1</option>
                                            <option value="base">Base Scenario</option>
                                            <option value="optimistic">Optimistic</option>
                                            <option value="pessimistic">Pessimistic</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select" id="scenario2">
                                            <option value="">Select Scenario 2</option>
                                            <option value="base">Base Scenario</option>
                                            <option value="optimistic">Optimistic</option>
                                            <option value="pessimistic">Pessimistic</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="scenarioComparisonChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scenarios List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Budget Scenarios</h5>
                            <button type="button" class="btn btn-primary btn-sm">
                                <i class="ri-download-line"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Scenario Name</th>
                                    <th>Type</th>
                                    <th>Total Budget</th>
                                    <th>Variance</th>
                                    <th>Created By</th>
                                    <th>Last Updated</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scenarios ?? [] as $scenario)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($scenario->name ?? 'B', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('budgets.scenarios.show', $scenario ?? 1) }}">
                                                    {{ $scenario->name ?? 'Base Scenario' }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $scenario->type ?? 'Base' }}</td>
                                        <td>${{ number_format($scenario->total_budget ?? 100000) }}</td>
                                        <td>
                                            <span class="badge bg-label-success">+5%</span>
                                        </td>
                                        <td>{{ $scenario->creator->name ?? 'John Doe' }}</td>
                                        <td>{{ $scenario->updated_at ?? '2024-01-15' }}</td>
                                        <td>
                                            <span class="badge bg-label-primary">Active</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('budgets.scenarios.show', $scenario ?? 1) }}">
                                                            <i class="ri-eye-line me-1"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('budgets.scenarios.edit', $scenario ?? 1) }}">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-file-copy-line me-1"></i> Duplicate
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('budgets.scenarios.destroy', $scenario ?? 1) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ri-delete-bin-line me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="p-3">
                                                <i class="ri-file-list-3-line ri-3x text-primary mb-3"></i>
                                                <h5>No Scenarios Found</h5>
                                                <p class="mb-3">Start by creating your first budget scenario</p>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#createScenarioModal">
                                                    <i class="ri-add-line"></i> Create Scenario
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Scenario Modal -->
    <div class="modal fade" id="createScenarioModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Budget Scenario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('budgets.scenarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Scenario Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Base Budget</label>
                                <select class="form-select" name="base_budget_id" required>
                                    <option value="">Select Base Budget</option>
                                    <!-- Budgets will be populated here -->
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Scenario Type</label>
                                <select class="form-select" name="type" required>
                                    <option value="optimistic">Optimistic</option>
                                    <option value="pessimistic">Pessimistic</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Adjustment Method</label>
                                <select class="form-select" name="adjustment_method" required>
                                    <option value="percentage">Percentage Adjustment</option>
                                    <option value="fixed">Fixed Amount Adjustment</option>
                                    <option value="custom">Custom Adjustments</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Adjustment Value</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="adjustment_value" step="0.01">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Time Period</label>
                                <select class="form-select" name="time_period" required>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="apply_to_all_categories"
                                        id="applyToAllCategories">
                                    <label class="form-check-label" for="applyToAllCategories">
                                        Apply adjustment to all budget categories
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 category-adjustments">
                                <label class="form-label">Category Adjustments</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Current Budget</th>
                                                <th>Adjustment</th>
                                                <th>New Budget</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Categories will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Scenario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scenario Comparison Chart
            const scenarioComparisonOptions = {
                series: [{
                    name: 'Base Scenario',
                    data: [10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000,
                        10000
                    ]
                }, {
                    name: 'Optimistic Scenario',
                    data: [11000, 11000, 11000, 11000, 11000, 11000, 11000, 11000, 11000, 11000, 11000,
                        11000
                    ]
                }],
                chart: {
                    type: 'line',
                    height: 400,
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: [2, 2],
                    dashArray: [0, 0]
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ]
                },
                yaxis: {
                    title: {
                        text: 'Amount ($)'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return '$ ' + val;
                        }
                    }
                },
                colors: ['#696cff', '#03c3ec']
            };

            const scenarioComparisonChart = new ApexCharts(
                document.querySelector("#scenarioComparisonChart"),
                scenarioComparisonOptions
            );
            scenarioComparisonChart.render();

            // Handle category adjustments visibility
            const applyToAllCategories = document.getElementById('applyToAllCategories');
            const categoryAdjustments = document.querySelector('.category-adjustments');

            if (applyToAllCategories && categoryAdjustments) {
                applyToAllCategories.addEventListener('change', function() {
                    categoryAdjustments.style.display = this.checked ? 'none' : 'block';
                });
            }

            // Handle scenario comparison
            const scenario1Select = document.getElementById('scenario1');
            const scenario2Select = document.getElementById('scenario2');

            function updateComparisonChart() {
                // Update chart data based on selected scenarios
                // This would typically involve an API call to get the scenario data
            }

            if (scenario1Select && scenario2Select) {
                scenario1Select.addEventListener('change', updateComparisonChart);
                scenario2Select.addEventListener('change', updateComparisonChart);
            }
        });
    </script>
@endsection

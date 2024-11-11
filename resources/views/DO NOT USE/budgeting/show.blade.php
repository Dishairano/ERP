@extends('layouts/contentNavbarLayout')

@section('title', 'Budget Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">{{ $budget->name ?? 'Annual Budget 2024' }}</h4>
                                <p class="mb-0">{{ $budget->description ?? 'Budget details and tracking information' }}</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <a href="{{ route('budgets.edit', $budget ?? 1) }}" class="btn btn-primary">
                                        <i class="ri-pencil-line"></i> Edit Budget
                                    </a>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="window.print()"><i
                                                    class="ri-printer-line me-1"></i> Print</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-download-line me-1"></i>
                                                Export</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('budgets.destroy', $budget ?? 1) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="ri-delete-bin-line me-1"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Overview -->
        <div class="row">
            <!-- Budget Status -->
            <div class="col-12 col-xl-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Budget Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="mb-0">Total Budget</h6>
                                <h4 class="mb-0">${{ number_format($budget->total_amount ?? 100000) }}</h4>
                            </div>
                            <span class="badge bg-label-success">Active</span>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0">Budget Utilization</h6>
                                <small>{{ $budget->progress ?? 45 }}%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $budget->progress ?? 45 }}%">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <h6 class="mb-0">Spent</h6>
                                <h5 class="mb-0 text-danger">${{ number_format($budget->spent_amount ?? 45000) }}</h5>
                            </div>
                            <div>
                                <h6 class="mb-0">Remaining</h6>
                                <h5 class="mb-0 text-success">${{ number_format($budget->remaining_amount ?? 55000) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Budget Details -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Budget Details</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Type</dt>
                            <dd class="col-sm-8">{{ $budget->type ?? 'Department' }}</dd>

                            <dt class="col-sm-4">Period</dt>
                            <dd class="col-sm-8">{{ $budget->period ?? 'Jan 2024 - Dec 2024' }}</dd>

                            <dt class="col-sm-4">Department</dt>
                            <dd class="col-sm-8">{{ $budget->department->name ?? 'IT Department' }}</dd>

                            <dt class="col-sm-4">Created By</dt>
                            <dd class="col-sm-8">{{ $budget->creator->name ?? 'John Doe' }}</dd>

                            <dt class="col-sm-4">Created At</dt>
                            <dd class="col-sm-8">{{ $budget->created_at ?? '2024-01-01' }}</dd>

                            <dt class="col-sm-4">Last Updated</dt>
                            <dd class="col-sm-8">{{ $budget->updated_at ?? '2024-01-15' }}</dd>

                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8"><span class="badge bg-label-success">Active</span></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Monthly Breakdown -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Monthly Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div id="monthlyBreakdownChart" style="min-height: 400px;"></div>
                    </div>
                </div>

                <!-- Category Distribution -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Category Distribution</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addCategoryModal">
                            <i class="ri-add-line"></i> Add Category
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Allocated</th>
                                    <th>Spent</th>
                                    <th>Remaining</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($budget->categories ?? [] as $category)
                                    <tr>
                                        <td>{{ $category->name ?? 'Personnel' }}</td>
                                        <td>${{ number_format($category->allocated_amount ?? 50000) }}</td>
                                        <td>${{ number_format($category->spent_amount ?? 25000) }}</td>
                                        <td>${{ number_format($category->remaining_amount ?? 25000) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress w-100 me-2" style="height: 8px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $category->progress ?? 50 }}%"></div>
                                                </div>
                                                <span>{{ $category->progress ?? 50 }}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No categories found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Transactions</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addTransactionModal">
                            <i class="ri-add-line"></i> Add Transaction
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($budget->transactions ?? [] as $transaction)
                                    <tr>
                                        <td>{{ $transaction->date ?? '2024-01-15' }}</td>
                                        <td>{{ $transaction->description ?? 'Software License Renewal' }}</td>
                                        <td>{{ $transaction->category ?? 'Software' }}</td>
                                        <td>${{ number_format($transaction->amount ?? 1000) }}</td>
                                        <td>
                                            <span class="badge bg-label-danger">Expense</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-success">Approved</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            data-bs-toggle="modal" data-bs-target="#editTransactionModal">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No transactions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('budgets.categories.store', $budget ?? 1) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Allocated Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="amount" step="0.01" min="0"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Transaction Modal -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('budgets.transactions.store', $budget ?? 1) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Select Category</option>
                                <!-- Categories will be populated here -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="amount" step="0.01" min="0"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type" required>
                                <option value="expense">Expense</option>
                                <option value="income">Income</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Breakdown Chart
            const monthlyBreakdownOptions = {
                series: [{
                    name: 'Budget',
                    data: [10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000,
                        10000
                    ]
                }, {
                    name: 'Actual',
                    data: [8500, 9200, 9800, 8900, 9000, 8700, 0, 0, 0, 0, 0, 0]
                }],
                chart: {
                    type: 'bar',
                    height: 400,
                    stacked: false,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
                },
                yaxis: {
                    title: {
                        text: 'Amount ($)'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "$ " + val
                        }
                    }
                },
                colors: ['#696cff', '#03c3ec']
            };

            const monthlyBreakdownChart = new ApexCharts(
                document.querySelector("#monthlyBreakdownChart"),
                monthlyBreakdownOptions
            );
            monthlyBreakdownChart.render();
        });
    </script>
@endsection

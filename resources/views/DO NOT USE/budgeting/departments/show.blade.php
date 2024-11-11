@extends('layouts/contentNavbarLayout')

@section('title', 'Department Budget Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar avatar-md me-2">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(substr($department->name ?? 'IT', 0, 1)) }}
                                        </span>
                                    </div>
                                    <h4 class="card-title text-primary mb-0">{{ $department->name ?? 'IT Department' }}</h4>
                                </div>
                                <p class="mb-0">Budget management and tracking for
                                    {{ $department->name ?? 'IT Department' }}</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                                        <i class="ri-add-line"></i> Add Budget
                                    </a>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('budgets.departments') }}"><i
                                                    class="ri-arrow-left-line me-1"></i> Back to List</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-download-line me-1"></i>
                                                Export Report</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Summary -->
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
                                <h4 class="mb-0">${{ number_format($department->budget ?? 50000) }}</h4>
                            </div>
                            <span class="badge bg-label-success">Active</span>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0">Budget Utilization</h6>
                                <small>{{ $department->progress ?? 50 }}%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $department->progress ?? 50 }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <h6 class="mb-0">Spent</h6>
                                <h5 class="mb-0 text-danger">${{ number_format($department->spent ?? 25000) }}</h5>
                            </div>
                            <div>
                                <h6 class="mb-0">Remaining</h6>
                                <h5 class="mb-0 text-success">${{ number_format($department->remaining ?? 25000) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Department Details -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Department Details</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Manager</dt>
                            <dd class="col-sm-8">{{ $department->manager->name ?? 'John Doe' }}</dd>

                            <dt class="col-sm-4">Employees</dt>
                            <dd class="col-sm-8">{{ $department->employee_count ?? 10 }}</dd>

                            <dt class="col-sm-4">Location</dt>
                            <dd class="col-sm-8">{{ $department->location ?? 'New York' }}</dd>

                            <dt class="col-sm-4">Cost Center</dt>
                            <dd class="col-sm-8">{{ $department->cost_center ?? 'CC-001' }}</dd>

                            <dt class="col-sm-4">Created At</dt>
                            <dd class="col-sm-8">{{ $department->created_at ?? '2024-01-01' }}</dd>

                            <dt class="col-sm-4">Last Updated</dt>
                            <dd class="col-sm-8">{{ $department->updated_at ?? '2024-01-15' }}</dd>

                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8"><span class="badge bg-label-success">Active</span></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Budget Trends -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Budget Trends</h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                2024
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">2024</a></li>
                                <li><a class="dropdown-item" href="#">2023</a></li>
                                <li><a class="dropdown-item" href="#">2022</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="budgetTrendsChart" style="min-height: 400px;"></div>
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
                                    <th>Budget</th>
                                    <th>Spent</th>
                                    <th>Remaining</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($department->categories ?? [] as $category)
                                    <tr>
                                        <td>{{ $category->name ?? 'Personnel' }}</td>
                                        <td>${{ number_format($category->budget ?? 30000) }}</td>
                                        <td>${{ number_format($category->spent ?? 15000) }}</td>
                                        <td>${{ number_format($category->remaining ?? 15000) }}</td>
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
                                @forelse($department->transactions ?? [] as $transaction)
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
                <form action="{{ route('budgets.departments.categories.store', $department ?? 1) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Budget Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="budget" step="0.01" min="0"
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
                <form action="{{ route('budgets.departments.transactions.store', $department ?? 1) }}" method="POST">
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
            // Budget Trends Chart
            const budgetTrendsOptions = {
                series: [{
                    name: 'Budget',
                    data: [5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000]
                }, {
                    name: 'Actual',
                    data: [4200, 4500, 4800, 4300, 4600, 4100, 0, 0, 0, 0, 0, 0]
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

            const budgetTrendsChart = new ApexCharts(
                document.querySelector("#budgetTrendsChart"),
                budgetTrendsOptions
            );
            budgetTrendsChart.render();
        });
    </script>
@endsection

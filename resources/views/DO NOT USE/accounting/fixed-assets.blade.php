@extends('layouts/contentNavbarLayout')

@section('title', 'Fixed Assets')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center row">
                            <div class="col-sm-7">
                                <h4 class="card-title text-primary">Fixed Assets</h4>
                                <p class="mb-0">Manage company assets, depreciation, and maintenance</p>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createAssetModal">
                                        <i class="ri-add-line"></i> New Asset
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#recordDepreciationModal"><i
                                                    class="ri-percent-line me-1"></i> Record Depreciation</a></li>
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#scheduledMaintenanceModal"><i
                                                    class="ri-tools-line me-1"></i> Schedule Maintenance</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-download-line me-1"></i>
                                                Export</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ri-printer-line me-1"></i>
                                                Print</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Assets</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$500,000</h4>
                                    <small class="text-success">(+5%)</small>
                                </div>
                                <small>Book value</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ri-building-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Accumulated Depreciation</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$100,000</h4>
                                    <small class="text-danger">(+10%)</small>
                                </div>
                                <small>Total depreciation</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="ri-percent-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Net Book Value</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">$400,000</h4>
                                    <small class="text-success">(+3%)</small>
                                </div>
                                <small>Current value</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Maintenance Due</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">5</h4>
                                    <small class="text-warning">Assets</small>
                                </div>
                                <small>Next 30 days</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ri-tools-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label">Category</label>
                                <select class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="buildings">Buildings</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="vehicles">Vehicles</option>
                                    <option value="furniture">Furniture</option>
                                    <option value="it">IT Assets</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="disposed">Disposed</option>
                                    <option value="maintenance">In Maintenance</option>
                                    <option value="retired">Retired</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label">Location</label>
                                <select class="form-select">
                                    <option value="">All Locations</option>
                                    <!-- Locations will be populated here -->
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" placeholder="Search assets...">
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-3-line"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assets Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Fixed Assets</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Asset ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Purchase Date</th>
                                    <th>Cost</th>
                                    <th>Depreciation</th>
                                    <th>Book Value</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assets ?? [] as $asset)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#viewAssetModal">
                                                {{ $asset->id ?? 'AST-001' }}
                                            </a>
                                        </td>
                                        <td>{{ $asset->name ?? 'Office Building' }}</td>
                                        <td>{{ $asset->category ?? 'Buildings' }}</td>
                                        <td>{{ $asset->purchase_date ?? '2024-01-01' }}</td>
                                        <td>${{ number_format($asset->cost ?? 100000, 2) }}</td>
                                        <td>${{ number_format($asset->depreciation ?? 20000, 2) }}</td>
                                        <td>${{ number_format($asset->book_value ?? 80000, 2) }}</td>
                                        <td>
                                            <span class="badge bg-label-success">Active</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#recordDepreciationModal">
                                                            <i class="ri-percent-line me-1"></i> Record Depreciation
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#scheduledMaintenanceModal">
                                                            <i class="ri-tools-line me-1"></i> Schedule Maintenance
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            data-bs-toggle="modal" data-bs-target="#editAssetModal">
                                                            <i class="ri-pencil-line me-1"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-line me-1"></i> Dispose
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <div class="p-3">
                                                <i class="ri-building-line ri-3x text-primary mb-3"></i>
                                                <h5>No Assets Found</h5>
                                                <p class="mb-3">Start by adding your first asset</p>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#createAssetModal">
                                                    <i class="ri-add-line"></i> Add Asset
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <select class="form-select form-select-sm" style="width: 80px">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Asset Modal -->
    <div class="modal fade" id="createAssetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Asset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounting.fixed-assets.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Asset Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="buildings">Buildings</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="vehicles">Vehicles</option>
                                    <option value="furniture">Furniture</option>
                                    <option value="it">IT Assets</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" name="purchase_date" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Purchase Cost</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="cost" step="0.01"
                                        min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <select class="form-select" name="location_id" required>
                                    <option value="">Select Location</option>
                                    <!-- Locations will be populated here -->
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <select class="form-select" name="department_id" required>
                                    <option value="">Select Department</option>
                                    <!-- Departments will be populated here -->
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Useful Life (Years)</label>
                                <input type="number" class="form-control" name="useful_life" min="1" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Depreciation Method</label>
                                <select class="form-select" name="depreciation_method" required>
                                    <option value="straight_line">Straight Line</option>
                                    <option value="declining_balance">Declining Balance</option>
                                    <option value="sum_of_years">Sum of Years</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Attachments</label>
                                <input type="file" class="form-control" name="attachments[]" multiple>
                                <small class="text-muted">Upload purchase documents, warranties, etc.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Maintenance Schedule</label>
                                <select class="form-select" name="maintenance_frequency">
                                    <option value="">No Regular Maintenance</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="semi_annual">Semi-Annual</option>
                                    <option value="annual">Annual</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Asset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Record Depreciation Modal -->
    <div class="modal fade" id="recordDepreciationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Depreciation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounting.fixed-assets.depreciation.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Asset</label>
                                <input type="text" class="form-control" value="Office Building" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="amount" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Method</label>
                                <select class="form-select" name="method" required>
                                    <option value="straight_line">Straight Line</option>
                                    <option value="declining_balance">Declining Balance</option>
                                    <option value="sum_of_years">Sum of Years</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Record Depreciation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Schedule Maintenance Modal -->
    <div class="modal fade" id="scheduledMaintenanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Maintenance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounting.fixed-assets.maintenance.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Asset</label>
                                <input type="text" class="form-control" value="Office Building" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Scheduled Date</label>
                                <input type="date" class="form-control" name="scheduled_date" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <select class="form-select" name="maintenance_type" required>
                                    <option value="routine">Routine</option>
                                    <option value="repair">Repair</option>
                                    <option value="inspection">Inspection</option>
                                    <option value="upgrade">Upgrade</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Assigned To</label>
                                <select class="form-select" name="assigned_to">
                                    <option value="">Select Staff</option>
                                    <!-- Staff will be populated here -->
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Estimated Cost</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="estimated_cost" step="0.01"
                                        min="0">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Schedule Maintenance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle select all checkbox
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    document.querySelectorAll('tbody .form-check-input').forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }
        });
    </script>
@endsection

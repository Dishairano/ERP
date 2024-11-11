@extends('layouts/contentNavbarLayout')

@section('title', 'Production Planning')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Production /</span> Planning
        </h4>

        <div class="row">
            <!-- Production Schedule -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Production Schedule</h5>
                        <div>
                            <button type="button" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i> New Plan
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Plan ID</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Work Center</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productionPlans as $plan)
                                        <tr>
                                            <td>{{ $plan->id }}</td>
                                            <td>{{ $plan->product_name }}</td>
                                            <td>{{ $plan->quantity }}</td>
                                            <td>{{ $plan->work_center }}</td>
                                            <td>{{ $plan->start_date }}</td>
                                            <td>{{ $plan->end_date }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $plan->status === 'Approved'
                                                        ? 'success'
                                                        : ($plan->status === 'Pending'
                                                            ? 'warning'
                                                            : ($plan->status === 'In Review'
                                                                ? 'info'
                                                                : 'danger')) }}">
                                                    {{ $plan->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-eye-line me-2"></i> View Details
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-pencil-line me-2"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-check-line me-2"></i> Approve
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">
                                                            <i class="ri-close-line me-2"></i> Reject
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $productionPlans->links() }}
                    </div>
                </div>
            </div>

            <!-- Resource Allocation -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Resource Allocation</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Resource</th>
                                        <th>Type</th>
                                        <th>Allocated Hours</th>
                                        <th>Available Hours</th>
                                        <th>Utilization</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resources as $resource)
                                        <tr>
                                            <td>{{ $resource->name }}</td>
                                            <td>{{ $resource->type }}</td>
                                            <td>{{ $resource->allocated_hours }}</td>
                                            <td>{{ $resource->available_hours }}</td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $resource->utilization }}%"
                                                        aria-valuenow="{{ $resource->utilization }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $resource->utilization }}%</small>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary">
                                                    Adjust
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

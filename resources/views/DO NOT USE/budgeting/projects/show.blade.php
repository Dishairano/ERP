@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $project->name }} - Budget Details</h3>
                        <div class="card-tools">
                            <a href="{{ route('budgets.create', ['project_id' => $project->id]) }}" class="btn btn-primary">
                                Add Budget
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-box-content">
                                        <h5>Project Information</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Project Code</th>
                                                <td>{{ $project->code }}</td>
                                            </tr>
                                            <tr>
                                                <th>Start Date</th>
                                                <td>{{ $project->start_date->format('Y-m-d') }}</td>
                                            </tr>
                                            <tr>
                                                <th>End Date</th>
                                                <td>{{ $project->end_date->format('Y-m-d') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>{{ ucfirst($project->status) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-box-content">
                                        <h5>Budget Summary</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Total Planned</th>
                                                <td>{{ number_format($budgets->sum('planned_amount'), 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Actual</th>
                                                <td>{{ number_format($budgets->sum('actual_amount'), 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Variance</th>
                                                <td>{{ number_format($budgets->sum('variance_amount'), 2) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Cost Category</th>
                                        <th>Revenue Stream</th>
                                        <th>Planned Amount</th>
                                        <th>Actual Amount</th>
                                        <th>Variance</th>
                                        <th>Progress</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($budgets as $budget)
                                        <tr>
                                            <td>{{ $budget->category_name }}</td>
                                            <td>{{ $budget->costCategory?->name ?? 'N/A' }}</td>
                                            <td>{{ $budget->revenueStream?->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($budget->planned_amount, 2) }} {{ $budget->currency }}
                                            </td>
                                            <td>{{ number_format($budget->actual_amount, 2) }} {{ $budget->currency }}
                                            </td>
                                            <td>{{ number_format($budget->variance_amount, 2) }} {{ $budget->currency }}
                                            </td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar {{ $budget->spentPercentage > 80 ? 'bg-danger' : 'bg-success' }}"
                                                        role="progressbar" style="width: {{ $budget->spentPercentage }}%">
                                                        {{ number_format($budget->spentPercentage, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('budgets.show', $budget) }}"
                                                    class="btn btn-sm btn-info">View</a>
                                                <a href="{{ route('budgets.edit', $budget) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
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

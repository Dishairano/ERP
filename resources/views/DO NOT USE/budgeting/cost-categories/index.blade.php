@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cost Categories</h3>
                    </div>
                    <div class="card-body">
                        @foreach ($categories as $category)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>{{ $category->name }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Budget</th>
                                                    <th>Department</th>
                                                    <th>Project</th>
                                                    <th>Planned Amount</th>
                                                    <th>Actual Amount</th>
                                                    <th>Progress</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($category->budgets as $budget)
                                                    <tr>
                                                        <td>{{ $budget->category_name }}</td>
                                                        <td>{{ $budget->department?->name ?? 'N/A' }}</td>
                                                        <td>{{ $budget->project?->name ?? 'N/A' }}</td>
                                                        <td>{{ number_format($budget->planned_amount, 2) }}
                                                            {{ $budget->currency }}</td>
                                                        <td>{{ number_format($budget->actual_amount, 2) }}
                                                            {{ $budget->currency }}</td>
                                                        <td>
                                                            <div class="progress">
                                                                <div class="progress-bar {{ $budget->spentPercentage > 80 ? 'bg-danger' : 'bg-success' }}"
                                                                    role="progressbar"
                                                                    style="width: {{ $budget->spentPercentage }}%">
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
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3"><strong>Total</strong></td>
                                                    <td><strong>{{ number_format($category->budgets->sum('planned_amount'), 2) }}</strong>
                                                    </td>
                                                    <td><strong>{{ number_format($category->budgets->sum('actual_amount'), 2) }}</strong>
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

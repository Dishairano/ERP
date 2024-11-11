@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgets')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            @foreach ($projects as $project)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ $project->name }}</h5>
                        </div>
                        <div class="card-body">
                            @if ($project->budgets->isNotEmpty())
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Current Budget</h6>
                                    <span class="badge bg-primary">
                                        FY {{ $project->budgets->first()->fiscal_year }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Amount</span>
                                        <span class="fw-semibold">
                                            {{ number_format($project->budgets->first()->total_amount, 2) }}
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        @foreach ($project->budgets->first()->categories as $category)
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{ ($category->amount / $project->budgets->first()->total_amount) * 100 }}%">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6>Categories</h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($project->budgets->first()->categories as $category)
                                            <li class="mb-1">
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ $category->name }}</span>
                                                    <span>{{ number_format($category->amount, 2) }}</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @if ($project->budgets->first()->kpis->isNotEmpty())
                                    <div class="mb-3">
                                        <h6>KPIs</h6>
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($project->budgets->first()->kpis as $kpi)
                                                <li class="mb-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>{{ $kpi->name }}</span>
                                                        <span
                                                            class="badge bg-{{ $kpi->status === 'on_track' ? 'success' : ($kpi->status === 'at_risk' ? 'warning' : 'danger') }}">
                                                            {{ $kpi->actual ?? 'N/A' }}/{{ $kpi->target }}
                                                            {{ $kpi->unit }}
                                                        </span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#viewBudgetHistoryModal{{ $project->id }}">
                                        View History
                                    </button>
                                </div>
                            @else
                                <div class="text-center">
                                    <p class="mb-3">No budget created yet.</p>
                                    <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                                        Create Budget
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @foreach ($projects as $project)
        @if ($project->budgets->isNotEmpty())
            <!-- Budget History Modal -->
            <div class="modal fade" id="viewBudgetHistoryModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Budget History - {{ $project->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Fiscal Year</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($project->budgets as $budget)
                                            <tr>
                                                <td>{{ $budget->fiscal_year }}</td>
                                                <td>{{ number_format($budget->total_amount, 2) }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $budget->status === 'approved' ? 'success' : ($budget->status === 'rejected' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($budget->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $budget->creator->name }}</td>
                                                <td>{{ $budget->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endsection

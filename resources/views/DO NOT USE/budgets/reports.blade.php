@extends('layouts/contentNavbarLayout')

@section('title', 'Budget Reports')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Budget Reports</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('budgets.reports') }}" method="GET" class="mb-4">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Fiscal Year</label>
                                    <select class="form-select" name="fiscal_year">
                                        <option value="">All Years</option>
                                        @foreach (range(date('Y') - 5, date('Y') + 1) as $year)
                                            <option value="{{ $year }}"
                                                {{ request('fiscal_year') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" name="type">
                                        <option value="">All Types</option>
                                        <option value="department" {{ request('type') === 'department' ? 'selected' : '' }}>
                                            Department</option>
                                        <option value="project" {{ request('type') === 'project' ? 'selected' : '' }}>
                                            Project</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Department</label>
                                    <select class="form-select" name="department_id">
                                        <option value="">All Departments</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Project</label>
                                    <select class="form-select" name="project_id">
                                        <option value="">All Projects</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                                <button type="button" class="btn btn-secondary" onclick="exportReport()">Export</button>
                            </div>
                        </form>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Total Budget</h6>
                                        <h2>{{ number_format($budgets->sum('total_amount'), 2) }}</h2>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Average Budget</h6>
                                        <h2>{{ number_format($budgets->avg('total_amount'), 2) }}</h2>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Total Budgets</h6>
                                        <h2>{{ $budgets->count() }}</h2>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                        <th>KPIs</th>
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
                                                @if ($budget->kpis->isNotEmpty())
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewKpisModal{{ $budget->id }}">
                                                        View KPIs
                                                    </button>
                                                @else
                                                    <span class="badge bg-secondary">No KPIs</span>
                                                @endif
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

    @foreach ($budgets as $budget)
        @if ($budget->kpis->isNotEmpty())
            <!-- View KPIs Modal -->
            <div class="modal fade" id="viewKpisModal{{ $budget->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">KPIs - {{ $budget->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
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
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endsection

@section('page-script')
    <script>
        function exportReport() {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route('budgets.reports.export') }}';

            // Copy current filter values
            const params = new URLSearchParams(window.location.search);
            params.forEach((value, key) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
@endsection

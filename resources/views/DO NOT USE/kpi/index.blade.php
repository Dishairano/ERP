@extends('layouts/contentNavbarLayout')

@section('title', 'KPI Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Key Performance Indicators</h4>
                    @if (auth()->user()->can('manage-kpis'))
                        <a href="{{ route('kpi.create') }}" class="btn btn-primary">
                            <i data-feather="plus"></i> Create New KPI
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Current Value</th>
                                    <th>Target</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kpis as $kpi)
                                    @php
                                        $latestValue = $kpi->getLatestValue();
                                        $currentTarget = $kpi->getCurrentTarget();
                                    @endphp
                                    <tr>
                                        <td>{{ $kpi->name }}</td>
                                        <td>{{ $kpi->category }}</td>
                                        <td>
                                            @if ($latestValue)
                                                {{ number_format($latestValue->value, 2) }} {{ $kpi->unit }}
                                            @else
                                                No data
                                            @endif
                                        </td>
                                        <td>
                                            @if ($currentTarget)
                                                {{ number_format($currentTarget->target_value, 2) }} {{ $kpi->unit }}
                                            @else
                                                No target
                                            @endif
                                        </td>
                                        <td>
                                            @if ($latestValue && $currentTarget)
                                                @php
                                                    $progress =
                                                        ($latestValue->value / $currentTarget->target_value) * 100;
                                                    $status =
                                                        $progress >= 100
                                                            ? 'success'
                                                            : ($progress >= 70
                                                                ? 'warning'
                                                                : 'danger');
                                                @endphp
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar bg-{{ $status }}" role="progressbar"
                                                        style="width: {{ min($progress, 100) }}%">
                                                    </div>
                                                </div>
                                                <small>{{ number_format($progress, 1) }}%</small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($latestValue)
                                                {{ $latestValue->measurement_date->diffForHumans() }}
                                            @else
                                                Never
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('kpi.show', $kpi) }}">
                                                            <i data-feather="eye"></i> View Details
                                                        </a>
                                                    </li>
                                                    @if (auth()->user()->can('manage-kpis'))
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('kpi.edit', $kpi) }}">
                                                                <i data-feather="edit"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                                onclick="showAddValueModal({{ $kpi->id }})">
                                                                <i data-feather="plus-circle"></i> Add Value
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                                onclick="showAddTargetModal({{ $kpi->id }})">
                                                                <i data-feather="target"></i> Set Target
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $kpis->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('kpi.modals.add-value')
    @include('kpi.modals.add-target')

@endsection

@section('page-script')
    <script>
        function showAddValueModal(kpiId) {
            $('#add-value-form').attr('action', `/kpi/${kpiId}/value`);
            $('#add-value-modal').modal('show');
        }

        function showAddTargetModal(kpiId) {
            $('#add-target-form').attr('action', `/kpi/${kpiId}/target`);
            $('#add-target-modal').modal('show');
        }
    </script>
@endsection

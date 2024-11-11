@extends('layouts/contentNavbarLayout')

@section('title', 'Control Charts')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manufacturing Quality /</span> Control Charts
        </h4>

        <div class="row">
            <!-- Chart Selection -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Process</label>
                                <select class="form-select" id="process-select">
                                    <option value="">Select Process</option>
                                    @foreach ($processes as $process)
                                        <option value="{{ $process->id }}">{{ $process->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Chart Type</label>
                                <select class="form-select" id="chart-type">
                                    <option value="xbar">X-bar Chart</option>
                                    <option value="r">R Chart</option>
                                    <option value="s">S Chart</option>
                                    <option value="p">P Chart</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date Range</label>
                                <input type="text" class="form-control" id="date-range" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary d-block">
                                    Update Chart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Control Chart -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Control Chart</h5>
                        <div class="dropdown">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <i class="ri-download-line me-1"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="ri-file-pdf-line me-2"></i> PDF
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="ri-file-excel-line me-2"></i> Excel
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="ri-image-line me-2"></i> Image
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="control-chart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="col-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted">UCL (Upper Control Limit)</h6>
                                <h4 class="card-title mb-0">{{ number_format($statistics->ucl, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted">CL (Center Line)</h6>
                                <h4 class="card-title mb-0">{{ number_format($statistics->cl, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted">LCL (Lower Control Limit)</h6>
                                <h4 class="card-title mb-0">{{ number_format($statistics->lcl, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted">Cp (Process Capability)</h6>
                                <h4 class="card-title mb-0">{{ number_format($statistics->cp, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Out of Control Points -->
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Out of Control Points</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Point ID</th>
                                        <th>Date/Time</th>
                                        <th>Value</th>
                                        <th>Rule Violation</th>
                                        <th>Assignee</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($outOfControlPoints as $point)
                                        <tr>
                                            <td>{{ $point->id }}</td>
                                            <td>{{ $point->datetime }}</td>
                                            <td>{{ number_format($point->value, 2) }}</td>
                                            <td>{{ $point->rule_violation }}</td>
                                            <td>{{ $point->assignee }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $point->status === 'Resolved' ? 'success' : ($point->status === 'In Progress' ? 'warning' : 'danger') }}">
                                                    {{ $point->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary">
                                                    Investigate
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize date range picker
                $('#date-range').daterangepicker();

                // Initialize control chart
                const chart = new ApexCharts(document.querySelector("#control-chart"), {
                    series: [{
                        name: "Measurements",
                        data: @json($chartData->measurements)
                    }],
                    chart: {
                        height: 400,
                        type: 'line',
                        zoom: {
                            enabled: true
                        }
                    },
                    plotOptions: {
                        line: {
                            markers: {
                                size: 4
                            }
                        }
                    },
                    stroke: {
                        curve: 'straight'
                    },
                    grid: {
                        row: {
                            colors: ['#f3f3f3', 'transparent'],
                            opacity: 0.5
                        }
                    },
                    xaxis: {
                        categories: @json($chartData->categories)
                    },
                    yaxis: {
                        title: {
                            text: 'Value'
                        }
                    }
                });

                chart.render();
            });
        </script>
    @endpush
@endsection

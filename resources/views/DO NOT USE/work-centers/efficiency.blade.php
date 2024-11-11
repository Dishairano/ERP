@extends('layouts/contentNavbarLayout')

@section('title', 'Work Centers Efficiency Analysis')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Work Centers /</span> Efficiency Analysis
        </h4>

        <div class="row">
            <!-- Efficiency Metrics -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Efficiency Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Work Center</th>
                                        <th>OEE</th>
                                        <th>Availability</th>
                                        <th>Performance</th>
                                        <th>Quality</th>
                                        <th>Trend</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workCenters as $workCenter)
                                        @foreach ($workCenter->efficiencyRecords as $record)
                                            <tr>
                                                <td>{{ $workCenter->name }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress w-100 me-3" style="height: 8px;">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{ $record->oee }}%"
                                                                aria-valuenow="{{ $record->oee }}" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                        <span>{{ $record->oee }}%</span>
                                                    </div>
                                                </td>
                                                <td>{{ $record->availability }}%</td>
                                                <td>{{ $record->performance }}%</td>
                                                <td>{{ $record->quality }}%</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $record->trend === 'up' ? 'success' : 'danger' }}">
                                                        <i class="ri-arrow-{{ $record->trend }}-line"></i>
                                                        {{ $record->trend_value }}%
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
                                                                <i class="ri-bar-chart-line me-2"></i> Detailed Analysis
                                                            </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">
                                                                <i class="ri-history-line me-2"></i> Historical Data
                                                            </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">
                                                                <i class="ri-file-chart-line me-2"></i> Export Report
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $efficiencyRecords->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Risk Management /</span> Risk Reports
        </h4>

        <div class="row">
            <!-- Monthly Risk Trends -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Monthly Risk Trends</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#" onclick="exportChart('monthlyTrends')">Export
                                    Chart</a>
                                <a class="dropdown-item" href="#" onclick="exportData('monthlyTrends')">Export
                                    Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="monthlyTrendsChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Project Risk Distribution -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Project Risk Distribution</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#" onclick="exportChart('riskDistribution')">Export
                                    Chart</a>
                                <a class="dropdown-item" href="#" onclick="exportData('riskDistribution')">Export
                                    Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Total Risks</th>
                                        <th>High</th>
                                        <th>Medium</th>
                                        <th>Low</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projectRiskDistribution as $project)
                                        <tr>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $project->total_risks }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $project->high_risks }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $project->medium_risks }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $project->low_risks }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mitigation Effectiveness -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Mitigation Effectiveness</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"
                                    onclick="exportChart('mitigationEffectiveness')">Export Chart</a>
                                <a class="dropdown-item" href="#"
                                    onclick="exportData('mitigationEffectiveness')">Export Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Strategy</th>
                                        <th>Risks Mitigated</th>
                                        <th>Avg. Days to Mitigate</th>
                                        <th>Effectiveness</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mitigationEffectiveness as $strategy)
                                        <tr>
                                            <td>{{ $strategy->mitigation_strategy }}</td>
                                            <td>{{ $strategy->total_mitigated }}</td>
                                            <td>{{ round($strategy->avg_days_to_mitigate, 1) }}</td>
                                            <td>
                                                @php
                                                    $effectiveness =
                                                        100 - min(($strategy->avg_days_to_mitigate / 30) * 100, 100);
                                                @endphp
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: {{ $effectiveness }}%"
                                                        aria-valuenow="{{ $effectiveness }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ round($effectiveness) }}%</small>
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

@push('page-js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Monthly Trends Chart
        const monthlyTrendsData = @json($monthlyTrends);
        const monthlyTrendsChart = new ApexCharts(document.querySelector("#monthlyTrendsChart"), {
            series: [{
                name: 'High Risks',
                data: monthlyTrendsData.map(item => item.high_risks)
            }, {
                name: 'Medium Risks',
                data: monthlyTrendsData.map(item => item.medium_risks)
            }, {
                name: 'Low Risks',
                data: monthlyTrendsData.map(item => item.low_risks)
            }],
            chart: {
                type: 'area',
                height: 300,
                stacked: true,
                toolbar: {
                    show: true
                }
            },
            colors: ['#ff4d4f', '#faad14', '#52c41a'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left'
            },
            xaxis: {
                categories: monthlyTrendsData.map(item => item.month)
            }
        });

        monthlyTrendsChart.render();

        // Export functions
        function exportChart(chartId) {
            // Implement chart export functionality
        }

        function exportData(dataId) {
            // Implement data export functionality
        }
    </script>
@endpush

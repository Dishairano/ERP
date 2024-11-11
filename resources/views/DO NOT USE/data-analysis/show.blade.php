@extends('layouts/contentNavbarLayout')

@section('title', $config->name)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('vendors/css/charts/apexcharts.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Analysis Results</h4>
                    <div class="card-header-actions">
                        <form action="{{ route('data-analysis.analyze', $config) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary me-1">
                                <i data-feather="refresh-cw"></i> Run Analysis
                            </button>
                        </form>
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                Export
                            </button>
                            <div class="dropdown-menu">
                                <form action="{{ route('data-analysis.export', ['result' => $results->last()?->id ?? 0]) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="format" value="excel">
                                    <button type="submit" class="dropdown-item">
                                        <i data-feather="file-text"></i> Excel
                                    </button>
                                </form>
                                <form action="{{ route('data-analysis.export', ['result' => $results->last()?->id ?? 0]) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="format" value="csv">
                                    <button type="submit" class="dropdown-item">
                                        <i data-feather="file"></i> CSV
                                    </button>
                                </form>
                                <form action="{{ route('data-analysis.export', ['result' => $results->last()?->id ?? 0]) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="format" value="pdf">
                                    <button type="submit" class="dropdown-item">
                                        <i data-feather="file"></i> PDF
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($results->isEmpty())
                        <p>No analysis results available. Click "Run Analysis" to generate results.</p>
                    @else
                        <div class="row">
                            <!-- Charts will be rendered here -->
                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="chart1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="chart2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <!-- Dynamic headers based on analysis type -->
                                                @foreach ($results->last()->result_data[0] ?? [] as $key => $value)
                                                    <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results->last()->result_data ?? [] as $row)
                                                <tr>
                                                    @foreach ($row as $value)
                                                        <td>{{ $value }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('vendors/js/charts/apexcharts.min.js') }}"></script>
@endsection

@section('page-script')
    <script>
        // Initialize charts if results exist
        @if ($results->isNotEmpty())
            const latestResult = @json($results->last());
            initializeCharts(latestResult.result_data);
        @endif

        function initializeCharts(data) {
            // Chart initialization logic based on analysis type
            switch ("{{ $config->type }}") {
                case 'sales':
                    initializeSalesCharts(data);
                    break;
                case 'finance':
                    initializeFinanceCharts(data);
                    break;
                case 'inventory':
                    initializeInventoryCharts(data);
                    break;
                case 'hr':
                    initializeHRCharts(data);
                    break;
            }
        }

        function initializeSalesCharts(data) {
            // Sales charts initialization
            const salesByRegion = data.reduce((acc, curr) => {
                acc[curr.region] = (acc[curr.region] || 0) + curr.total_sales;
                return acc;
            }, {});

            const revenueByRegion = data.reduce((acc, curr) => {
                acc[curr.region] = (acc[curr.region] || 0) + curr.revenue;
                return acc;
            }, {});

            // Sales by Region Chart
            new ApexCharts(document.querySelector('#chart1'), {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Sales',
                    data: Object.values(salesByRegion)
                }],
                xaxis: {
                    categories: Object.keys(salesByRegion)
                },
                title: {
                    text: 'Sales by Region'
                }
            }).render();

            // Revenue by Region Chart
            new ApexCharts(document.querySelector('#chart2'), {
                chart: {
                    type: 'pie',
                    height: 350
                },
                series: Object.values(revenueByRegion),
                labels: Object.keys(revenueByRegion),
                title: {
                    text: 'Revenue by Region'
                }
            }).render();
        }

        function initializeFinanceCharts(data) {
            // Finance charts initialization
            const expensesByCategory = data.reduce((acc, curr) => {
                acc[curr.category] = curr.total_expense;
                return acc;
            }, {});

            const transactionsByCategory = data.reduce((acc, curr) => {
                acc[curr.category] = curr.transaction_count;
                return acc;
            }, {});

            // Total Expenses by Category Chart
            new ApexCharts(document.querySelector('#chart1'), {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Total Expenses',
                    data: Object.values(expensesByCategory)
                }],
                xaxis: {
                    categories: Object.keys(expensesByCategory)
                },
                title: {
                    text: 'Expenses by Category'
                }
            }).render();

            // Transaction Count by Category Chart
            new ApexCharts(document.querySelector('#chart2'), {
                chart: {
                    type: 'donut',
                    height: 350
                },
                series: Object.values(transactionsByCategory),
                labels: Object.keys(transactionsByCategory),
                title: {
                    text: 'Transaction Count by Category'
                }
            }).render();
        }

        function initializeInventoryCharts(data) {
            // Inventory charts initialization
            const stockByProduct = data.reduce((acc, curr) => {
                acc[curr.name] = curr.total_stock;
                return acc;
            }, {});

            const avgPriceByProduct = data.reduce((acc, curr) => {
                acc[curr.name] = curr.average_price;
                return acc;
            }, {});

            // Stock Levels Chart
            new ApexCharts(document.querySelector('#chart1'), {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Stock Level',
                    data: Object.values(stockByProduct)
                }],
                xaxis: {
                    categories: Object.keys(stockByProduct)
                },
                title: {
                    text: 'Stock Levels by Product'
                }
            }).render();

            // Average Price Chart
            new ApexCharts(document.querySelector('#chart2'), {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                    name: 'Average Price',
                    data: Object.values(avgPriceByProduct)
                }],
                xaxis: {
                    categories: Object.keys(avgPriceByProduct)
                },
                title: {
                    text: 'Average Price by Product'
                }
            }).render();
        }

        function initializeHRCharts(data) {
            // HR charts initialization
            const employeesByDepartment = data.reduce((acc, curr) => {
                acc[curr.name] = curr.employee_count;
                return acc;
            }, {});

            const avgSalaryByDepartment = data.reduce((acc, curr) => {
                acc[curr.name] = curr.average_salary;
                return acc;
            }, {});

            // Employee Count Chart
            new ApexCharts(document.querySelector('#chart1'), {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Employees',
                    data: Object.values(employeesByDepartment)
                }],
                xaxis: {
                    categories: Object.keys(employeesByDepartment)
                },
                title: {
                    text: 'Employees by Department'
                }
            }).render();

            // Average Salary Chart
            new ApexCharts(document.querySelector('#chart2'), {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Average Salary',
                    data: Object.values(avgSalaryByDepartment)
                }],
                xaxis: {
                    categories: Object.keys(avgSalaryByDepartment)
                },
                title: {
                    text: 'Average Salary by Department'
                }
            }).render();
        }
    </script>
@endsection

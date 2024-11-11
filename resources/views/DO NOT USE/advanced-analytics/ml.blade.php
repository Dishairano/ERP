@extends('layouts/contentNavbarLayout')

@section('title', 'Machine Learning Analytics')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Machine Learning Analytics</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Predictive Analytics</h4>
                                </div>
                                <div class="card-body">
                                    <div id="predictive-analytics-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Model Performance</h4>
                                </div>
                                <div class="card-body">
                                    <div id="model-performance-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
@endsection

@section('page-script')
    <script>
        $(function() {
            'use strict';

            var predictiveAnalyticsChart = new ApexCharts(
                document.querySelector("#predictive-analytics-chart"), {
                    chart: {
                        height: 350,
                        type: 'line',
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Predictions',
                        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
                    }],
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
                    }
                }
            );
            predictiveAnalyticsChart.render();

            var modelPerformanceChart = new ApexCharts(
                document.querySelector("#model-performance-chart"), {
                    chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Accuracy',
                        data: [0.95, 0.88, 0.92, 0.85, 0.94]
                    }],
                    xaxis: {
                        categories: ['Model 1', 'Model 2', 'Model 3', 'Model 4', 'Model 5']
                    }
                }
            );
            modelPerformanceChart.render();
        });
    </script>
@endsection

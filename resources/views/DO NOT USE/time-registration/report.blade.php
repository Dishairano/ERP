@extends('layouts/contentNavbarLayout')

@section('title', 'Time Registration Report')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Time Registration Overview</h4>
                    <div class="d-flex">
                        <input type="month" class="form-control me-1" id="month-filter" value="{{ date('Y-m') }}">
                        <button class="btn btn-primary" onclick="filterData()">Filter</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <canvas id="timeByProjectChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container">
                                <canvas id="timeByTypeChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    @if (auth()->user()->can('view-all-time-registrations'))
                                        <th>Employee</th>
                                    @endif
                                    <th>Project</th>
                                    <th>Hours</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timeRegistrations as $registration)
                                    <tr>
                                        <td>{{ $registration->date->format('Y-m-d') }}</td>
                                        @if (auth()->user()->can('view-all-time-registrations'))
                                            <td>{{ $registration->user->name }}</td>
                                        @endif
                                        <td>{{ $registration->project->name }}</td>
                                        <td>{{ $registration->hours }}</td>
                                        <td>
                                            <span
                                                class="badge rounded-pill badge-light-{{ $registration->type === 'work' ? 'primary' : ($registration->type === 'overtime' ? 'warning' : 'info') }}">
                                                {{ ucfirst($registration->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill badge-light-{{ $registration->status === 'approved' ? 'success' : ($registration->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
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
@endsection

@section('vendor-script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
@endsection

@section('page-script')
    <script>
        let timeByProjectChart, timeByTypeChart;

        function initCharts() {
            const projectCtx = document.getElementById('timeByProjectChart').getContext('2d');
            const typeCtx = document.getElementById('timeByTypeChart').getContext('2d');

            timeByProjectChart = new Chart(projectCtx, {
                type: 'pie',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: []
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Time by Project'
                        }
                    }
                }
            });

            timeByTypeChart = new Chart(typeCtx, {
                type: 'pie',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: []
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Time by Type'
                        }
                    }
                }
            });
        }

        function filterData() {
            const month = document.getElementById('month-filter').value;
            // Add your filter logic here
        }

        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });
    </script>
@endsection

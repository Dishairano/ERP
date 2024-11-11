@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Reports')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Reports</h4>
            <a href="{{ route('reports.create') }}" class="btn btn-primary">Generate New Report</a>
        </div>
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Total Income</th>
                        <th>Total Expense</th>
                        <th>Net Income</th>
                        <th>Assets</th>
                        <th>Liabilities</th>
                        <th>Equity</th>
                        <th>Date Range</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $report->type)) }}</td>
                        <td>${{ number_format($report->total_income, 2) }}</td>
                        <td>${{ number_format($report->total_expense, 2) }}</td>
                        <td>${{ number_format($report->net_income, 2) }}</td>
                        <td>${{ number_format($report->assets, 2) }}</td>
                        <td>${{ number_format($report->liabilities, 2) }}</td>
                        <td>${{ number_format($report->equity, 2) }}</td>
                        <td>{{ $report->start_date }} - {{ $report->end_date }}</td>
                        <td>
                            <a href="{{ route('reports.show', $report->id) }}" class="btn btn-info">View</a>
                            <a href="{{ route('reports.download', $report->id) }}" class="btn btn-secondary">Download
                                PDF</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
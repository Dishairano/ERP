@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - View Report')

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
            <h4>Report Details</h4>
        </div>
        <div class="card-body">
            <h5>Report Type: {{ ucfirst(str_replace('_', ' ', $report->type)) }}</h5>
            <p><strong>Total Income:</strong> ${{ number_format($report->total_income, 2) }}</p>
            <p><strong>Total Expense:</strong> ${{ number_format($report->total_expense, 2) }}</p>
            <p><strong>Net Income:</strong> ${{ number_format($report->net_income, 2) }}</p>
            <p><strong>Assets:</strong> ${{ number_format($report->assets, 2) }}</p>
            <p><strong>Liabilities:</strong> ${{ number_format($report->liabilities, 2) }}</p>
            <p><strong>Equity:</strong> ${{ number_format($report->equity, 2) }}</p>
            <p><strong>Date Range:</strong> {{ $report->start_date }} - {{ $report->end_date }}</p>

            <a href="{{ route('reports.download', $report->id) }}" class="btn btn-secondary">Download PDF</a>
        </div>
    </div>
</div>
@endsection
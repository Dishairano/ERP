@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

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
            <h4>Review Invoice #{{ $invoice->invoice_number }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Client Name:</strong> {{ $invoice->client_name }}</p>
            <p><strong>Amount:</strong> ${{ number_format($invoice->amount, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
            <p><strong>Date:</strong> {{ $invoice->created_at->format('Y-m-d') }}</p>

            <!-- Add more details if needed -->

            <a href="{{ route('invoices.downloadPdf', $invoice->id) }}" class="btn btn-primary">Download PDF</a>
        </div>
    </div>
</div>
@endsection
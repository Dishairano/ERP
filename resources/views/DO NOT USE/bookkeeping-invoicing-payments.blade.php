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
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Invoicing & Payments</h4>
            <a href="{{ route('invoices.create') }}" class="btn btn-success">Add Invoice</a>
        </div>
        <div class="card-body">
            <!-- Invoice Table -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->client_name }}</td>
                        <td>{{ $invoice->amount }}</td>
                        <td>{{ $invoice->status }}</td>
                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('invoices.review', $invoice->id) }}" class="btn btn-primary">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
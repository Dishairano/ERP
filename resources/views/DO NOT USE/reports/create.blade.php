@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Generate Report')

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
            <h4>Generate New Report</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('reports.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="type">Report Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="income_statement">Income Statement</option>
                        <option value="balance_sheet">Balance Sheet</option>
                        <option value="cash_flow">Cash Flow</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="total_income">Total Income</label>
                    <input type="number" step="0.01" class="form-control" name="total_income" id="total_income">
                </div>

                <div class="form-group">
                    <label for="total_expense">Total Expense</label>
                    <input type="number" step="0.01" class="form-control" name="total_expense" id="total_expense">
                </div>

                <div class="form-group">
                    <label for="assets">Assets</label>
                    <input type="number" step="0.01" class="form-control" name="assets" id="assets">
                </div>

                <div class="form-group">
                    <label for="liabilities">Liabilities</label>
                    <input type="number" step="0.01" class="form-control" name="liabilities" id="liabilities">
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" name="start_date" id="start_date">
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" name="end_date" id="end_date">
                </div>

                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts/contentNavbarLayout')

@section('title', 'Quotes')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Quotes</h4>
                <a href="{{ route('quotes.create') }}" class="btn btn-success ms-auto">Create Quote</a>
            </div>
            <div class="card-body">
                <!-- Quotes Table -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Quote Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotes as $quote)
                            <tr>
                                <td>{{ $quote->id }}</td>
                                <td>{{ $quote->customer->name }}</td>
                                <td>{{ $quote->quote_date }}</td>
                                <td>{{ $quote->total_amount }}</td>
                                <td>{{ $quote->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

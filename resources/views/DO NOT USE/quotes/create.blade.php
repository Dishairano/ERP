@extends('layouts/contentNavbarLayout')

@section('title', 'Create Quote')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Create Quote</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('quotes.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quote_date" class="form-label">Quote Date</label>
                        <input type="date" name="quote_date" id="quote_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="total_amount" class="form-label">Total Amount</label>
                        <input type="number" name="total_amount" id="total_amount" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection

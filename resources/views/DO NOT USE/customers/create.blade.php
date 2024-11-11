@extends('layouts/contentNavbarLayout')

@section('title', 'Add Customer')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Add New Customer</h4>
        </div>
        <div class="card-body">
            <!-- Display validation errors if any -->
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Customer Form -->
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Customer Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" id="address" class="form-control" required>{{ old('address') }}</textarea>
                </div>

                <button type="submit" class="btn btn-success">Save Customer</button>
            </form>
        </div>
    </div>
</div>

@endsection
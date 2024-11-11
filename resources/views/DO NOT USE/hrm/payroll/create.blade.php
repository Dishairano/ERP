@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Create Payroll')

@section('content')
    <h4 class="fw-bold">Create New Payroll Record</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('hrm.payroll.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label">Employee</label>
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror"
                            required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date"
                            class="form-control @error('payment_date') is-invalid @enderror" required>
                        @error('payment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="salary" class="form-label">Salary</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="salary" id="salary"
                                class="form-control @error('salary') is-invalid @enderror" required>
                        </div>
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="bonus" class="form-label">Bonus</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="bonus" id="bonus"
                                class="form-control @error('bonus') is-invalid @enderror">
                        </div>
                        @error('bonus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="deductions" class="form-label">Deductions</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="deductions" id="deductions"
                                class="form-control @error('deductions') is-invalid @enderror">
                        </div>
                        @error('deductions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="payment_period" class="form-label">Payment Period</label>
                        <input type="text" name="payment_period" id="payment_period"
                            class="form-control @error('payment_period') is-invalid @enderror"
                            placeholder="e.g., January 2024" required>
                        @error('payment_period')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror"
                            required>
                            <option value="pending">Pending</option>
                            <option value="processed">Processed</option>
                            <option value="paid">Paid</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Create Payroll Record</button>
                    <a href="{{ route('hrm.payroll') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

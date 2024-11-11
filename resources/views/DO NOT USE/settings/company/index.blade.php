@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Company Profile</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Company Information</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('settings.company.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $company['name']) }}">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $company['address']) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone', $company['phone']) }}">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $company['email']) }}">
                            </div>

                            <div class="mb-3">
                                <label for="tax_number" class="form-label">Tax Number</label>
                                <input type="text" class="form-control" id="tax_number" name="tax_number"
                                    value="{{ old('tax_number', $company['tax_number']) }}">
                            </div>

                            <div class="mb-3">
                                <label for="logo" class="form-label">Company Logo</label>
                                @if ($company['logo'])
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $company['logo']) }}" alt="Company Logo"
                                            class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="logo" name="logo">
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

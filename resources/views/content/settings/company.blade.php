@extends('layouts/contentNavbarLayout')

@section('title', 'Company Profile')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Company Profile</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="text-center mb-3">
                                        <img id="logo-preview"
                                            src="{{ isset($settings['company_logo']) ? asset('storage/' . $settings['company_logo']) : asset('assets/img/default-company-logo.png') }}"
                                            alt="Company Logo" class="img-fluid mb-2" style="max-height: 150px;">

                                        <div class="mt-2">
                                            <label for="company_logo" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-upload"></i> Upload Logo
                                            </label>
                                            <input type="file" id="company_logo" name="company_logo" class="d-none"
                                                accept="image/*">
                                        </div>
                                        @error('company_logo')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_name">Company Name</label>
                                                <input type="text"
                                                    class="form-control @error('company_name') is-invalid @enderror"
                                                    id="company_name" name="company_name"
                                                    value="{{ old('company_name', $settings['company_name'] ?? '') }}"
                                                    required>
                                                @error('company_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_email">Company Email</label>
                                                <input type="email"
                                                    class="form-control @error('company_email') is-invalid @enderror"
                                                    id="company_email" name="company_email"
                                                    value="{{ old('company_email', $settings['company_email'] ?? '') }}"
                                                    required>
                                                @error('company_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_phone">Phone Number</label>
                                                <input type="text"
                                                    class="form-control @error('company_phone') is-invalid @enderror"
                                                    id="company_phone" name="company_phone"
                                                    value="{{ old('company_phone', $settings['company_phone'] ?? '') }}"
                                                    required>
                                                @error('company_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_website">Website</label>
                                                <input type="url"
                                                    class="form-control @error('company_website') is-invalid @enderror"
                                                    id="company_website" name="company_website"
                                                    value="{{ old('company_website', $settings['company_website'] ?? '') }}">
                                                @error('company_website')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="company_address">Address</label>
                                        <textarea class="form-control @error('company_address') is-invalid @enderror" id="company_address"
                                            name="company_address" rows="3" required>{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                                        @error('company_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tax_id">Tax ID / VAT Number</label>
                                        <input type="text" class="form-control @error('tax_id') is-invalid @enderror"
                                            id="tax_id" name="tax_id"
                                            value="{{ old('tax_id', $settings['tax_id'] ?? '') }}">
                                        @error('tax_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="registration_number">Registration Number</label>
                                        <input type="text"
                                            class="form-control @error('registration_number') is-invalid @enderror"
                                            id="registration_number" name="registration_number"
                                            value="{{ old('registration_number', $settings['registration_number'] ?? '') }}">
                                        @error('registration_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Company Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logo preview
            document.getElementById('company_logo').addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('logo-preview').src = e.target.result;
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Phone number formatting
            const phoneInput = document.getElementById('company_phone');
            phoneInput.addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
            });
        });
    </script>
@endpush

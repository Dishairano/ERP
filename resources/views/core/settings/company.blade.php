@extends('layouts/contentNavbarLayout')

@section('title', 'Company Profile')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Company Profile</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Company Logo -->
                                <div class="col-12 mb-4">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        @if (isset($settings['company_logo']))
                                            <img src="{{ Storage::url($settings['company_logo']) }}" alt="company logo"
                                                class="d-block rounded" height="100">
                                        @else
                                            <img src="{{ asset('assets/img/default-company-logo.png') }}" alt="default logo"
                                                class="d-block rounded" height="100">
                                        @endif
                                        <div class="button-wrapper">
                                            <label for="company_logo" class="btn btn-primary me-2 mb-3">
                                                <i class="ri-upload-2-line"></i>&nbsp; Upload Logo
                                                <input type="file" id="company_logo" name="company_logo"
                                                    class="account-file-input" hidden accept="image/png, image/jpeg">
                                            </label>
                                            <div class="text-muted">Allowed JPG or PNG. Max size of 2MB</div>
                                            @error('company_logo')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Basic Information -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_name">Company Name</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name" name="company_name"
                                        value="{{ old('company_name', $settings['company_name'] ?? '') }}" required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_email">Company Email</label>
                                    <input type="email" class="form-control @error('company_email') is-invalid @enderror"
                                        id="company_email" name="company_email"
                                        value="{{ old('company_email', $settings['company_email'] ?? '') }}" required>
                                    @error('company_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_phone">Phone Number</label>
                                    <input type="text" class="form-control @error('company_phone') is-invalid @enderror"
                                        id="company_phone" name="company_phone"
                                        value="{{ old('company_phone', $settings['company_phone'] ?? '') }}" required>
                                    @error('company_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_website">Website</label>
                                    <input type="url"
                                        class="form-control @error('company_website') is-invalid @enderror"
                                        id="company_website" name="company_website"
                                        value="{{ old('company_website', $settings['company_website'] ?? '') }}">
                                    @error('company_website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Address Information -->
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="company_address">Address</label>
                                    <textarea class="form-control @error('company_address') is-invalid @enderror" id="company_address"
                                        name="company_address" rows="2" required>{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_city">City</label>
                                    <input type="text" class="form-control @error('company_city') is-invalid @enderror"
                                        id="company_city" name="company_city"
                                        value="{{ old('company_city', $settings['company_city'] ?? '') }}" required>
                                    @error('company_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_state">State/Province</label>
                                    <input type="text" class="form-control @error('company_state') is-invalid @enderror"
                                        id="company_state" name="company_state"
                                        value="{{ old('company_state', $settings['company_state'] ?? '') }}" required>
                                    @error('company_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_postal_code">Postal Code</label>
                                    <input type="text"
                                        class="form-control @error('company_postal_code') is-invalid @enderror"
                                        id="company_postal_code" name="company_postal_code"
                                        value="{{ old('company_postal_code', $settings['company_postal_code'] ?? '') }}"
                                        required>
                                    @error('company_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_country">Country</label>
                                    <input type="text"
                                        class="form-control @error('company_country') is-invalid @enderror"
                                        id="company_country" name="company_country"
                                        value="{{ old('company_country', $settings['company_country'] ?? '') }}" required>
                                    @error('company_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Registration Information -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_tax_number">Tax Number</label>
                                    <input type="text"
                                        class="form-control @error('company_tax_number') is-invalid @enderror"
                                        id="company_tax_number" name="company_tax_number"
                                        value="{{ old('company_tax_number', $settings['company_tax_number'] ?? '') }}">
                                    @error('company_tax_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="company_registration_number">Registration
                                        Number</label>
                                    <input type="text"
                                        class="form-control @error('company_registration_number') is-invalid @enderror"
                                        id="company_registration_number" name="company_registration_number"
                                        value="{{ old('company_registration_number', $settings['company_registration_number'] ?? '') }}">
                                    @error('company_registration_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview uploaded image
            const upload = document.getElementById('company_logo');
            const preview = upload.parentElement.parentElement.querySelector('img');

            upload.onchange = function() {
                const file = this.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                }
            };
        });
    </script>
@endsection

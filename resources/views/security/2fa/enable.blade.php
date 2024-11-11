@extends('layouts/contentNavbarLayout')

@section('title', 'Enable Two-Factor Authentication')

@section('content')
    <h4 class="fw-bold">Enable Two-Factor Authentication</h4>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Enhance Your Account Security</h5>
                        <p class="text-muted">Two-factor authentication adds an extra layer of security to your account. In
                            addition to your password, you'll need to enter a verification code sent to your phone.</p>
                    </div>

                    <div class="alert alert-info d-flex mb-4">
                        <i class="ri-information-line fs-4 me-2"></i>
                        <div>
                            Make sure you have access to your phone before enabling 2FA. You'll need it to verify your
                            identity each time you sign in.
                        </div>
                    </div>

                    <form action="{{ route('security.2fa.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+</span>
                                <input type="text" name="phone_number" id="phone_number"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    placeholder="1234567890" value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Enter your phone number including country code</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Send Verification Code</button>
                    </form>

                    <!-- Verification Code Form (shown after sending code) -->
                    @if (session('verification_sent'))
                        <form action="{{ route('security.2fa.verify') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="verification_code" class="form-label">Verification Code</label>
                                <input type="text" name="verification_code" id="verification_code"
                                    class="form-control @error('verification_code') is-invalid @enderror"
                                    placeholder="Enter 6-digit code" maxlength="6" required>
                                @error('verification_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-success">Verify & Enable 2FA</button>
                                <button type="button" class="btn btn-link"
                                    onclick="event.preventDefault(); document.getElementById('resend-code').submit();">
                                    Resend Code
                                </button>
                            </div>
                        </form>

                        <form id="resend-code" action="{{ route('security.2fa.resend') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Setup Instructions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">How It Works</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-label-primary rounded p-2 me-2">1</div>
                            <div>Enter your phone number</div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-label-primary rounded p-2 me-2">2</div>
                            <div>Receive verification code via SMS</div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-label-primary rounded p-2 me-2">3</div>
                            <div>Enter the code to verify</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-label-primary rounded p-2 me-2">4</div>
                            <div>2FA is enabled for your account</div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <i class="ri-alert-line fs-4 me-2"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Important</h6>
                                <div>Keep your phone number up to date. You won't be able to access your account if you lose
                                    access to your phone number.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

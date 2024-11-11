@extends('layouts/contentNavbarLayout')

@section('title', 'Email Configuration')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Email Configuration</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.email.update') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mail_driver">Mail Driver</label>
                                        <select class="form-control @error('mail_driver') is-invalid @enderror"
                                            id="mail_driver" name="mail_driver" required>
                                            <option value="smtp"
                                                {{ old('mail_driver', $settings['mail_driver'] ?? '') == 'smtp' ? 'selected' : '' }}>
                                                SMTP</option>
                                            <option value="sendmail"
                                                {{ old('mail_driver', $settings['mail_driver'] ?? '') == 'sendmail' ? 'selected' : '' }}>
                                                Sendmail</option>
                                            <option value="mailgun"
                                                {{ old('mail_driver', $settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>
                                                Mailgun</option>
                                            <option value="ses"
                                                {{ old('mail_driver', $settings['mail_driver'] ?? '') == 'ses' ? 'selected' : '' }}>
                                                Amazon SES</option>
                                        </select>
                                        @error('mail_driver')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mail_encryption">Mail Encryption</label>
                                        <select class="form-control @error('mail_encryption') is-invalid @enderror"
                                            id="mail_encryption" name="mail_encryption" required>
                                            <option value="tls"
                                                {{ old('mail_encryption', $settings['mail_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>
                                                TLS</option>
                                            <option value="ssl"
                                                {{ old('mail_encryption', $settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>
                                                SSL</option>
                                            <option value="none"
                                                {{ old('mail_encryption', $settings['mail_encryption'] ?? '') == 'none' ? 'selected' : '' }}>
                                                None</option>
                                        </select>
                                        @error('mail_encryption')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mail_host">SMTP Host</label>
                                        <input type="text" class="form-control @error('mail_host') is-invalid @enderror"
                                            id="mail_host" name="mail_host"
                                            value="{{ old('mail_host', $settings['mail_host'] ?? '') }}" required>
                                        @error('mail_host')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mail_port">SMTP Port</label>
                                        <input type="number" class="form-control @error('mail_port') is-invalid @enderror"
                                            id="mail_port" name="mail_port"
                                            value="{{ old('mail_port', $settings['mail_port'] ?? '') }}" required>
                                        @error('mail_port')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mail_username">SMTP Username</label>
                                        <input type="text"
                                            class="form-control @error('mail_username') is-invalid @enderror"
                                            id="mail_username" name="mail_username"
                                            value="{{ old('mail_username', $settings['mail_username'] ?? '') }}" required>
                                        @error('mail_username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mail_password">SMTP Password</label>
                                        <input type="password"
                                            class="form-control @error('mail_password') is-invalid @enderror"
                                            id="mail_password" name="mail_password"
                                            value="{{ old('mail_password', $settings['mail_password'] ?? '') }}" required>
                                        @error('mail_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mail_from_address">From Address</label>
                                        <input type="email"
                                            class="form-control @error('mail_from_address') is-invalid @enderror"
                                            id="mail_from_address" name="mail_from_address"
                                            value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}"
                                            required>
                                        @error('mail_from_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mail_from_name">From Name</label>
                                        <input type="text"
                                            class="form-control @error('mail_from_name') is-invalid @enderror"
                                            id="mail_from_name" name="mail_from_name"
                                            value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}"
                                            required>
                                        @error('mail_from_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email_signature">Email Signature</label>
                                        <textarea class="form-control @error('email_signature') is-invalid @enderror" id="email_signature"
                                            name="email_signature" rows="3">{{ old('email_signature', $settings['email_signature'] ?? '') }}</textarea>
                                        @error('email_signature')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <form action="{{ route('settings.email.test') }}" method="POST" class="d-inline">
                                    @csrf
                                    <div class="input-group" style="width: 300px;">
                                        <input type="email" class="form-control" name="test_email"
                                            placeholder="Enter email for testing" required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-info">Send Test Email</button>
                                        </div>
                                    </div>
                                </form>

                                <button type="submit" class="btn btn-primary">Save Email Settings</button>
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
            // Show/hide SMTP fields based on mail driver
            const mailDriver = document.getElementById('mail_driver');
            const smtpFields = document.querySelectorAll('.smtp-field');

            function toggleSmtpFields() {
                const isSmtp = mailDriver.value === 'smtp';
                smtpFields.forEach(field => {
                    field.style.display = isSmtp ? 'block' : 'none';
                });
            }

            mailDriver.addEventListener('change', toggleSmtpFields);
            toggleSmtpFields();
        });
    </script>
@endpush

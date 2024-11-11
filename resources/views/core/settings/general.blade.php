@extends('layouts/contentNavbarLayout')

@section('title', 'General Settings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">General Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.general.update') }}" method="POST">
                            @csrf
                            <div class="row">
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
                                    <label class="form-label" for="timezone">Timezone</label>
                                    <select class="form-select @error('timezone') is-invalid @enderror" id="timezone"
                                        name="timezone" required>
                                        @foreach (timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}"
                                                {{ old('timezone', $settings['timezone'] ?? '') == $timezone ? 'selected' : '' }}>
                                                {{ $timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="date_format">Date Format</label>
                                    <select class="form-select @error('date_format') is-invalid @enderror" id="date_format"
                                        name="date_format" required>
                                        <option value="Y-m-d"
                                            {{ old('date_format', $settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>
                                            YYYY-MM-DD ({{ date('Y-m-d') }})
                                        </option>
                                        <option value="d/m/Y"
                                            {{ old('date_format', $settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>
                                            DD/MM/YYYY ({{ date('d/m/Y') }})
                                        </option>
                                        <option value="m/d/Y"
                                            {{ old('date_format', $settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>
                                            MM/DD/YYYY ({{ date('m/d/Y') }})
                                        </option>
                                        <option value="d-m-Y"
                                            {{ old('date_format', $settings['date_format'] ?? '') == 'd-m-Y' ? 'selected' : '' }}>
                                            DD-MM-YYYY ({{ date('d-m-Y') }})
                                        </option>
                                    </select>
                                    @error('date_format')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="time_format">Time Format</label>
                                    <select class="form-select @error('time_format') is-invalid @enderror" id="time_format"
                                        name="time_format" required>
                                        <option value="H:i"
                                            {{ old('time_format', $settings['time_format'] ?? '') == 'H:i' ? 'selected' : '' }}>
                                            24 Hour ({{ date('H:i') }})
                                        </option>
                                        <option value="h:i A"
                                            {{ old('time_format', $settings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' }}>
                                            12 Hour ({{ date('h:i A') }})
                                        </option>
                                    </select>
                                    @error('time_format')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="currency">Currency</label>
                                    <select class="form-select @error('currency') is-invalid @enderror" id="currency"
                                        name="currency" required>
                                        <option value="USD"
                                            {{ old('currency', $settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>
                                            USD ($)</option>
                                        <option value="EUR"
                                            {{ old('currency', $settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>
                                            EUR (€)</option>
                                        <option value="GBP"
                                            {{ old('currency', $settings['currency'] ?? '') == 'GBP' ? 'selected' : '' }}>
                                            GBP (£)</option>
                                        <option value="JPY"
                                            {{ old('currency', $settings['currency'] ?? '') == 'JPY' ? 'selected' : '' }}>
                                            JPY (¥)</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="fiscal_year_start">Fiscal Year Start</label>
                                    <input type="date"
                                        class="form-control @error('fiscal_year_start') is-invalid @enderror"
                                        id="fiscal_year_start" name="fiscal_year_start"
                                        value="{{ old('fiscal_year_start', $settings['fiscal_year_start'] ?? '') }}"
                                        required>
                                    @error('fiscal_year_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="language">Language</label>
                                    <select class="form-select @error('language') is-invalid @enderror" id="language"
                                        name="language" required>
                                        <option value="en"
                                            {{ old('language', $settings['language'] ?? '') == 'en' ? 'selected' : '' }}>
                                            English</option>
                                        <option value="es"
                                            {{ old('language', $settings['language'] ?? '') == 'es' ? 'selected' : '' }}>
                                            Spanish</option>
                                        <option value="fr"
                                            {{ old('language', $settings['language'] ?? '') == 'fr' ? 'selected' : '' }}>
                                            French</option>
                                        <option value="de"
                                            {{ old('language', $settings['language'] ?? '') == 'de' ? 'selected' : '' }}>
                                            German</option>
                                    </select>
                                    @error('language')
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

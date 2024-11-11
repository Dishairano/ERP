@extends('layouts/contentNavbarLayout')

@section('title', 'Localization Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Localization Settings</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.localization.update') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="default_language">Default Language</label>
                                        <select class="form-control @error('default_language') is-invalid @enderror"
                                            id="default_language" name="default_language" required>
                                            <option value="en"
                                                {{ old('default_language', $settings['default_language'] ?? '') == 'en' ? 'selected' : '' }}>
                                                English</option>
                                            <option value="es"
                                                {{ old('default_language', $settings['default_language'] ?? '') == 'es' ? 'selected' : '' }}>
                                                Spanish</option>
                                            <option value="fr"
                                                {{ old('default_language', $settings['default_language'] ?? '') == 'fr' ? 'selected' : '' }}>
                                                French</option>
                                            <option value="de"
                                                {{ old('default_language', $settings['default_language'] ?? '') == 'de' ? 'selected' : '' }}>
                                                German</option>
                                            <option value="nl"
                                                {{ old('default_language', $settings['default_language'] ?? '') == 'nl' ? 'selected' : '' }}>
                                                Dutch</option>
                                        </select>
                                        @error('default_language')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Available Languages</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="lang_en"
                                                name="available_languages[]" value="en"
                                                {{ in_array('en', old('available_languages', $settings['available_languages'] ?? [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="lang_en">English</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="lang_es"
                                                name="available_languages[]" value="es"
                                                {{ in_array('es', old('available_languages', $settings['available_languages'] ?? [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="lang_es">Spanish</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="lang_fr"
                                                name="available_languages[]" value="fr"
                                                {{ in_array('fr', old('available_languages', $settings['available_languages'] ?? [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="lang_fr">French</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="lang_de"
                                                name="available_languages[]" value="de"
                                                {{ in_array('de', old('available_languages', $settings['available_languages'] ?? [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="lang_de">German</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="lang_nl"
                                                name="available_languages[]" value="nl"
                                                {{ in_array('nl', old('available_languages', $settings['available_languages'] ?? [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="lang_nl">Dutch</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="default_timezone">Default Timezone</label>
                                        <select class="form-control @error('default_timezone') is-invalid @enderror"
                                            id="default_timezone" name="default_timezone" required>
                                            @foreach (timezone_identifiers_list() as $timezone)
                                                <option value="{{ $timezone }}"
                                                    {{ old('default_timezone', $settings['default_timezone'] ?? '') == $timezone ? 'selected' : '' }}>
                                                    {{ $timezone }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('default_timezone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_day_of_week">First Day of Week</label>
                                        <select class="form-control @error('first_day_of_week') is-invalid @enderror"
                                            id="first_day_of_week" name="first_day_of_week" required>
                                            <option value="0"
                                                {{ old('first_day_of_week', $settings['first_day_of_week'] ?? '') == 0 ? 'selected' : '' }}>
                                                Sunday</option>
                                            <option value="1"
                                                {{ old('first_day_of_week', $settings['first_day_of_week'] ?? '') == 1 ? 'selected' : '' }}>
                                                Monday</option>
                                        </select>
                                        @error('first_day_of_week')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_format">Date Format</label>
                                        <select class="form-control @error('date_format') is-invalid @enderror"
                                            id="date_format" name="date_format" required>
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
                                        </select>
                                        @error('date_format')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="time_format">Time Format</label>
                                        <select class="form-control @error('time_format') is-invalid @enderror"
                                            id="time_format" name="time_format" required>
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
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="currency_code">Currency Code</label>
                                        <input type="text"
                                            class="form-control @error('currency_code') is-invalid @enderror"
                                            id="currency_code" name="currency_code"
                                            value="{{ old('currency_code', $settings['currency_code'] ?? 'USD') }}"
                                            maxlength="3" required>
                                        @error('currency_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="currency_symbol">Currency Symbol</label>
                                        <input type="text"
                                            class="form-control @error('currency_symbol') is-invalid @enderror"
                                            id="currency_symbol" name="currency_symbol"
                                            value="{{ old('currency_symbol', $settings['currency_symbol'] ?? '$') }}"
                                            required>
                                        @error('currency_symbol')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="number_of_decimals">Number of Decimals</label>
                                        <input type="number"
                                            class="form-control @error('number_of_decimals') is-invalid @enderror"
                                            id="number_of_decimals" name="number_of_decimals"
                                            value="{{ old('number_of_decimals', $settings['number_of_decimals'] ?? 2) }}"
                                            min="0" max="4" required>
                                        @error('number_of_decimals')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="thousand_separator">Thousand Separator</label>
                                        <input type="text"
                                            class="form-control @error('thousand_separator') is-invalid @enderror"
                                            id="thousand_separator" name="thousand_separator"
                                            value="{{ old('thousand_separator', $settings['thousand_separator'] ?? ',') }}"
                                            maxlength="1" required>
                                        @error('thousand_separator')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="decimal_separator">Decimal Separator</label>
                                        <input type="text"
                                            class="form-control @error('decimal_separator') is-invalid @enderror"
                                            id="decimal_separator" name="decimal_separator"
                                            value="{{ old('decimal_separator', $settings['decimal_separator'] ?? '.') }}"
                                            maxlength="1" required>
                                        @error('decimal_separator')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Localization Settings</button>
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
            if (typeof $.fn.select2 !== 'undefined') {
                $('#default_timezone').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            }
        });
    </script>
@endpush

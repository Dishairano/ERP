@extends('content.settings.index')

@section('settings-content')
    <h4>General Settings</h4>
    <div class="row">
        <div class="col-12">
            <form action="{{ route('settings.general.update') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="site_name">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name"
                            value="{{ $settings['site_name'] ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="timezone">Timezone</label>
                        <select class="form-select" id="timezone" name="timezone" required>
                            @foreach (timezone_identifiers_list() as $timezone)
                                <option value="{{ $timezone }}"
                                    {{ ($settings['timezone'] ?? '') == $timezone ? 'selected' : '' }}>
                                    {{ $timezone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="site_description">Site Description</label>
                    <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ $settings['site_description'] ?? '' }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="date_format">Date Format</label>
                        <select class="form-select" id="date_format" name="date_format" required>
                            <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>
                                YYYY-MM-DD
                            </option>
                            <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>
                                DD/MM/YYYY
                            </option>
                            <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>
                                MM/DD/YYYY
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="time_format">Time Format</label>
                        <select class="form-select" id="time_format" name="time_format" required>
                            <option value="H:i" {{ ($settings['time_format'] ?? '') == 'H:i' ? 'selected' : '' }}>
                                24 Hour
                            </option>
                            <option value="h:i A" {{ ($settings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' }}>
                                12 Hour
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="language">Language</label>
                        <select class="form-select" id="language" name="language" required>
                            <option value="en" {{ ($settings['language'] ?? '') == 'en' ? 'selected' : '' }}>English
                            </option>
                            <option value="es" {{ ($settings['language'] ?? '') == 'es' ? 'selected' : '' }}>Spanish
                            </option>
                            <option value="fr" {{ ($settings['language'] ?? '') == 'fr' ? 'selected' : '' }}>French
                            </option>
                            <option value="de" {{ ($settings['language'] ?? '') == 'de' ? 'selected' : '' }}>German
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="currency">Currency</label>
                    <select class="form-select" id="currency" name="currency" required>
                        <option value="USD" {{ ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD ($)
                        </option>
                        <option value="EUR" {{ ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)
                        </option>
                        <option value="GBP" {{ ($settings['currency'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP (£)
                        </option>
                        <option value="JPY" {{ ($settings['currency'] ?? '') == 'JPY' ? 'selected' : '' }}>JPY (¥)
                        </option>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

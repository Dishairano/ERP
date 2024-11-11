@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">General Settings</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">System Settings</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('settings.general.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="site_name" class="form-label">Site Name</label>
                                <input type="text" class="form-control" id="site_name" name="site_name"
                                    value="{{ old('site_name', $settings['site_name']) }}">
                            </div>

                            <div class="mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-select" id="timezone" name="timezone">
                                    @foreach (timezone_identifiers_list() as $timezone)
                                        <option value="{{ $timezone }}"
                                            {{ old('timezone', $settings['timezone']) == $timezone ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="date_format" class="form-label">Date Format</label>
                                <select class="form-select" id="date_format" name="date_format">
                                    <option value="Y-m-d"
                                        {{ old('date_format', $settings['date_format']) == 'Y-m-d' ? 'selected' : '' }}>
                                        YYYY-MM-DD
                                    </option>
                                    <option value="d/m/Y"
                                        {{ old('date_format', $settings['date_format']) == 'd/m/Y' ? 'selected' : '' }}>
                                        DD/MM/YYYY
                                    </option>
                                    <option value="m/d/Y"
                                        {{ old('date_format', $settings['date_format']) == 'm/d/Y' ? 'selected' : '' }}>
                                        MM/DD/YYYY
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="time_format" class="form-label">Time Format</label>
                                <select class="form-select" id="time_format" name="time_format">
                                    <option value="H:i"
                                        {{ old('time_format', $settings['time_format']) == 'H:i' ? 'selected' : '' }}>
                                        24 Hour (14:30)
                                    </option>
                                    <option value="h:i A"
                                        {{ old('time_format', $settings['time_format']) == 'h:i A' ? 'selected' : '' }}>
                                        12 Hour (02:30 PM)
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="language" class="form-label">Language</label>
                                <select class="form-select" id="language" name="language">
                                    <option value="en"
                                        {{ old('language', $settings['language']) == 'en' ? 'selected' : '' }}>English
                                    </option>
                                    <option value="es"
                                        {{ old('language', $settings['language']) == 'es' ? 'selected' : '' }}>Spanish
                                    </option>
                                    <option value="fr"
                                        {{ old('language', $settings['language']) == 'fr' ? 'selected' : '' }}>French
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

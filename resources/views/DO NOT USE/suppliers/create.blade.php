@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Nieuwe Leverancier</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('suppliers.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Contactgegevens</h5>
                                    <div class="form-group">
                                        <label for="name">Bedrijfsnaam</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="contact_person">Contactpersoon</label>
                                        <input type="text"
                                            class="form-control @error('contact_person') is-invalid @enderror"
                                            id="contact_person" name="contact_person" value="{{ old('contact_person') }}"
                                            required>
                                        @error('contact_person')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Telefoon</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5>Adresgegevens</h5>
                                    <div class="form-group">
                                        <label for="address">Adres</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" value="{{ old('address') }}" required>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="postal_code">Postcode</label>
                                        <input type="text"
                                            class="form-control @error('postal_code') is-invalid @enderror" id="postal_code"
                                            name="postal_code" value="{{ old('postal_code') }}" required>
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="city">Plaats</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city') }}" required>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="country">Land</label>
                                        <input type="text" class="form-control @error('country') is-invalid @enderror"
                                            id="country" name="country" value="{{ old('country') }}" required>
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Bedrijfsgegevens</h5>
                                    <div class="form-group">
                                        <label for="tax_number">BTW Nummer</label>
                                        <input type="text" class="form-control @error('tax_number') is-invalid @enderror"
                                            id="tax_number" name="tax_number" value="{{ old('tax_number') }}">
                                        @error('tax_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="registration_number">KvK Nummer</label>
                                        <input type="text"
                                            class="form-control @error('registration_number') is-invalid @enderror"
                                            id="registration_number" name="registration_number"
                                            value="{{ old('registration_number') }}">
                                        @error('registration_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5>Classificatie</h5>
                                    <div class="form-group">
                                        <label for="classification">Leverancier Classificatie</label>
                                        <select class="form-control @error('classification') is-invalid @enderror"
                                            id="classification" name="classification" required>
                                            <option value="">Selecteer classificatie</option>
                                            <option value="strategic"
                                                {{ old('classification') == 'strategic' ? 'selected' : '' }}>Strategisch
                                            </option>
                                            <option value="tactical"
                                                {{ old('classification') == 'tactical' ? 'selected' : '' }}>Tactisch
                                            </option>
                                            <option value="operational"
                                                {{ old('classification') == 'operational' ? 'selected' : '' }}>Operationeel
                                            </option>
                                        </select>
                                        @error('classification')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_critical"
                                                name="is_critical" value="1"
                                                {{ old('is_critical') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_critical">
                                                Kritieke Leverancier
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="notes">Opmerkingen</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Leverancier Toevoegen</button>
                                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Annuleren</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Nieuwe Verlofaanvraag</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('leave-requests.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="type" class="form-label">Type Verlof</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type"
                                    name="type" required>
                                    <option value="">Selecteer type</option>
                                    <option value="vacation">Vakantie</option>
                                    <option value="sick">Ziek</option>
                                    <option value="personal">Persoonlijk</option>
                                    <option value="other">Anders</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Datum</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="end_date" class="form-label">Eind Datum</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    id="end_date" name="end_date" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="reason" class="form-label">Reden</label>
                                <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3"
                                    required></textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Indienen</button>
                                <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Annuleren</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

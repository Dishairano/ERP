@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Nieuw Project Template</h4>
                        <a href="{{ route('projects.templates') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Terug naar Templates
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('projects.templates.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="name">Template Naam</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description">Beschrijving</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="client_id">Standaard Klant</label>
                                        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id"
                                            name="client_id" required>
                                            <option value="">Selecteer Klant</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}"
                                                    {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="manager_id">Standaard Project Manager</label>
                                        <select class="form-select @error('manager_id') is-invalid @enderror"
                                            id="manager_id" name="manager_id" required>
                                            <option value="">Selecteer Manager</option>
                                            @foreach ($managers as $manager)
                                                <option value="{{ $manager->id }}"
                                                    {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                                    {{ $manager->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('manager_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="start_date">Standaard Start Datum</label>
                                                <input type="date"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    id="start_date" name="start_date"
                                                    value="{{ old('start_date', date('Y-m-d')) }}" required>
                                                @error('start_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="end_date">Standaard Eind Datum</label>
                                                <input type="date"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    id="end_date" name="end_date"
                                                    value="{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}"
                                                    required>
                                                @error('end_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="budget">Standaard Budget</label>
                                        <div class="input-group">
                                            <span class="input-group-text">€</span>
                                            <input type="number" step="0.01"
                                                class="form-control @error('budget') is-invalid @enderror" id="budget"
                                                name="budget" value="{{ old('budget', 0) }}" required>
                                            @error('budget')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Template Scope</label>
                                        <div id="scope-items">
                                            @if (old('scope'))
                                                @foreach (old('scope') as $item)
                                                    <div class="input-group mb-2">
                                                        <input type="text" class="form-control" name="scope[]"
                                                            value="{{ $item }}">
                                                        <button type="button" class="btn btn-outline-danger"
                                                            onclick="this.parentElement.remove()">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" name="scope[]"
                                                    placeholder="Voeg scope item toe">
                                                <button type="button" class="btn btn-outline-primary"
                                                    onclick="addScopeItem()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="status" value="planned">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Template Aanmaken</button>
                                </div>
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
        function addScopeItem() {
            const container = document.getElementById('scope-items');
            const newItem = document.createElement('div');
            newItem.className = 'input-group mb-2';
            newItem.innerHTML = `
        <input type="text" class="form-control" name="scope[]" placeholder="Voeg scope item toe">
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-minus"></i>
        </button>
    `;
            container.insertBefore(newItem, container.lastElementChild);
        }
    </script>
@endpush

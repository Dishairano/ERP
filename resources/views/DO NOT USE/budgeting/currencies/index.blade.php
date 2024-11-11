@extends('layouts.contentNavbarLayout')

@section('title', 'Currencies')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Budgeting /</span> Currencies
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Currencies</h5>
                <a href="{{ route('currencies.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Add New Currency
                </a>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Symbol</th>
                                <th>Exchange Rate</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currencies as $currency)
                                <tr>
                                    <td>{{ $currency->code }}</td>
                                    <td>{{ $currency->name }}</td>
                                    <td>{{ $currency->symbol }}</td>
                                    <td>{{ number_format($currency->exchange_rate, 4) }}</td>
                                    <td>
                                        @if ($currency->is_default)
                                            <span class="badge bg-primary">Default</span>
                                        @endif
                                        @if ($currency->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('currencies.edit', $currency) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                @if (!$currency->is_default)
                                                    <form action="{{ route('currencies.destroy', $currency) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure you want to delete this currency?')">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

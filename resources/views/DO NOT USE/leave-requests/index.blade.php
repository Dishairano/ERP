@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Verlofaanvragen</h5>
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                            Nieuwe Verlofaanvraag
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Start Datum</th>
                                        <th>Eind Datum</th>
                                        <th>Status</th>
                                        <th>Reden</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaveRequests as $request)
                                        <tr>
                                            <td>{{ $request->type }}</td>
                                            <td>{{ $request->start_date->format('d-m-Y') }}</td>
                                            <td>{{ $request->end_date->format('d-m-Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->reason }}</td>
                                            <td>
                                                <a href="{{ route('leave-requests.show', $request) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

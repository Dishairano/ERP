@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Verlofaanvraag Details</h5>
                        <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">
                            Terug naar Overzicht
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Type Verlof</h6>
                                <p>{{ ucfirst($leaveRequest->type) }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Status</h6>
                                <span
                                    class="badge bg-{{ $leaveRequest->status === 'approved' ? 'success' : ($leaveRequest->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($leaveRequest->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Start Datum</h6>
                                <p>{{ $leaveRequest->start_date->format('d-m-Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Eind Datum</h6>
                                <p>{{ $leaveRequest->end_date->format('d-m-Y') }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6>Reden</h6>
                            <p>{{ $leaveRequest->reason }}</p>
                        </div>

                        @if (auth()->user()->can('approve-leave-requests') && $leaveRequest->status === 'pending')
                            <div class="mt-4">
                                <form action="{{ route('leave-requests.update', $leaveRequest) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success">Goedkeuren</button>
                                </form>

                                <form action="{{ route('leave-requests.update', $leaveRequest) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger">Afwijzen</button>
                                </form>
                            </div>
                        @endif

                        @if ($leaveRequest->approved_by)
                            <div class="mt-4">
                                <h6>Beoordeeld door</h6>
                                <p>{{ $leaveRequest->approver->name }} op
                                    {{ $leaveRequest->approved_at->format('d-m-Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

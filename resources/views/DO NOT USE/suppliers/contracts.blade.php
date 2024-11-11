@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            @if (isset($supplier))
                                Contracts - {{ $supplier->name }}
                            @else
                                All Supplier Contracts
                            @endif
                        </h4>
                        @if (isset($supplier))
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newContractModal">
                                <i class="fas fa-plus"></i> New Contract
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Contract #</th>
                                        @if (!isset($supplier))
                                            <th>Supplier</th>
                                        @endif
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contracts as $contract)
                                        <tr>
                                            <td>{{ $contract->contract_number }}</td>
                                            @if (!isset($supplier))
                                                <td>
                                                    <a href="{{ route('suppliers.show', $contract->supplier) }}">
                                                        {{ $contract->supplier->name }}
                                                    </a>
                                                </td>
                                            @endif
                                            <td>{{ $contract->start_date->format('d-m-Y') }}</td>
                                            <td>
                                                {{ $contract->end_date->format('d-m-Y') }}
                                                @if ($contract->isExpiring())
                                                    <span class="badge badge-warning">Expiring Soon</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($contract->value, 2) }} {{ $contract->currency }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $contract->isActive() ? 'success' : 'danger' }}">
                                                    {{ $contract->isActive() ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                    data-target="#viewContractModal-{{ $contract->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if (isset($supplier))
                                                    <a href="{{ route('suppliers.contracts', $supplier) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('suppliers.contracts', $contract->supplier) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($supplier))
        <!-- New Contract Modal -->
        <div class="modal fade" id="newContractModal" tabindex="-1" role="dialog" aria-labelledby="newContractModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('suppliers.contracts.store', $supplier) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="newContractModalLabel">New Contract</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contract_number">Contract Number</label>
                                        <input type="text" class="form-control" id="contract_number"
                                            name="contract_number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="value">Contract Value</label>
                                        <input type="number" step="0.01" class="form-control" id="value"
                                            name="value" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <select class="form-control" id="currency" name="currency" required>
                                            <option value="EUR">EUR</option>
                                            <option value="USD">USD</option>
                                            <option value="GBP">GBP</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_terms">Payment Terms</label>
                                        <input type="text" class="form-control" id="payment_terms" name="payment_terms"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_days">Payment Days</label>
                                        <input type="number" class="form-control" id="payment_days" name="payment_days"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="terms_conditions">Terms & Conditions</label>
                                        <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="auto_renewal"
                                                name="auto_renewal" value="1">
                                            <label class="custom-control-label" for="auto_renewal">Auto Renewal</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="renewal_notice_days">Renewal Notice Days</label>
                                        <input type="number" class="form-control" id="renewal_notice_days"
                                            name="renewal_notice_days" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Contract</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- View Contract Modals -->
    @foreach ($contracts as $contract)
        <div class="modal fade" id="viewContractModal-{{ $contract->id }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Contract Details - {{ $contract->contract_number }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Contract Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Contract Number:</th>
                                        <td>{{ $contract->contract_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Title:</th>
                                        <td>{{ $contract->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Start Date:</th>
                                        <td>{{ $contract->start_date->format('d-m-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>End Date:</th>
                                        <td>{{ $contract->end_date->format('d-m-Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Financial Details</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Contract Value:</th>
                                        <td>{{ number_format($contract->value, 2) }} {{ $contract->currency }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Terms:</th>
                                        <td>{{ $contract->payment_terms }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Days:</th>
                                        <td>{{ $contract->payment_days }} days</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Terms & Conditions</h6>
                                <p>{{ $contract->terms_conditions }}</p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Renewal</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Auto Renewal:</th>
                                        <td>{{ $contract->auto_renewal ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Notice Period:</th>
                                        <td>{{ $contract->renewal_notice_days }} days</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

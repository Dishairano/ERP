@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Budget Transfers</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newTransferModal">
                                New Transfer
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>From Budget</th>
                                        <th>To Budget</th>
                                        <th>Amount</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transfers as $transfer)
                                        <tr>
                                            <td>{{ $transfer->fromBudget->category_name }}</td>
                                            <td>{{ $transfer->toBudget->category_name }}</td>
                                            <td>{{ number_format($transfer->amount, 2) }}
                                                {{ $transfer->fromBudget->currency }}</td>
                                            <td>{{ $transfer->reason }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $transfer->status === 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($transfer->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $transfer->created_at->format('Y-m-d H:i') }}</td>
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

    <!-- New Transfer Modal -->
    <div class="modal fade" id="newTransferModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('budgets.transfer') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Budget Transfer</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>From Budget</label>
                            <select name="from_budget_id" class="form-control" required>
                                @foreach ($transfers->pluck('fromBudget')->unique() as $budget)
                                    <option value="{{ $budget->id }}">{{ $budget->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>To Budget</label>
                            <select name="to_budget_id" class="form-control" required>
                                @foreach ($transfers->pluck('toBudget')->unique() as $budget)
                                    <option value="{{ $budget->id }}">{{ $budget->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Reason</label>
                            <textarea name="reason" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

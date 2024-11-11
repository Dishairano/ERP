@extends('layouts/contentNavbarLayout')

@section('title', 'Expense Approvals')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pending Expense Approvals</h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Budget</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Requested By</th>
                                        <th>Date Requested</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->budget->name }}</td>
                                            <td>{{ $expense->description }}</td>
                                            <td>{{ number_format($expense->amount, 2) }}</td>
                                            <td>{{ $expense->creator->name ?? 'N/A' }}</td>
                                            <td>{{ $expense->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <form action="{{ route('expenses.approve', $expense) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="return confirm('Are you sure you want to approve this expense?')">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                        data-target="#rejectModal{{ $expense->id }}">
                                                        Reject
                                                    </button>
                                                </div>

                                                <!-- Reject Modal -->
                                                <div class="modal fade" id="rejectModal{{ $expense->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('expenses.reject', $expense) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Reject Expense</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="rejection_reason">Reason for
                                                                            Rejection</label>
                                                                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Reject</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No pending expenses found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

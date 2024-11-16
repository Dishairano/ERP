@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Balances')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Balances</h3>
                    <div class="card-tools">
                        <form class="form-inline" method="GET">
                            <select name="year" class="form-control" onchange="this.form.submit()">
                                @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <h4>My Leave Balances</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Total Days</th>
                                    <th>Used Days</th>
                                    <th>Pending Days</th>
                                    <th>Remaining Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($balances as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item['type']->name }}</strong>
                                            @if($item['type']->description)
                                                <br><small class="text-muted">{{ $item['type']->description }}</small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item['balance']->total_days, 1) }}</td>
                                        <td>{{ number_format($item['balance']->used_days, 1) }}</td>
                                        <td>{{ number_format($item['balance']->pending_days, 1) }}</td>
                                        <td>
                                            <span class="badge {{ $item['balance']->remaining_days > 0 ? 'badge-success' : 'badge-danger' }}">
                                                {{ number_format($item['balance']->remaining_days, 1) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item['type']->paid)
                                                <span class="badge badge-info">Paid</span>
                                            @else
                                                <span class="badge badge-warning">Unpaid</span>
                                            @endif
                                            @if($item['type']->allow_carry_forward)
                                                <span class="badge badge-success">Carry Forward</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(isset($allBalances) && count($allBalances) > 0)
                        <h4 class="mt-4">Team Leave Balances</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Leave Type</th>
                                        <th>Total Days</th>
                                        <th>Used Days</th>
                                        <th>Pending Days</th>
                                        <th>Remaining Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allBalances as $userId => $userBalances)
                                        @foreach($userBalances as $balance)
                                            <tr>
                                                <td>{{ $balance->user->name }}</td>
                                                <td>{{ $balance->leaveType->name }}</td>
                                                <td>{{ number_format($balance->total_days, 1) }}</td>
                                                <td>{{ number_format($balance->used_days, 1) }}</td>
                                                <td>{{ number_format($balance->pending_days, 1) }}</td>
                                                <td>
                                                    <span class="badge {{ $balance->remaining_days > 0 ? 'badge-success' : 'badge-danger' }}">
                                                        {{ number_format($balance->remaining_days, 1) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .badge {
        font-size: 100%;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endsection

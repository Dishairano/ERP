@extends('layouts/contentNavbarLayout')

@section('title', 'Budget Audit Trails')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Budget Audit Trails</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Budget</th>
                                        <th>Event</th>
                                        <th>Old Values</th>
                                        <th>New Values</th>
                                        <th>User</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($audits as $audit)
                                        <tr>
                                            <td>{{ $audit->auditable->name ?? 'N/A' }}</td>
                                            <td>{{ ucfirst($audit->event) }}</td>
                                            <td>
                                                @if ($audit->old_values)
                                                    @foreach ($audit->old_values as $attribute => $value)
                                                        <strong>{{ $attribute }}:</strong> {{ $value }}<br>
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if ($audit->new_values)
                                                    @foreach ($audit->new_values as $attribute => $value)
                                                        <strong>{{ $attribute }}:</strong> {{ $value }}<br>
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $audit->user->name ?? 'System' }}</td>
                                            <td>{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($audits->hasPages())
                            <div class="mt-4">
                                {{ $audits->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

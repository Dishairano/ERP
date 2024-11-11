@extends('layouts/contentNavbarLayout')

@section('title', 'Distribution Planning')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Distribution Plans</h5>
        <a href="{{ route('logistics.distribution-planning.create') }}" class="btn btn-primary">Add New Plan</a>
    </div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Plan Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $plan)
                <tr>
                    <td>{{ $plan->plan_name }}</td>
                    <td>{{ $plan->start_date }}</td>
                    <td>{{ $plan->end_date }}</td>
                    <td>
                        <a href="{{ route('logistics.distribution-planning.edit', $plan->id) }}"
                            class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('logistics.distribution-planning.destroy', $plan->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
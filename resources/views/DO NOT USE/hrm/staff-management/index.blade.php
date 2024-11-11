@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Staff Management')

@section('content')
    <h4 class="fw-bold">Staff Management</h4>
    <a href="{{ route('hrm.staff-management.create') }}" class="btn btn-primary mb-4">Add New Staff</a>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>
                        <a href="{{ route('hrm.staff-management.edit', $member->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('hrm.staff-management.destroy', $member->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

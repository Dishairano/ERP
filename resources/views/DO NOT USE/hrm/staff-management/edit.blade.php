@extends('layouts/contentNavbarLayout')

@section('title', 'HRM - Edit Staff')

@section('content')
    <h4 class="fw-bold">Edit Staff Details</h4>

    <form action="{{ route('hrm.staff-management.update', $staff->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $staff->name }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ $staff->email }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update Staff</button>
    </form>
@endsection

@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2>Department Presets</h2>
            </div>
            <div class="col text-end">
                <a href="{{ route('preset-departments.create') }}" class="btn btn-primary">Create New Preset</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ $department->description }}</td>
                                    <td>{{ $department->is_active ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <a href="{{ route('preset-departments.edit', $department) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('preset-departments.destroy', $department) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $departments->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

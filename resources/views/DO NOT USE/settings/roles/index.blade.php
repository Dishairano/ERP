@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="fw-bold py-3 mb-4">Roles & Permissions</h4>
            <a href="{{ route('settings.roles.create') }}" class="btn btn-primary">Create Role</a>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role['name'] }}</td>
                                        <td>
                                            @foreach ($role['permissions'] as $permission)
                                                <span class="badge bg-label-primary me-1">{{ $permission }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('settings.roles.edit', $role['id']) }}">
                                                        <i class="ri-pencil-line me-2"></i> Edit
                                                    </a>
                                                    @if ($role['name'] !== 'Administrator')
                                                        <form action="{{ route('settings.roles.destroy', $role['id']) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Are you sure?')">
                                                                <i class="ri-delete-bin-line me-2"></i> Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

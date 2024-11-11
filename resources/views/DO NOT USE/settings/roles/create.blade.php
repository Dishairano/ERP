@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Roles /</span> Create New Role
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Role Details</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('settings.roles.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Permissions</label>
                                @foreach ($permissions as $key => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="permission_{{ $key }}"
                                            name="permissions[]" value="{{ $key }}">
                                        <label class="form-check-label" for="permission_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary">Create Role</button>
                            <a href="{{ route('settings.roles') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

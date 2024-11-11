@extends('layouts/contentNavbarLayout')

@section('title', 'User Management')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Users</h6>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#createUserModal">
                            <i class="fas fa-user-plus"></i> New User
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Users Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm mr-2">
                                                        @if ($user->avatar)
                                                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                                                alt="{{ $user->name }}" class="rounded-circle">
                                                        @else
                                                            <div class="avatar-initial rounded-circle bg-primary">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    {{ $user->name }}
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach ($user->roles as $role)
                                                    <span class="badge badge-primary">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if ($user->email_verified_at)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="#editUserModal" data-user="{{ json_encode($user) }}"
                                                        data-roles="{{ json_encode($user->roles->pluck('id')) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if (auth()->id() !== $user->id)
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-toggle="modal" data-target="#deleteUserModal"
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->name }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('settings.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                            <label>Roles</label>
                            @foreach ($roles as $role)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_{{ $role->id }}"
                                        name="roles[]" value="{{ $role->id }}">
                                    <label class="custom-control-label" for="role_{{ $role->id }}">
                                        {{ ucfirst($role->name) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUserForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="edit_name"
                                name="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="edit_email" name="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="edit_password">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="edit_password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="edit_password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" id="edit_password_confirmation"
                                name="password_confirmation">
                        </div>

                        <div class="form-group">
                            <label>Roles</label>
                            @foreach ($roles as $role)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input edit-role"
                                        id="edit_role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}">
                                    <label class="custom-control-label" for="edit_role_{{ $role->id }}">
                                        {{ ucfirst($role->name) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteUserForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete user "<span id="deleteUserName"></span>"?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle edit user modal
            $('#editUserModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const user = button.data('user');
                const roles = button.data('roles');
                const modal = $(this);

                // Update form action
                modal.find('#editUserForm').attr('action', `/settings/users/${user.id}`);

                // Fill form fields
                modal.find('#edit_name').val(user.name);
                modal.find('#edit_email').val(user.email);

                // Reset password fields
                modal.find('#edit_password').val('');
                modal.find('#edit_password_confirmation').val('');

                // Reset and set role checkboxes
                modal.find('.edit-role').prop('checked', false);
                roles.forEach(roleId => {
                    modal.find(`#edit_role_${roleId}`).prop('checked', true);
                });
            });

            // Handle delete user modal
            $('#deleteUserModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const userId = button.data('user-id');
                const userName = button.data('user-name');
                const modal = $(this);

                modal.find('#deleteUserForm').attr('action', `/settings/users/${userId}`);
                modal.find('#deleteUserName').text(userName);
            });
        });
    </script>
@endpush

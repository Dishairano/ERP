@extends('layouts/contentNavbarLayout')

@section('title', 'Roles & Permissions')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Roles List -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Roles</h6>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#createRoleModal">
                            <i class="fas fa-plus"></i> New Role
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach ($roles as $role)
                                <a href="#" class="list-group-item list-group-item-action role-item"
                                    data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}"
                                    data-permissions="{{ json_encode($role->permissions->pluck('id')) }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ ucfirst($role->name) }}</h6>
                                        <span class="badge badge-primary badge-pill">
                                            {{ $role->permissions->count() }} permissions
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Permissions -->
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Role Permissions</h6>
                    </div>
                    <div class="card-body">
                        <form id="rolePermissionsForm" action="" method="POST" style="display: none;">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="role_name">Role Name</label>
                                <input type="text" class="form-control" id="role_name" name="name" required>
                            </div>

                            <div class="row mb-3">
                                <!-- General Permissions -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">General Permissions</h6>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="view_dashboard" name="permissions[]" value="view_dashboard">
                                        <label class="custom-control-label" for="view_dashboard">View Dashboard</label>
                                    </div>
                                </div>

                                <!-- User Management -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">User Management</h6>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="manage_users" name="permissions[]" value="manage_users">
                                        <label class="custom-control-label" for="manage_users">Manage Users</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="view_users" name="permissions[]" value="view_users">
                                        <label class="custom-control-label" for="view_users">View Users</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- Project Permissions -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Project Management</h6>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="manage_projects" name="permissions[]" value="manage_projects">
                                        <label class="custom-control-label" for="manage_projects">Manage Projects</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="view_projects" name="permissions[]" value="view_projects">
                                        <label class="custom-control-label" for="view_projects">View Projects</label>
                                    </div>
                                </div>

                                <!-- Task Permissions -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Task Management</h6>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="manage_tasks" name="permissions[]" value="manage_tasks">
                                        <label class="custom-control-label" for="manage_tasks">Manage Tasks</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="view_tasks" name="permissions[]" value="view_tasks">
                                        <label class="custom-control-label" for="view_tasks">View Tasks</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- Settings Permissions -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Settings</h6>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="manage_settings" name="permissions[]" value="manage_settings">
                                        <label class="custom-control-label" for="manage_settings">Manage Settings</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="view_settings" name="permissions[]" value="view_settings">
                                        <label class="custom-control-label" for="view_settings">View Settings</label>
                                    </div>
                                </div>

                                <!-- Audit Log Permissions -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Audit Log</h6>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="view_audit_logs" name="permissions[]" value="view_audit_logs">
                                        <label class="custom-control-label" for="view_audit_logs">View Audit Logs</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input permission-checkbox"
                                            id="export_audit_logs" name="permissions[]" value="export_audit_logs">
                                        <label class="custom-control-label" for="export_audit_logs">Export Audit
                                            Logs</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-danger" id="deleteRoleBtn">Delete Role</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>

                        <div id="noRoleSelected" class="text-center py-5">
                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Select a role to view and edit its permissions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('settings.roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new_role_name">Role Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="new_role_name" name="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Role Modal -->
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteRoleForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this role? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentRoleId = null;

            // Handle role selection
            $('.role-item').on('click', function(e) {
                e.preventDefault();
                currentRoleId = $(this).data('role-id');
                const roleName = $(this).data('role-name');
                const permissions = $(this).data('permissions');

                // Update form
                $('#rolePermissionsForm').attr('action', `/settings/roles/${currentRoleId}`);
                $('#role_name').val(roleName);

                // Reset all checkboxes
                $('.permission-checkbox').prop('checked', false);

                // Check permissions for this role
                permissions.forEach(permissionId => {
                    $(`#${permissionId}`).prop('checked', true);
                });

                // Show form, hide placeholder
                $('#rolePermissionsForm').show();
                $('#noRoleSelected').hide();

                // Update active state
                $('.role-item').removeClass('active');
                $(this).addClass('active');
            });

            // Handle delete button
            $('#deleteRoleBtn').on('click', function() {
                if (currentRoleId) {
                    $('#deleteRoleForm').attr('action', `/settings/roles/${currentRoleId}`);
                    $('#deleteRoleModal').modal('show');
                }
            });
        });
    </script>
@endpush

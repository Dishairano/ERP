<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleSettingsController extends Controller
{
  public function index()
  {
    $roles = Role::all();
    return view('settings.roles.index', compact('roles'));
  }

  public function create()
  {
    $permissions = [
      'view' => 'View',
      'create' => 'Create',
      'edit' => 'Edit',
      'delete' => 'Delete',
      'manage_users' => 'Manage Users',
      'manage_roles' => 'Manage Roles',
      'manage_settings' => 'Manage Settings',
    ];

    return view('settings.roles.create', compact('permissions'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:roles',
      'description' => 'nullable|string|max:255',
      'permissions' => 'required|array',
      'permissions.*' => 'string',
    ]);

    Role::create([
      'name' => $validated['name'],
      'description' => $validated['description'],
      'permissions' => $validated['permissions'],
    ]);

    return redirect()->route('settings.access.roles')
      ->with('success', 'Role created successfully');
  }

  public function edit(Role $role)
  {
    $permissions = [
      'view' => 'View',
      'create' => 'Create',
      'edit' => 'Edit',
      'delete' => 'Delete',
      'manage_users' => 'Manage Users',
      'manage_roles' => 'Manage Roles',
      'manage_settings' => 'Manage Settings',
    ];

    return view('settings.roles.edit', compact('role', 'permissions'));
  }

  public function update(Request $request, Role $role)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
      'description' => 'nullable|string|max:255',
      'permissions' => 'required|array',
      'permissions.*' => 'string',
    ]);

    $role->update([
      'name' => $validated['name'],
      'description' => $validated['description'],
      'permissions' => $validated['permissions'],
    ]);

    return redirect()->route('settings.access.roles')
      ->with('success', 'Role updated successfully');
  }

  public function destroy(Role $role)
  {
    // Don't allow deleting the admin role
    if ($role->name === 'admin') {
      return redirect()->route('settings.access.roles')
        ->with('error', 'Cannot delete the admin role');
    }

    // Update users with this role to have the default 'user' role
    User::where('role', $role->name)->update(['role' => 'user']);

    $role->delete();

    return redirect()->route('settings.access.roles')
      ->with('success', 'Role deleted successfully');
  }

  public function users()
  {
    $users = User::all();
    $roles = Role::all();
    return view('settings.roles.users', compact('users', 'roles'));
  }

  public function createUser()
  {
    $roles = Role::all();
    return view('settings.roles.users.create', compact('roles'));
  }

  public function storeUser(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'role' => 'required|string|exists:roles,name',
    ]);

    User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => bcrypt($validated['password']),
      'role' => $validated['role'],
    ]);

    return redirect()->route('settings.access.users')
      ->with('success', 'User created successfully');
  }

  public function editUser(User $user)
  {
    $roles = Role::all();
    return view('settings.roles.users.edit', compact('user', 'roles'));
  }

  public function updateUser(Request $request, User $user)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
      'role' => 'required|string|exists:roles,name',
      'password' => 'nullable|string|min:8|confirmed',
    ]);

    $data = [
      'name' => $validated['name'],
      'email' => $validated['email'],
      'role' => $validated['role'],
    ];

    if (!empty($validated['password'])) {
      $data['password'] = bcrypt($validated['password']);
    }

    $user->update($data);

    return redirect()->route('settings.access.users')
      ->with('success', 'User updated successfully');
  }

  public function destroyUser(User $user)
  {
    // Don't allow deleting the last admin user
    if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
      return redirect()->route('settings.access.users')
        ->with('error', 'Cannot delete the last admin user');
    }

    $user->delete();

    return redirect()->route('settings.access.users')
      ->with('success', 'User deleted successfully');
  }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up()
  {
    Schema::create('permissions', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('guard_name')->default('web');
      $table->timestamps();

      $table->unique(['name', 'guard_name']);
    });

    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('guard_name')->default('web');
      $table->timestamps();

      $table->unique(['name', 'guard_name']);
    });

    Schema::create('role_has_permissions', function (Blueprint $table) {
      $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
      $table->foreignId('role_id')->constrained()->cascadeOnDelete();

      $table->primary(['permission_id', 'role_id']);
    });

    Schema::create('model_has_roles', function (Blueprint $table) {
      $table->foreignId('role_id')->constrained()->cascadeOnDelete();
      $table->string('model_type');
      $table->unsignedBigInteger('model_id');

      $table->primary(['role_id', 'model_id', 'model_type']);
      $table->index(['model_id', 'model_type']);
    });

    // Insert default permissions
    DB::table('permissions')->insert([
      // General permissions
      ['name' => 'view_dashboard', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],

      // User management permissions
      ['name' => 'manage_users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'view_users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],

      // Role management permissions
      ['name' => 'manage_roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'view_roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],

      // Settings permissions
      ['name' => 'manage_settings', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'view_settings', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],

      // Project permissions
      ['name' => 'manage_projects', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'view_projects', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],

      // Task permissions
      ['name' => 'manage_tasks', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'view_tasks', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],

      // Risk permissions
      ['name' => 'manage_risks', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'view_risks', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],

      // Audit log permissions
      ['name' => 'view_audit_logs', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'export_audit_logs', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
    ]);

    // Create admin role and assign all permissions
    $adminRole = DB::table('roles')->insertGetId([
      'name' => 'admin',
      'guard_name' => 'web',
      'created_at' => now(),
      'updated_at' => now()
    ]);

    $permissions = DB::table('permissions')->pluck('id');
    foreach ($permissions as $permissionId) {
      DB::table('role_has_permissions')->insert([
        'permission_id' => $permissionId,
        'role_id' => $adminRole
      ]);
    }
  }

  public function down()
  {
    Schema::dropIfExists('model_has_roles');
    Schema::dropIfExists('role_has_permissions');
    Schema::dropIfExists('roles');
    Schema::dropIfExists('permissions');
  }
};

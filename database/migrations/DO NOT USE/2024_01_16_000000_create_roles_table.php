<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->string('description')->nullable();
      $table->json('permissions')->nullable();
      $table->timestamps();
    });

    // Insert default roles
    DB::table('roles')->insert([
      [
        'name' => 'admin',
        'description' => 'Administrator with full access',
        'permissions' => json_encode(['*']),
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'name' => 'user',
        'description' => 'Standard user',
        'permissions' => json_encode([
          'dashboard.view',
          'dashboard.create',
          'dashboard.edit',
          'dashboard.delete',
        ]),
        'created_at' => now(),
        'updated_at' => now(),
      ]
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('roles');
  }
};

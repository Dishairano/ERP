<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('data_sources', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type');
      $table->json('configuration');
      $table->string('status')->default('active');
      $table->timestamps();
    });

    Schema::create('data_source_permissions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('data_source_id')->constrained()->onDelete('cascade');
      $table->foreignId('role_id')->constrained()->onDelete('cascade');
      $table->string('permission_level');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('data_source_permissions');
    Schema::dropIfExists('data_sources');
  }
};

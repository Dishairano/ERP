<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('dashboards', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('type')->default('personal'); // personal, department, company
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
      $table->json('layout_settings')->nullable();
      $table->json('theme_settings')->nullable();
      $table->boolean('is_default')->default(false);
      $table->boolean('is_public')->default(false);
      $table->string('status')->default('active');
      $table->json('permissions')->nullable();
      $table->timestamps();
      $table->softDeletes();

      // Indexes for better query performance
      $table->index('type');
      $table->index('status');
      $table->index(['user_id', 'is_default']);
    });

    // Create pivot table for dashboard sharing
    Schema::create('dashboard_shares', function (Blueprint $table) {
      $table->id();
      $table->foreignId('dashboard_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('permission_level'); // view, edit, manage
      $table->timestamps();

      $table->unique(['dashboard_id', 'user_id']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('dashboard_shares');
    Schema::dropIfExists('dashboards');
  }
};

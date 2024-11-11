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
    // Create project_feedback table
    Schema::create('project_feedback', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->text('content');
      $table->integer('rating')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    // Create project_kpis table
    Schema::create('project_kpis', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('metric');
      $table->decimal('target_value', 10, 2);
      $table->decimal('actual_value', 10, 2)->default(0);
      $table->date('measurement_date');
      $table->timestamps();
      $table->softDeletes();
    });

    // Create project_changes table
    Schema::create('project_changes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('type');
      $table->text('description');
      $table->json('old_values')->nullable();
      $table->json('new_values')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    // Create project_notifications table
    Schema::create('project_notifications', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->string('type');
      $table->text('message');
      $table->json('data')->nullable();
      $table->timestamp('read_at')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    // Create project_time_registrations table
    Schema::create('project_time_registrations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->foreignId('task_id')->nullable()->constrained('project_tasks')->onDelete('set null');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->date('date');
      $table->integer('hours');
      $table->text('description')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('project_time_registrations');
    Schema::dropIfExists('project_notifications');
    Schema::dropIfExists('project_changes');
    Schema::dropIfExists('project_kpis');
    Schema::dropIfExists('project_feedback');
  }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    // Time Registrations
    Schema::create('time_registrations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
      $table->foreignId('task_id')->nullable()->constrained('project_tasks')->nullOnDelete();
      $table->date('date');
      $table->time('start_time');
      $table->time('end_time');
      $table->decimal('hours', 5, 2);
      $table->text('description')->nullable();
      $table->string('status')->default('pending'); // pending, approved, rejected
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->text('rejection_reason')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    // Project Time Entries
    Schema::create('project_time_entries', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->date('date');
      $table->decimal('hours', 5, 2);
      $table->text('description')->nullable();
      $table->string('activity_type');
      $table->decimal('billable_hours', 5, 2)->default(0);
      $table->decimal('rate', 10, 2)->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    // Project Time Budgets
    Schema::create('project_time_budgets', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->decimal('allocated_hours', 8, 2);
      $table->decimal('used_hours', 8, 2)->default(0);
      $table->date('start_date');
      $table->date('end_date');
      $table->timestamps();
    });

    // Leave Types
    Schema::create('leave_types', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code')->unique();
      $table->text('description')->nullable();
      $table->boolean('requires_approval')->default(true);
      $table->boolean('paid')->default(true);
      $table->timestamps();
    });

    // Leave Policies
    Schema::create('leave_policies', function (Blueprint $table) {
      $table->id();
      $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
      $table->decimal('days_per_year', 5, 2);
      $table->boolean('can_carry_forward')->default(false);
      $table->decimal('max_carry_forward_days', 5, 2)->nullable();
      $table->integer('min_service_days_required')->default(0);
      $table->integer('max_consecutive_days')->nullable();
      $table->integer('notice_days_required')->default(0);
      $table->timestamps();
    });

    // Leave Balances
    Schema::create('leave_balances', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
      $table->integer('year');
      $table->decimal('total_days', 5, 2);
      $table->decimal('used_days', 5, 2)->default(0);
      $table->decimal('pending_days', 5, 2)->default(0);
      $table->decimal('carried_forward_days', 5, 2)->default(0);
      $table->timestamps();

      $table->unique(['user_id', 'leave_type_id', 'year']);
    });

    // Leave Requests
    Schema::create('leave_requests', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
      $table->date('start_date');
      $table->date('end_date');
      $table->decimal('total_days', 5, 2);
      $table->text('reason')->nullable();
      $table->string('status')->default('pending'); // pending, approved, rejected
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->text('rejection_reason')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    // Work Shifts
    Schema::create('work_shifts', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->time('start_time');
      $table->time('end_time');
      $table->decimal('hours', 4, 2);
      $table->json('break_times')->nullable();
      $table->boolean('is_night_shift')->default(false);
      $table->timestamps();
    });

    // Schedule Templates
    Schema::create('schedule_templates', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->json('pattern');
      $table->timestamps();
    });

    // Employee Schedules
    Schema::create('employee_schedules', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('shift_id')->constrained('work_shifts')->onDelete('cascade');
      $table->date('date');
      $table->time('actual_start_time')->nullable();
      $table->time('actual_end_time')->nullable();
      $table->string('status')->default('scheduled'); // scheduled, completed, absent
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->unique(['user_id', 'date']);
    });

    // Employee Availability
    Schema::create('employee_availability', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->date('date');
      $table->time('start_time');
      $table->time('end_time');
      $table->string('availability_type'); // available, unavailable, preferred
      $table->text('notes')->nullable();
      $table->timestamps();
    });

    // Overtime Records
    Schema::create('overtime_records', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('schedule_id')->constrained('employee_schedules')->onDelete('cascade');
      $table->date('date');
      $table->time('start_time');
      $table->time('end_time');
      $table->decimal('hours', 4, 2);
      $table->decimal('rate_multiplier', 3, 2)->default(1.5);
      $table->string('status')->default('pending'); // pending, approved, rejected
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->text('reason')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('overtime_records');
    Schema::dropIfExists('employee_availability');
    Schema::dropIfExists('employee_schedules');
    Schema::dropIfExists('schedule_templates');
    Schema::dropIfExists('work_shifts');
    Schema::dropIfExists('leave_requests');
    Schema::dropIfExists('leave_balances');
    Schema::dropIfExists('leave_policies');
    Schema::dropIfExists('leave_types');
    Schema::dropIfExists('project_time_budgets');
    Schema::dropIfExists('project_time_entries');
    Schema::dropIfExists('time_registrations');
  }
};

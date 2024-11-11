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
    Schema::create('hrm_attendances', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained('hrm_employees')->cascadeOnDelete();
      $table->date('date');
      $table->timestamp('check_in')->nullable();
      $table->timestamp('check_out')->nullable();
      $table->timestamp('break_start')->nullable();
      $table->timestamp('break_end')->nullable();
      $table->decimal('total_hours', 5, 2)->nullable();
      $table->decimal('overtime_hours', 5, 2)->nullable();
      $table->integer('late_minutes')->default(0);
      $table->integer('early_departure_minutes')->default(0);
      $table->integer('break_duration')->nullable(); // in minutes
      $table->string('status'); // present, absent, half-day, on-leave, work-from-home
      $table->string('shift_type'); // morning, evening, night, flexible
      $table->string('location'); // office, remote, client-site
      $table->string('ip_address')->nullable();
      $table->json('device_info')->nullable();
      $table->json('geo_location')->nullable();
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('employee_id');
      $table->index('date');
      $table->index('check_in');
      $table->index('check_out');
      $table->index('status');
      $table->index('shift_type');
      $table->index('location');
      $table->index(['employee_id', 'date']);
      $table->index(['employee_id', 'status']);
      $table->index(['date', 'status']);
      $table->index(['shift_type', 'status']);
      $table->index(['location', 'status']);
      $table->index(['check_in', 'check_out']);
      $table->index(['break_start', 'break_end']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hrm_attendances');
  }
};

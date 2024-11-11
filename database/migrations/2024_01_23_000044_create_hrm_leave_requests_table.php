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
    Schema::create('hrm_leave_requests', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained('hrm_employees')->cascadeOnDelete();
      $table->string('leave_type'); // annual, sick, maternity, paternity, bereavement, unpaid, etc.
      $table->date('start_date');
      $table->date('end_date');
      $table->decimal('duration', 4, 1); // in days
      $table->boolean('half_day')->default(false);
      $table->string('start_half')->nullable(); // first, second (for half days)
      $table->text('reason');
      $table->json('attachments')->nullable();
      $table->string('emergency_contact')->nullable();
      $table->string('emergency_phone')->nullable();
      $table->text('handover_notes')->nullable();
      $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
      $table->foreignId('approved_by')->nullable()->constrained('users');
      $table->timestamp('approved_at')->nullable();
      $table->foreignId('rejected_by')->nullable()->constrained('users');
      $table->timestamp('rejected_at')->nullable();
      $table->text('rejection_reason')->nullable();
      $table->text('cancellation_reason')->nullable();
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('employee_id');
      $table->index('leave_type');
      $table->index('start_date');
      $table->index('end_date');
      $table->index('status');
      $table->index('approved_by');
      $table->index('rejected_by');
      $table->index(['employee_id', 'leave_type']);
      $table->index(['employee_id', 'status']);
      $table->index(['start_date', 'end_date']);
      $table->index(['leave_type', 'status']);
      $table->index(['approved_by', 'approved_at']);
      $table->index(['rejected_by', 'rejected_at']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hrm_leave_requests');
  }
};

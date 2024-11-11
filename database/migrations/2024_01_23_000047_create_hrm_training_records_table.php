<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('hrm_training_records', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained('hrm_employees')->cascadeOnDelete();
      $table->string('training_type'); // course, workshop, certification, etc.
      $table->string('training_name');
      $table->text('description')->nullable();
      $table->string('provider')->nullable();
      $table->date('start_date');
      $table->date('end_date');
      $table->integer('duration_hours')->nullable();
      $table->decimal('cost', 10, 2)->nullable();
      $table->string('status')->default('planned'); // planned, in_progress, completed, cancelled
      $table->boolean('certification_obtained')->default(false);
      $table->string('certification_name')->nullable();
      $table->date('certification_expiry')->nullable();
      $table->decimal('score', 5, 2)->nullable();
      $table->text('feedback')->nullable();
      $table->json('attachments')->nullable();
      $table->timestamps();
      $table->softDeletes();

      // Use shorter index names
      $table->index(['certification_obtained', 'certification_expiry'], 'cert_status_idx');
      $table->index(['employee_id', 'status'], 'emp_training_status_idx');
      $table->index(['start_date', 'end_date'], 'training_period_idx');
    });
  }

  public function down()
  {
    Schema::dropIfExists('hrm_training_records');
  }
};

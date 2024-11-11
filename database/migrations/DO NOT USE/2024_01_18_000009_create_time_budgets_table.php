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
    Schema::create('time_budgets', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained();
      $table->decimal('total_hours', 10, 2);
      $table->date('start_date');
      $table->date('end_date');
      $table->json('task_budgets');
      $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });

    Schema::create('time_budget_tasks', function (Blueprint $table) {
      $table->foreignId('time_budget_id')->constrained()->onDelete('cascade');
      $table->foreignId('task_id')->constrained()->onDelete('cascade');
      $table->decimal('hours', 10, 2);
      $table->primary(['time_budget_id', 'task_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('time_budget_tasks');
    Schema::dropIfExists('time_budgets');
  }
};

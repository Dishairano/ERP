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
    Schema::create('development_plans', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained();
      $table->foreignId('mentor_id')->constrained('employees');
      $table->json('objectives');
      $table->date('start_date');
      $table->date('end_date');
      $table->json('activities');
      $table->json('resources');
      $table->json('milestones');
      $table->json('success_criteria');
      $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('development_plans');
  }
};

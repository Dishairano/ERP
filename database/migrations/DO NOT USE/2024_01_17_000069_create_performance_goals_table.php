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
    Schema::create('performance_goals', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained();
      $table->string('title');
      $table->text('description');
      $table->string('category');
      $table->date('start_date');
      $table->date('end_date');
      $table->json('metrics');
      $table->string('target');
      $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
      $table->enum('status', ['pending', 'approved', 'in_progress', 'completed'])->default('pending');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('performance_goals');
  }
};

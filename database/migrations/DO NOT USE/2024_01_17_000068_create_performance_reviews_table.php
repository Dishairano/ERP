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
    Schema::create('performance_reviews', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained();
      $table->foreignId('reviewer_id')->constrained('users');
      $table->string('review_period');
      $table->date('review_date');
      $table->json('ratings');
      $table->json('strengths');
      $table->json('areas_for_improvement');
      $table->json('goals');
      $table->text('comments')->nullable();
      $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('performance_reviews');
  }
};

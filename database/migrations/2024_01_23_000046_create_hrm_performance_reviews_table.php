<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('hrm_performance_reviews', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained('hrm_employees')->cascadeOnDelete();
      $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
      $table->date('review_period_start');
      $table->date('review_period_end');
      $table->string('review_type'); // annual, quarterly, probation, etc.
      $table->decimal('overall_rating', 3, 2)->nullable();
      $table->json('ratings')->nullable(); // Detailed ratings for different aspects
      $table->text('achievements')->nullable();
      $table->text('areas_for_improvement')->nullable();
      $table->text('goals_set')->nullable();
      $table->text('training_needs')->nullable();
      $table->text('employee_comments')->nullable();
      $table->text('reviewer_comments')->nullable();
      $table->string('status')->default('draft'); // draft, in_progress, completed
      $table->date('completed_date')->nullable();
      $table->date('next_review_date')->nullable();
      $table->timestamps();
      $table->softDeletes();

      // Use shorter index names
      $table->index(['review_period_start', 'review_period_end'], 'review_period_idx');
      $table->index(['employee_id', 'status'], 'employee_status_idx');
      $table->index(['reviewer_id', 'status'], 'reviewer_status_idx');
    });
  }

  public function down()
  {
    Schema::dropIfExists('hrm_performance_reviews');
  }
};

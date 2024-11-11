<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('hrm_interviews', function (Blueprint $table) {
      $table->id();
      $table->foreignId('candidate_id')->nullable()->constrained('hrm_candidates')->nullOnDelete();
      $table->foreignId('job_posting_id')->nullable()->constrained('hrm_job_postings')->nullOnDelete();
      $table->foreignId('interviewer_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->string('interview_type'); // phone, video, in-person, technical, behavioral
      $table->integer('round_number')->default(1);
      $table->date('scheduled_date')->nullable();
      $table->time('scheduled_time')->nullable();
      $table->integer('duration_minutes')->nullable();
      $table->string('location')->nullable();
      $table->string('meeting_link')->nullable();
      $table->string('meeting_id')->nullable();
      $table->string('meeting_password')->nullable();
      $table->text('preparation_notes')->nullable();
      $table->json('questions')->nullable();
      $table->json('evaluation_criteria')->nullable();
      $table->decimal('technical_skills_rating', 3, 1)->nullable();
      $table->decimal('soft_skills_rating', 3, 1)->nullable();
      $table->decimal('cultural_fit_rating', 3, 1)->nullable();
      $table->decimal('overall_rating', 3, 1)->nullable();
      $table->text('interviewer_notes')->nullable();
      $table->text('candidate_feedback')->nullable();
      $table->text('next_steps')->nullable();
      $table->string('status')->default('scheduled'); // scheduled, completed, cancelled, no_show
      $table->text('cancellation_reason')->nullable();
      $table->timestamps();
      $table->softDeletes();

      // Add indexes
      $table->index(['status', 'scheduled_date']);
      $table->index(['interviewer_id', 'scheduled_date']);
      $table->index(['candidate_id', 'status']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('hrm_interviews');
  }
};

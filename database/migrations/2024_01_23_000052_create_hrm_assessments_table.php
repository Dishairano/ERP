<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('hrm_assessments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('candidate_id')->nullable()->constrained('hrm_candidates')->nullOnDelete();
      $table->foreignId('job_posting_id')->nullable()->constrained('hrm_job_postings')->nullOnDelete();
      $table->foreignId('assessor_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->string('title');
      $table->text('description')->nullable();
      $table->string('assessment_type'); // technical, coding, personality, skills, language
      $table->date('scheduled_date')->nullable();
      $table->time('scheduled_time')->nullable();
      $table->integer('duration_minutes')->nullable();
      $table->string('platform')->nullable();
      $table->string('access_link')->nullable();
      $table->string('access_code')->nullable();
      $table->date('expiry_date')->nullable();
      $table->text('instructions')->nullable();
      $table->json('questions')->nullable();
      $table->integer('max_score')->default(100);
      $table->integer('passing_score')->default(60);
      $table->integer('score')->nullable();
      $table->json('skill_scores')->nullable();
      $table->text('feedback')->nullable();
      $table->text('recommendations')->nullable();
      $table->json('attachments')->nullable();
      $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, expired, cancelled
      $table->timestamps();
      $table->softDeletes();

      // Add indexes
      $table->index(['status', 'scheduled_date']);
      $table->index(['candidate_id', 'status']);
      $table->index(['assessor_id', 'scheduled_date']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('hrm_assessments');
  }
};

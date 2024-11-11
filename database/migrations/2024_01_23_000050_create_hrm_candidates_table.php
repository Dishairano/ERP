<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('hrm_candidates', function (Blueprint $table) {
      $table->id();
      $table->foreignId('job_posting_id')->nullable()->constrained('hrm_job_postings')->nullOnDelete();
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->string('first_name');
      $table->string('last_name');
      $table->string('email')->unique();
      $table->string('phone')->nullable();
      $table->string('city')->nullable();
      $table->string('state')->nullable();
      $table->string('country')->nullable();
      $table->text('address')->nullable();
      $table->string('current_company')->nullable();
      $table->string('current_position')->nullable();
      $table->integer('experience_years')->default(0);
      $table->string('education_level')->nullable();
      $table->string('field_of_study')->nullable();
      $table->json('skills')->nullable();
      $table->string('portfolio_url')->nullable();
      $table->string('linkedin_url')->nullable();
      $table->string('github_url')->nullable();
      $table->string('resume_path')->nullable();
      $table->string('cover_letter_path')->nullable();
      $table->string('status')->default('applied'); // applied, screening, interviewing, offered, hired, rejected, withdrawn
      $table->text('rejection_reason')->nullable();
      $table->timestamps();
      $table->softDeletes();

      // Add indexes
      $table->index(['status', 'created_at']);
      $table->index(['email']);
      $table->index(['phone']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('hrm_candidates');
  }
};

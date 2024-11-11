<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('hrm_job_postings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->string('title');
      $table->text('description');
      $table->string('department');
      $table->string('location');
      $table->string('employment_type'); // full-time, part-time, contract
      $table->string('experience_level');
      $table->decimal('salary_min', 10, 2)->nullable();
      $table->decimal('salary_max', 10, 2)->nullable();
      $table->json('required_skills')->nullable();
      $table->json('responsibilities')->nullable();
      $table->json('qualifications')->nullable();
      $table->json('benefits')->nullable();
      $table->date('posting_date')->nullable();
      $table->date('closing_date')->nullable();
      $table->string('status')->default('draft'); // draft, active, closed, cancelled
      $table->integer('positions_available')->default(1);
      $table->integer('positions_filled')->default(0);
      $table->timestamps();
      $table->softDeletes();

      // Add indexes
      $table->index(['status', 'posting_date']);
      $table->index(['department']);
      $table->index(['location']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('hrm_job_postings');
  }
};

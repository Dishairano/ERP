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
    Schema::create('performance_feedback', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained();
      $table->foreignId('reviewer_id')->constrained('users');
      $table->string('relationship');
      $table->string('period');
      $table->json('competencies');
      $table->json('strengths');
      $table->json('improvements');
      $table->text('comments')->nullable();
      $table->enum('status', ['draft', 'submitted', 'reviewed'])->default('draft');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('performance_feedback');
  }
};

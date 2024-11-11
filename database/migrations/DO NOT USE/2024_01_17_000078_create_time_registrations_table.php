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
    Schema::create('time_registrations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained();
      $table->foreignId('project_id')->constrained();
      $table->foreignId('task_id')->constrained();
      $table->date('date');
      $table->decimal('hours', 5, 2);
      $table->text('description');
      $table->boolean('billable')->default(true);
      $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
      $table->text('rejection_reason')->nullable();
      $table->foreignId('approved_by')->nullable()->constrained('users');
      $table->foreignId('rejected_by')->nullable()->constrained('users');
      $table->timestamp('approved_at')->nullable();
      $table->timestamp('rejected_at')->nullable();
      $table->integer('week_number');
      $table->integer('month');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('time_registrations');
  }
};

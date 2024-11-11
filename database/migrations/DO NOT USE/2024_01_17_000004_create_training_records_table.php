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
    Schema::create('training_records', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained()->onDelete('cascade');
      $table->foreignId('training_id')->constrained();
      $table->date('start_date');
      $table->date('completion_date')->nullable();
      $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
      $table->decimal('score', 5, 2)->nullable();
      $table->string('certificate_number')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('training_records');
  }
};

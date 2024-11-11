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
    Schema::create('project_tasks', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
      $table->dateTime('start_date');
      $table->dateTime('end_date');
      $table->integer('duration_days');
      $table->decimal('estimated_hours', 10, 2);
      $table->decimal('actual_hours', 10, 2)->nullable();
      $table->boolean('is_complete')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('project_tasks');
  }
};

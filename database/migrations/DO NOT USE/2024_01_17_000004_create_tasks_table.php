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
    Schema::create('tasks', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained();
      $table->string('name');
      $table->text('description')->nullable();
      $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold'])->default('pending');
      $table->date('start_date')->nullable();
      $table->date('due_date')->nullable();
      $table->integer('estimated_hours')->nullable();
      $table->foreignId('assigned_to')->nullable()->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tasks');
  }
};

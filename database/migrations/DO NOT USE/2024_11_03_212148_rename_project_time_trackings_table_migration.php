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
    Schema::create('project_time_trackings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained();
      $table->foreignId('task_id')->constrained('tasks');
      $table->foreignId('user_id')->constrained();
      $table->timestamp('start_time')->useCurrent();
      $table->timestamp('end_time')->nullable();
      $table->integer('duration')->comment('in minutes');
      $table->text('description')->nullable();
      $table->boolean('billable')->default(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('project_time_trackings');
  }
};

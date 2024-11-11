<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('project_performance_metrics', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->string('metric_type'); // e.g., 'schedule_performance', 'cost_performance', 'quality_metrics'
      $table->decimal('planned_value', 15, 2)->nullable();
      $table->decimal('actual_value', 15, 2)->nullable();
      $table->decimal('earned_value', 15, 2)->nullable();
      $table->decimal('variance', 15, 2)->nullable();
      $table->decimal('performance_index', 8, 4)->nullable();
      $table->json('additional_data')->nullable();
      $table->date('measurement_date');
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('project_performance_metrics');
  }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('project_dashboards', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
      $table->integer('total_tasks')->default(0);
      $table->integer('completed_tasks')->default(0);
      $table->integer('pending_tasks')->default(0);
      $table->integer('overdue_tasks')->default(0);
      $table->decimal('progress_percentage', 5, 2)->default(0);
      $table->decimal('budget_allocated', 15, 2)->default(0);
      $table->decimal('budget_spent', 15, 2)->default(0);
      $table->decimal('budget_remaining', 15, 2)->default(0);
      $table->dateTime('start_date')->nullable();
      $table->dateTime('end_date')->nullable();
      $table->string('status')->default('pending');
      $table->string('priority')->default('medium');
      $table->json('team_members')->nullable();
      $table->json('recent_activities')->nullable();
      $table->json('upcoming_milestones')->nullable();
      $table->json('risk_summary')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('project_dashboards');
  }
};

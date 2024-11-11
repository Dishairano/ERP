<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('milestones', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
      $table->string('title');
      $table->text('description')->nullable();
      $table->date('due_date');
      $table->date('completion_date')->nullable();
      $table->enum('status', ['pending', 'in_progress', 'completed', 'delayed']);
      $table->enum('priority', ['low', 'medium', 'high']);
      $table->json('deliverables')->nullable();
      $table->json('dependencies')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('milestones');
  }
};

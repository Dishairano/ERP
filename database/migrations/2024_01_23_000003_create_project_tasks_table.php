<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('project_tasks', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
      $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->string('title');
      $table->text('description')->nullable();
      $table->string('status')->default('pending');
      $table->string('priority')->default('medium');
      $table->dateTime('start_date')->nullable();
      $table->dateTime('due_date')->nullable();
      $table->dateTime('completed_at')->nullable();
      $table->integer('estimated_hours')->nullable();
      $table->integer('actual_hours')->nullable();
      $table->decimal('actual_cost', 15, 2)->nullable();
      $table->json('attachments')->nullable();
      $table->json('comments')->nullable();
      $table->timestamps();
      $table->softDeletes();

      // Add indexes
      $table->index(['status', 'due_date']);
      $table->index(['assigned_to', 'status']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('project_tasks');
  }
};

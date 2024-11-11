<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('resource_maintenance_schedules', function (Blueprint $table) {
      $table->id();
      $table->foreignId('resource_id')->constrained()->onDelete('cascade');
      $table->string('maintenance_type');
      $table->dateTime('scheduled_date');
      $table->dateTime('completed_date')->nullable();
      $table->string('status'); // scheduled, in_progress, completed, overdue
      $table->text('description');
      $table->decimal('estimated_duration_hours', 8, 2);
      $table->decimal('actual_duration_hours', 8, 2)->nullable();
      $table->decimal('cost', 10, 2)->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('resource_maintenance_schedules');
  }
};

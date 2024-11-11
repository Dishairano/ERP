<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('resource_assignments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('resource_id')->constrained()->onDelete('cascade');
      $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
      $table->dateTime('start_time');
      $table->dateTime('end_time');
      $table->string('status'); // planned, active, completed, cancelled
      $table->text('notes')->nullable();
      $table->decimal('actual_hours_used', 8, 2)->nullable();
      $table->decimal('planned_hours', 8, 2);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('resource_assignments');
  }
};

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
    Schema::create('training_developments', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description')->nullable();
      $table->date('start_date');
      $table->date('end_date');
      $table->string('trainer')->nullable();
      $table->string('location')->nullable();
      $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
      $table->decimal('budget', 10, 2)->nullable();
      $table->text('objectives')->nullable();
      $table->text('outcomes')->nullable();
      $table->unsignedBigInteger('department_id')->nullable();
      $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('training_developments');
  }
};

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
    Schema::create('resource_allocations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained();
      $table->foreignId('resource_id')->constrained('resources');
      $table->date('start_date');
      $table->date('end_date');
      $table->decimal('hours_per_day', 4, 2);
      $table->string('role');
      $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('resource_allocations');
  }
};

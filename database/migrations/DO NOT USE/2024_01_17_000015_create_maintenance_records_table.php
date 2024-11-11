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
    Schema::create('maintenance_records', function (Blueprint $table) {
      $table->id();
      $table->foreignId('work_center_id')->constrained();
      $table->string('type');
      $table->text('description');
      $table->date('scheduled_date');
      $table->date('completion_date')->nullable();
      $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
      $table->foreignId('performed_by')->nullable()->constrained('users');
      $table->decimal('cost', 10, 2)->nullable();
      $table->text('notes')->nullable();
      $table->date('next_maintenance_date')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('maintenance_records');
  }
};

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
    Schema::create('efficiency_records', function (Blueprint $table) {
      $table->id();
      $table->foreignId('work_center_id')->constrained();
      $table->date('date');
      $table->decimal('planned_output', 10, 2);
      $table->decimal('actual_output', 10, 2);
      $table->decimal('efficiency_rate', 5, 2);
      $table->integer('downtime')->comment('in minutes');
      $table->decimal('quality_rate', 5, 2);
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->unique(['work_center_id', 'date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('efficiency_records');
  }
};

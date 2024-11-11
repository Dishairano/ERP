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
    Schema::create('production_orders', function (Blueprint $table) {
      $table->id();
      $table->string('order_number')->unique();
      $table->foreignId('product_id')->constrained();
      $table->decimal('quantity', 10, 2);
      $table->foreignId('work_center_id')->constrained();
      $table->date('scheduled_date');
      $table->date('start_date')->nullable();
      $table->date('completion_date')->nullable();
      $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
      $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
      $table->decimal('actual_quantity', 10, 2)->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('production_orders');
  }
};

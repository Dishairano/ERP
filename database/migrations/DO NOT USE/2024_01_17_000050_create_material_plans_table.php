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
    Schema::create('material_plans', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->foreignId('work_order_id')->constrained('production_orders');
      $table->decimal('quantity', 10, 2);
      $table->date('due_date');
      $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
      $table->enum('status', ['draft', 'approved', 'in_progress', 'completed'])->default('draft');
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
    Schema::dropIfExists('material_plans');
  }
};

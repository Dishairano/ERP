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
    Schema::create('material_requirements', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->foreignId('work_order_id')->constrained('production_orders');
      $table->decimal('quantity', 10, 2);
      $table->date('required_date');
      $table->string('source');
      $table->enum('status', ['pending', 'approved', 'fulfilled', 'cancelled'])->default('pending');
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
    Schema::dropIfExists('material_requirements');
  }
};

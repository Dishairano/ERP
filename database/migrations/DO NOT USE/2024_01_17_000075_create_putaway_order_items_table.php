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
    Schema::create('putaway_order_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('putaway_order_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_id')->constrained();
      $table->foreignId('bin_id')->constrained('warehouse_bins');
      $table->decimal('quantity', 10, 2);
      $table->decimal('putaway_quantity', 10, 2)->default(0);
      $table->enum('status', ['pending', 'partial', 'completed'])->default('pending');
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('putaway_order_items');
  }
};

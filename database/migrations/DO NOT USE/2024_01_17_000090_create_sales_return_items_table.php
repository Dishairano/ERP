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
    Schema::create('sales_return_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('return_id')->constrained('sales_returns')->onDelete('cascade');
      $table->foreignId('order_item_id')->constrained('order_items');
      $table->decimal('quantity', 10, 2);
      $table->decimal('price', 15, 2);
      $table->decimal('subtotal', 15, 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('sales_return_items');
  }
};

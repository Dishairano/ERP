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
    Schema::create('purchase_order_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_id')->constrained();
      $table->decimal('quantity', 10, 2);
      $table->decimal('unit_price', 12, 2);
      $table->decimal('total_price', 12, 2);
      $table->decimal('tax_rate', 5, 2)->default(0);
      $table->decimal('tax_amount', 12, 2)->default(0);
      $table->decimal('discount_rate', 5, 2)->default(0);
      $table->decimal('discount_amount', 12, 2)->default(0);
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('purchase_order_items');
  }
};

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
    // First, drop the tables that have a foreign key relationship with purchase_orders
    Schema::dropIfExists('purchase_order_items');
    Schema::dropIfExists('purchase_requisition_items');
    Schema::table('goods_receipts', function (Blueprint $table) {
      $table->dropForeign(['purchase_order_id']);
      $table->dropColumn('purchase_order_id');
    });

    // Then, drop the purchase_orders table
    Schema::dropIfExists('purchase_orders');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::create('purchase_orders', function (Blueprint $table) {
      $table->id();
      // Add table columns here
    });

    Schema::create('purchase_order_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
      // Add table columns here
    });

    Schema::create('purchase_requisition_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
      // Add table columns here
    });

    Schema::table('goods_receipts', function (Blueprint $table) {
      $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders');
    });
  }
};

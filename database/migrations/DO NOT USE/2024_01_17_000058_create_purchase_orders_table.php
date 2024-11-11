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
    Schema::create('purchase_orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('supplier_id')->constrained();
      $table->date('order_date');
      $table->date('delivery_date');
      $table->enum('status', ['draft', 'pending', 'approved', 'received', 'cancelled'])->default('draft');
      $table->decimal('total_amount', 12, 2);
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
    Schema::dropIfExists('purchase_orders');
  }
};

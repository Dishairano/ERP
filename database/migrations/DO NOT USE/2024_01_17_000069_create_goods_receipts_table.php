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
    Schema::create('goods_receipts', function (Blueprint $table) {
      $table->id();
      $table->string('goods_receipt_number')->unique();
      $table->date('receipt_date');
      $table->foreignId('purchase_order_id')->nullable()->constrained();
      $table->foreignId('supplier_id')->nullable()->constrained();
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('updated_by')->nullable()->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('goods_receipts');
  }
};

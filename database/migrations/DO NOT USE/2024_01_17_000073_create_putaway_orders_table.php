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
    Schema::create('putaway_orders', function (Blueprint $table) {
      $table->id();
      $table->string('putaway_order_number')->unique();
      $table->date('putaway_date');
      $table->foreignId('goods_receipt_id')->nullable()->constrained('goods_receipts');
      $table->enum('status', ['draft', 'putaway', 'completed', 'cancelled'])->default('draft');
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
    Schema::dropIfExists('putaway_orders');
  }
};

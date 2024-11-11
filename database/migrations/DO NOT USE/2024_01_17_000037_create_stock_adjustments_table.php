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
    Schema::create('stock_adjustments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->foreignId('warehouse_id')->constrained();
      $table->enum('type', ['addition', 'subtraction']);
      $table->decimal('quantity', 10, 2);
      $table->string('reason');
      $table->text('notes')->nullable();
      $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
      $table->foreignId('requested_by')->constrained('users');
      $table->foreignId('approved_by')->nullable()->constrained('users');
      $table->timestamp('approved_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('stock_adjustments');
  }
};

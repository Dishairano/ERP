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
    Schema::create('sales_returns', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained();
      $table->string('reason');
      $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
      $table->decimal('total_amount', 15, 2);
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
    Schema::dropIfExists('sales_returns');
  }
};

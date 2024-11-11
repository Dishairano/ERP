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
    Schema::create('picking_orders', function (Blueprint $table) {
      $table->id();
      $table->string('picking_order_number')->unique();
      $table->date('picking_date');
      $table->foreignId('order_id')->nullable()->constrained();
      $table->enum('status', ['draft', 'picked', 'completed', 'cancelled'])->default('draft');
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
    Schema::dropIfExists('picking_orders');
  }
};

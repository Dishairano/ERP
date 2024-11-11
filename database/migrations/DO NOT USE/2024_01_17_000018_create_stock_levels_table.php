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
    Schema::create('stock_levels', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->foreignId('warehouse_id')->constrained();
      $table->foreignId('warehouse_zone_id')->constrained();
      $table->decimal('quantity', 10, 2);
      $table->decimal('minimum_level', 10, 2);
      $table->decimal('maximum_level', 10, 2);
      $table->decimal('reorder_point', 10, 2);
      $table->string('status');
      $table->timestamp('last_counted_at')->nullable();
      $table->foreignId('last_counted_by')->nullable()->constrained('users');
      $table->timestamps();

      $table->unique(['product_id', 'warehouse_id', 'warehouse_zone_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('stock_levels');
  }
};

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
    Schema::create('price_list_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('price_list_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_id')->constrained();
      $table->decimal('price', 12, 2);
      $table->integer('min_quantity')->nullable();
      $table->integer('max_quantity')->nullable();
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->unique(['price_list_id', 'product_id', 'min_quantity']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('price_list_items');
  }
};

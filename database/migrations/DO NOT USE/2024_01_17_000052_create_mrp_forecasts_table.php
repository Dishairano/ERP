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
    Schema::create('mrp_forecasts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->string('period');
      $table->decimal('quantity', 10, 2);
      $table->decimal('confidence', 5, 2);
      $table->string('method');
      $table->json('parameters');
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
    Schema::dropIfExists('mrp_forecasts');
  }
};

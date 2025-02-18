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
    Schema::create('currencies', function (Blueprint $table) {
      $table->id();
      $table->string('code', 3)->unique(); // ISO 4217 currency code (e.g., USD, EUR)
      $table->string('name');
      $table->string('symbol');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->boolean('is_default')->default(false);
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('currencies');
  }
};

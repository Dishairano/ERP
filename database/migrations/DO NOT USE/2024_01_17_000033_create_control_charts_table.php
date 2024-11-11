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
    Schema::create('control_charts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->string('name');
      $table->string('type');
      $table->string('parameter');
      $table->decimal('ucl', 10, 4);
      $table->decimal('lcl', 10, 4);
      $table->decimal('target', 10, 4);
      $table->string('measurement_frequency');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('control_charts');
  }
};

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
    Schema::create('financial_metrics', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('category');
      $table->decimal('value', 15, 2);
      $table->decimal('target', 15, 2)->nullable();
      $table->string('period');
      $table->date('date');
      $table->string('status');
      $table->string('trend')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->unique(['name', 'period', 'date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('financial_metrics');
  }
};

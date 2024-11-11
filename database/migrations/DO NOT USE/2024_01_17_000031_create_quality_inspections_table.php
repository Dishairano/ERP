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
    Schema::create('quality_inspections', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->foreignId('work_order_id')->constrained('production_orders');
      $table->foreignId('inspector_id')->constrained('users');
      $table->timestamp('inspection_date');
      $table->string('type');
      $table->enum('result', ['pass', 'fail', 'conditional']);
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('quality_inspections');
  }
};

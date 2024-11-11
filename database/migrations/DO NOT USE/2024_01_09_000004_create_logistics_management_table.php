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
    Schema::create('logistics_management', function (Blueprint $table) {
      $table->id();
      $table->string('shipment_number')->unique();
      $table->string('origin');
      $table->string('destination');
      $table->string('status');
      $table->date('estimated_delivery_date');
      $table->date('actual_delivery_date')->nullable();
      $table->string('tracking_number')->nullable();
      $table->string('carrier');
      $table->text('notes')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('logistics_management');
  }
};

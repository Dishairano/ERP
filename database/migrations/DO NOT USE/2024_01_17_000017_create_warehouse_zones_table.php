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
    Schema::create('warehouse_zones', function (Blueprint $table) {
      $table->id();
      $table->foreignId('warehouse_id')->constrained();
      $table->string('name');
      $table->string('code')->unique();
      $table->string('type');
      $table->decimal('capacity', 10, 2);
      $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
      $table->text('description')->nullable();
      $table->string('location')->nullable();
      $table->json('temperature_range')->nullable();
      $table->json('humidity_range')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('warehouse_zones');
  }
};

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
    Schema::create('warehouse_bins', function (Blueprint $table) {
      $table->id();
      $table->foreignId('warehouse_zone_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->string('code')->unique();
      $table->decimal('capacity', 10, 2);
      $table->enum('status', ['empty', 'partial', 'full', 'blocked'])->default('empty');
      $table->string('location');
      $table->json('dimensions')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('warehouse_bins');
  }
};

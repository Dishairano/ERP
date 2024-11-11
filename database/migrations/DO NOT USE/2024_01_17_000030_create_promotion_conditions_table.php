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
    Schema::create('promotion_conditions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
      $table->string('type');
      $table->string('operator');
      $table->decimal('value', 12, 2);
      $table->string('target_type')->nullable();
      $table->unsignedBigInteger('target_id')->nullable();
      $table->integer('priority')->default(0);
      $table->timestamps();

      $table->index(['target_type', 'target_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('promotion_conditions');
  }
};

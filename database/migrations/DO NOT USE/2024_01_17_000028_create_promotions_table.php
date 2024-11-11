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
    Schema::create('promotions', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->enum('type', ['bogo', 'bundle', 'gift']);
      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->enum('reward_type', ['product', 'discount']);
      $table->string('reward_value');
      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->timestamps();
    });

    Schema::create('product_promotion', function (Blueprint $table) {
      $table->foreignId('product_id')->constrained()->onDelete('cascade');
      $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
      $table->primary(['product_id', 'promotion_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_promotion');
    Schema::dropIfExists('promotions');
  }
};

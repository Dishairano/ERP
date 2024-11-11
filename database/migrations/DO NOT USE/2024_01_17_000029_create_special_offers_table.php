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
    Schema::create('special_offers', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->enum('discount_type', ['percentage', 'fixed']);
      $table->decimal('discount_value', 10, 2);
      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->integer('usage_limit')->nullable();
      $table->decimal('minimum_purchase', 12, 2)->nullable();
      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->timestamps();
    });

    Schema::create('product_special_offer', function (Blueprint $table) {
      $table->foreignId('product_id')->constrained()->onDelete('cascade');
      $table->foreignId('special_offer_id')->constrained()->onDelete('cascade');
      $table->primary(['product_id', 'special_offer_id']);
    });

    Schema::create('customer_special_offer', function (Blueprint $table) {
      $table->foreignId('customer_id')->constrained()->onDelete('cascade');
      $table->foreignId('special_offer_id')->constrained()->onDelete('cascade');
      $table->primary(['customer_id', 'special_offer_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customer_special_offer');
    Schema::dropIfExists('product_special_offer');
    Schema::dropIfExists('special_offers');
  }
};

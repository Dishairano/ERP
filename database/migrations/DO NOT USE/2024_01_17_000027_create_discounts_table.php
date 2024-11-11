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
    Schema::create('discounts', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->enum('type', ['percentage', 'fixed']);
      $table->decimal('value', 10, 2);
      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->integer('minimum_quantity')->nullable();
      $table->decimal('minimum_amount', 12, 2)->nullable();
      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->timestamps();
    });

    Schema::create('discount_product', function (Blueprint $table) {
      $table->foreignId('discount_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_id')->constrained()->onDelete('cascade');
      $table->primary(['discount_id', 'product_id']);
    });

    Schema::create('customer_discount', function (Blueprint $table) {
      $table->foreignId('customer_id')->constrained()->onDelete('cascade');
      $table->foreignId('discount_id')->constrained()->onDelete('cascade');
      $table->primary(['customer_id', 'discount_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customer_discount');
    Schema::dropIfExists('discount_product');
    Schema::dropIfExists('discounts');
  }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('supplier_products', function (Blueprint $table) {
      $table->id();
      $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
      $table->string('product_code');
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('category')->nullable();
      $table->decimal('unit_price', 15, 2);
      $table->string('currency', 3)->default('EUR');
      $table->integer('minimum_order_quantity')->default(1);
      $table->integer('lead_time_days')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('supplier_products');
  }
};

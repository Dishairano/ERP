<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('items', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');
      $table->text('description')->nullable();
      $table->foreignId('category_id')->constrained('item_categories');
      $table->foreignId('unit_id')->constrained('units');
      $table->decimal('unit_cost', 15, 2)->default(0);
      $table->decimal('unit_price', 15, 2)->default(0);
      $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
      $table->string('barcode')->nullable()->unique();
      $table->string('manufacturer')->nullable();
      $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
      $table->decimal('weight', 10, 2)->nullable();
      $table->string('dimensions')->nullable();
      $table->boolean('is_stockable')->default(true);
      $table->boolean('is_purchasable')->default(true);
      $table->boolean('is_sellable')->default(true);
      $table->decimal('tax_rate', 5, 2)->default(0);
      $table->text('notes')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('items');
  }
};

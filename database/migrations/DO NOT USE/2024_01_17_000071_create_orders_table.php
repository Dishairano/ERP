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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('customer_id')->nullable()->constrained('customers');
      $table->foreignId('sales_person_id')->nullable()->constrained('employees');
      $table->string('order_number')->unique();
      $table->date('order_date');
      $table->date('required_date');
      $table->date('shipped_date')->nullable();
      $table->decimal('total_amount', 12, 2);
      $table->decimal('discount_amount', 12, 2)->default(0);
      $table->decimal('tax_amount', 12, 2)->default(0);
      $table->enum('status', ['draft', 'placed', 'shipped', 'cancelled'])->default('draft');
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->nullable()->constrained('users');
      $table->foreignId('updated_by')->nullable()->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};

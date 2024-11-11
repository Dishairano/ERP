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
    Schema::create('invoices', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained();
      $table->datetime('invoice_date');
      $table->datetime('due_date');
      $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
      $table->decimal('total_amount', 15, 2);
      $table->decimal('paid_amount', 15, 2)->default(0);
      $table->datetime('payment_date')->nullable();
      $table->string('payment_method')->nullable();
      $table->string('payment_reference')->nullable();
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('invoices');
  }
};

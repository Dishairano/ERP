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
    Schema::create('finance_receivable_payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('receivable_id')->constrained('finance_receivables')->cascadeOnDelete();
      $table->foreignId('customer_id')->constrained('finance_customers')->cascadeOnDelete();
      $table->date('payment_date');
      $table->decimal('amount', 15, 2);
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->string('payment_method'); // bank_transfer, check, cash, credit_card, debit_card, electronic, other
      $table->string('reference_number')->nullable();
      $table->string('bank_account')->nullable();
      $table->text('description')->nullable();
      $table->string('status')->default('draft'); // draft, posted, approved, voided
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('payment_date');
      $table->index('payment_method');
      $table->index('status');
      $table->index('approved_at');
      $table->index(['receivable_id', 'status']);
      $table->index(['customer_id', 'status']);
      $table->index(['payment_date', 'status']);
      $table->index(['payment_method', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_receivable_payments');
  }
};

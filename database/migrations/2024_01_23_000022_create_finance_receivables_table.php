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
    Schema::create('finance_receivables', function (Blueprint $table) {
      $table->id();
      $table->string('number')->unique();
      $table->foreignId('customer_id')->constrained('finance_customers')->cascadeOnDelete();
      $table->date('date');
      $table->date('due_date');
      $table->decimal('amount', 15, 2);
      $table->decimal('paid_amount', 15, 2)->default(0);
      $table->decimal('remaining_amount', 15, 2);
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->text('description')->nullable();
      $table->string('reference')->nullable();
      $table->string('payment_terms')->nullable();
      $table->string('status')->default('draft'); // draft, posted, approved, paid, voided
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('number');
      $table->index('date');
      $table->index('due_date');
      $table->index('status');
      $table->index('approved_at');
      $table->index(['customer_id', 'status']);
      $table->index(['due_date', 'status']);
      $table->index(['date', 'due_date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_receivables');
  }
};

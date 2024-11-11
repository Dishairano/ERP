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
    Schema::create('finance_tax_transactions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tax_id')->constrained('finance_taxes')->cascadeOnDelete();
      $table->string('reference_type'); // receivable, payable, asset, other
      $table->unsignedBigInteger('reference_id');
      $table->date('date');
      $table->decimal('base_amount', 15, 2);
      $table->decimal('tax_amount', 15, 2);
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->boolean('is_inclusive')->default(false);
      $table->string('status')->default('pending'); // pending, filed, paid
      $table->string('filing_period'); // YYYY-MM or YYYY-QQ
      $table->date('filing_date')->nullable();
      $table->date('payment_date')->nullable();
      $table->text('description')->nullable();
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('date');
      $table->index('status');
      $table->index('filing_period');
      $table->index('filing_date');
      $table->index('payment_date');
      $table->index(['reference_type', 'reference_id']);
      $table->index(['tax_id', 'status']);
      $table->index(['tax_id', 'filing_period']);
      $table->index(['date', 'status']);
      $table->index(['filing_period', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_tax_transactions');
  }
};

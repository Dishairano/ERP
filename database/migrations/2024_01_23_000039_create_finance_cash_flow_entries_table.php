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
    Schema::create('finance_cash_flow_entries', function (Blueprint $table) {
      $table->id();
      $table->foreignId('cash_flow_id')->constrained('finance_cash_flows')->cascadeOnDelete();
      $table->string('type'); // operating, investing, financing
      $table->string('category'); // revenue, expense, receivable, payable, investment, loan, other
      $table->date('date');
      $table->decimal('amount', 15, 2);
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->string('reference_type')->nullable(); // bank_transaction, investment_transaction, payable, receivable, other
      $table->unsignedBigInteger('reference_id')->nullable();
      $table->text('description')->nullable();
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('type');
      $table->index('category');
      $table->index('date');
      $table->index('currency');
      $table->index('reference_type');
      $table->index('reference_id');
      $table->index(['cash_flow_id', 'type']);
      $table->index(['cash_flow_id', 'category']);
      $table->index(['cash_flow_id', 'date']);
      $table->index(['type', 'category']);
      $table->index(['reference_type', 'reference_id']);
      $table->index(['date', 'type']);
      $table->index(['date', 'category']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_cash_flow_entries');
  }
};

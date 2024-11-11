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
    Schema::create('finance_cash_flows', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type'); // operating, investing, financing
      $table->string('category'); // revenue, expense, receivable, payable, investment, loan, other
      $table->string('period_type'); // daily, weekly, monthly, quarterly, annually
      $table->date('start_date');
      $table->date('end_date');
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->decimal('opening_balance', 15, 2);
      $table->decimal('closing_balance', 15, 2);
      $table->decimal('net_cash_flow', 15, 2);
      $table->decimal('operating_cash_flow', 15, 2);
      $table->decimal('investing_cash_flow', 15, 2);
      $table->decimal('financing_cash_flow', 15, 2);
      $table->text('description')->nullable();
      $table->text('notes')->nullable();
      $table->string('status')->default('draft'); // draft, published, archived
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('type');
      $table->index('category');
      $table->index('period_type');
      $table->index('start_date');
      $table->index('end_date');
      $table->index('currency');
      $table->index('status');
      $table->index(['type', 'category']);
      $table->index(['type', 'status']);
      $table->index(['category', 'status']);
      $table->index(['period_type', 'status']);
      $table->index(['start_date', 'end_date']);
      $table->index(['start_date', 'status']);
      $table->index(['end_date', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_cash_flows');
  }
};

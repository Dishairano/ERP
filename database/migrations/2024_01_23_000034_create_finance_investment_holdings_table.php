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
    Schema::create('finance_investment_holdings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('investment_account_id')->constrained('finance_investment_accounts')->cascadeOnDelete();
      $table->string('symbol');
      $table->string('name');
      $table->string('type'); // stock, bond, mutual_fund, etf, option, other
      $table->string('category'); // equity, fixed_income, commodity, real_estate, cash, other
      $table->decimal('quantity', 15, 4)->default(0);
      $table->decimal('cost_basis', 15, 2)->default(0);
      $table->decimal('average_cost', 15, 4)->default(0);
      $table->decimal('current_price', 15, 4)->default(0);
      $table->decimal('market_value', 15, 2)->default(0);
      $table->decimal('unrealized_gain_loss', 15, 2)->default(0);
      $table->decimal('realized_gain_loss', 15, 2)->default(0);
      $table->decimal('annual_income', 15, 2)->default(0);
      $table->decimal('yield_percentage', 7, 2)->default(0);
      $table->decimal('allocation_percentage', 7, 2)->default(0);
      $table->decimal('target_allocation_percentage', 7, 2)->nullable();
      $table->date('last_trade_date')->nullable();
      $table->date('last_dividend_date')->nullable();
      $table->date('next_dividend_date')->nullable();
      $table->string('dividend_frequency')->nullable(); // monthly, quarterly, semi_annual, annual
      $table->string('risk_level'); // low, medium, high
      $table->string('sector')->nullable();
      $table->string('industry')->nullable();
      $table->string('country')->nullable();
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->date('maturity_date')->nullable();
      $table->decimal('coupon_rate', 7, 2)->nullable();
      $table->text('notes')->nullable();
      $table->string('status')->default('active');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('symbol');
      $table->index('type');
      $table->index('category');
      $table->index('risk_level');
      $table->index('sector');
      $table->index('industry');
      $table->index('country');
      $table->index('currency');
      $table->index('status');
      $table->index('last_trade_date');
      $table->index('last_dividend_date');
      $table->index('next_dividend_date');
      $table->index('maturity_date');
      $table->index(['investment_account_id', 'symbol']);
      $table->index(['investment_account_id', 'type']);
      $table->index(['investment_account_id', 'category']);
      $table->index(['investment_account_id', 'status']);
      $table->index(['type', 'status']);
      $table->index(['category', 'status']);
      $table->index(['sector', 'industry']);
      $table->index(['risk_level', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_investment_holdings');
  }
};

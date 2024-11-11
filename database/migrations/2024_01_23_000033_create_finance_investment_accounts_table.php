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
    Schema::create('finance_investment_accounts', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');
      $table->string('account_number');
      $table->string('broker_name');
      $table->string('broker_code')->nullable();
      $table->string('type'); // brokerage, retirement, mutual_fund, other
      $table->string('currency', 3)->default('USD');
      $table->decimal('opening_balance', 15, 2)->default(0);
      $table->decimal('current_balance', 15, 2)->default(0);
      $table->decimal('market_value', 15, 2)->default(0);
      $table->decimal('unrealized_gain_loss', 15, 2)->default(0);
      $table->decimal('realized_gain_loss', 15, 2)->default(0);
      $table->date('last_valuation_date')->nullable();
      $table->string('risk_level'); // low, medium, high
      $table->text('investment_strategy')->nullable();
      $table->json('target_allocation')->nullable();
      $table->json('current_allocation')->nullable();
      $table->string('rebalancing_frequency')->nullable(); // monthly, quarterly, annually, manual
      $table->date('last_rebalancing_date')->nullable();
      $table->date('next_rebalancing_date')->nullable();
      $table->string('contact_person')->nullable();
      $table->string('contact_phone')->nullable();
      $table->string('contact_email')->nullable();
      $table->text('notes')->nullable();
      $table->string('status')->default('active');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('code');
      $table->index('account_number');
      $table->index('broker_name');
      $table->index('type');
      $table->index('currency');
      $table->index('risk_level');
      $table->index('rebalancing_frequency');
      $table->index('status');
      $table->index('last_valuation_date');
      $table->index('last_rebalancing_date');
      $table->index('next_rebalancing_date');
      $table->index(['type', 'status']);
      $table->index(['broker_name', 'broker_code']);
      $table->index(['currency', 'status']);
      $table->index(['risk_level', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_investment_accounts');
  }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('finance_investment_transactions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('investment_account_id')->constrained('finance_investment_accounts')->cascadeOnDelete();
      $table->foreignId('investment_holding_id')->nullable()->constrained('finance_investment_holdings')->nullOnDelete();
      $table->date('transaction_date');
      $table->string('type'); // buy, sell, dividend, interest, fee
      $table->decimal('quantity', 15, 4)->nullable();
      $table->decimal('price', 15, 4)->nullable();
      $table->decimal('amount', 15, 2);
      $table->decimal('fees', 15, 2)->default(0);
      $table->string('status')->default('pending'); // pending, completed, cancelled
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamps();
      $table->softDeletes();

      // Use shorter index names
      $table->index(['investment_account_id', 'transaction_date'], 'inv_trans_account_date_idx');
      $table->index(['investment_holding_id', 'type'], 'inv_trans_holding_type_idx');
      $table->index(['status', 'transaction_date'], 'inv_trans_status_date_idx');
    });
  }

  public function down()
  {
    Schema::dropIfExists('finance_investment_transactions');
  }
};

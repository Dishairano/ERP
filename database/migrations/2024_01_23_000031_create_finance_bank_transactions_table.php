<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('finance_bank_transactions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('bank_account_id')->constrained('finance_bank_accounts')->cascadeOnDelete();
      $table->foreignId('reconciliation_id')->nullable()->constrained('finance_bank_reconciliations')->nullOnDelete();
      $table->date('transaction_date');
      $table->string('reference_number')->nullable();
      $table->string('description');
      $table->decimal('amount', 15, 2);
      $table->string('type'); // credit, debit
      $table->string('category')->nullable();
      $table->string('status')->default('pending'); // pending, cleared, reconciled
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamps();
      $table->softDeletes();

      // Add indexes
      $table->index(['bank_account_id', 'transaction_date']);
      $table->index(['status', 'transaction_date']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('finance_bank_transactions');
  }
};

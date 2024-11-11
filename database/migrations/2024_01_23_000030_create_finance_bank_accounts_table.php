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
    Schema::create('finance_bank_accounts', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');
      $table->string('account_number');
      $table->string('bank_name');
      $table->string('branch_name')->nullable();
      $table->string('swift_code')->nullable();
      $table->string('iban')->nullable();
      $table->string('routing_number')->nullable();
      $table->string('currency', 3)->default('USD');
      $table->string('type'); // checking, savings, money_market, time_deposit, other
      $table->decimal('interest_rate', 7, 4)->default(0);
      $table->decimal('minimum_balance', 15, 2)->default(0);
      $table->decimal('opening_balance', 15, 2)->default(0);
      $table->decimal('current_balance', 15, 2)->default(0);
      $table->decimal('available_balance', 15, 2)->default(0);
      $table->date('last_reconciliation_date')->nullable();
      $table->decimal('reconciled_balance', 15, 2)->default(0);
      $table->string('contact_person')->nullable();
      $table->string('contact_phone')->nullable();
      $table->string('contact_email')->nullable();
      $table->string('address_line1')->nullable();
      $table->string('address_line2')->nullable();
      $table->string('city')->nullable();
      $table->string('state')->nullable();
      $table->string('postal_code')->nullable();
      $table->string('country')->nullable();
      $table->text('notes')->nullable();
      $table->string('status')->default('active');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('code');
      $table->index('account_number');
      $table->index('bank_name');
      $table->index('type');
      $table->index('currency');
      $table->index('status');
      $table->index('last_reconciliation_date');
      $table->index(['type', 'status']);
      $table->index(['bank_name', 'branch_name']);
      $table->index(['currency', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_bank_accounts');
  }
};

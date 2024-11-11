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
    Schema::create('finance_journal_entries', function (Blueprint $table) {
      $table->id();
      $table->foreignId('account_id')->constrained('finance_accounts')->cascadeOnDelete();
      $table->foreignId('journal_id')->constrained('finance_journals')->cascadeOnDelete();
      $table->string('type'); // debit or credit
      $table->decimal('amount', 15, 2);
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->date('date');
      $table->text('description')->nullable();
      $table->string('reference')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->string('status')->default('draft'); // draft, posted, approved, voided
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('type');
      $table->index('date');
      $table->index('status');
      $table->index('approved_at');
      $table->index(['account_id', 'type']);
      $table->index(['journal_id', 'type']);
      $table->index(['date', 'type']);
      $table->index(['type', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_journal_entries');
  }
};

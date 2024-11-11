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
    Schema::create('finance_journals', function (Blueprint $table) {
      $table->id();
      $table->string('number')->unique();
      $table->date('date');
      $table->string('type'); // general, sales, purchases, cash, bank, etc.
      $table->text('description')->nullable();
      $table->string('reference')->nullable();
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->decimal('total_debit', 15, 2)->default(0);
      $table->decimal('total_credit', 15, 2)->default(0);
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->string('status')->default('draft'); // draft, posted, approved, voided
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('number');
      $table->index('date');
      $table->index('type');
      $table->index('status');
      $table->index('approved_at');
      $table->index(['date', 'type']);
      $table->index(['type', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_journals');
  }
};

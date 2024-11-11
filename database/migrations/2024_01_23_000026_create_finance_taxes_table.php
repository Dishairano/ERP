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
    Schema::create('finance_taxes', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');
      $table->string('type'); // sales_tax, vat, income_tax, withholding_tax, other
      $table->decimal('rate', 7, 4);
      $table->date('effective_from');
      $table->date('effective_to')->nullable();
      $table->foreignId('account_id')->constrained('finance_accounts');
      $table->boolean('is_recoverable')->default(false);
      $table->boolean('is_compound')->default(false);
      $table->string('applies_to'); // sales, purchases, both
      $table->string('country')->nullable();
      $table->string('region')->nullable();
      $table->text('description')->nullable();
      $table->string('status')->default('active');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('code');
      $table->index('type');
      $table->index('status');
      $table->index('effective_from');
      $table->index('effective_to');
      $table->index('applies_to');
      $table->index(['country', 'region']);
      $table->index(['type', 'status']);
      $table->index(['effective_from', 'effective_to']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_taxes');
  }
};

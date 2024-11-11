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
    Schema::create('finance_budget_line_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('budget_id')->constrained('finance_budgets')->cascadeOnDelete();
      $table->string('name');
      $table->text('description')->nullable();
      $table->decimal('amount', 15, 2)->default(0);
      $table->decimal('allocated_amount', 15, 2)->default(0);
      $table->decimal('remaining_amount', 15, 2)->default(0);
      $table->string('category');
      $table->date('start_date');
      $table->date('end_date');
      $table->string('status')->default('active');
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('category');
      $table->index('status');
      $table->index(['budget_id', 'category']);
      $table->index(['start_date', 'end_date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_budget_line_items');
  }
};

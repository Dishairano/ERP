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
    Schema::create('finance_budget_scenarios', function (Blueprint $table) {
      $table->id();
      $table->foreignId('budget_id')->constrained('finance_budgets')->cascadeOnDelete();
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('type')->default('custom'); // optimistic, pessimistic, most_likely, custom
      $table->decimal('adjustment_percentage', 5, 2)->nullable();
      $table->decimal('total_amount', 15, 2)->default(0);
      $table->string('status')->default('draft');
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('type');
      $table->index('status');
      $table->index(['budget_id', 'type']);
      $table->index('approved_at');

      // Unique constraint to prevent duplicate scenarios of the same type for a budget
      $table->unique(['budget_id', 'type', 'name']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_budget_scenarios');
  }
};

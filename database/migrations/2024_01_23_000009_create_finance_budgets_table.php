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
    Schema::create('finance_budgets', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->integer('fiscal_year');
      $table->date('start_date');
      $table->date('end_date');
      $table->decimal('total_amount', 15, 2)->default(0);
      $table->decimal('allocated_amount', 15, 2)->default(0);
      $table->decimal('remaining_amount', 15, 2)->default(0);
      $table->string('status')->default('draft');
      $table->foreignId('department_id')->nullable()->constrained('finance_departments')->nullOnDelete();
      $table->foreignId('project_id')->nullable()->constrained('project_dashboards')->nullOnDelete();
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('fiscal_year');
      $table->index('status');
      $table->index(['department_id', 'fiscal_year']);
      $table->index(['project_id', 'fiscal_year']);
      $table->index(['start_date', 'end_date']);
      $table->index('approved_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_budgets');
  }
};

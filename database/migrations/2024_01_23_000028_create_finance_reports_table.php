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
    Schema::create('finance_reports', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type'); // balance_sheet, income_statement, cash_flow, custom
      $table->json('template')->nullable();
      $table->json('parameters')->nullable();
      $table->json('filters')->nullable();
      $table->json('grouping')->nullable();
      $table->json('sorting')->nullable();
      $table->string('date_range_type'); // monthly, quarterly, yearly, custom
      $table->date('start_date');
      $table->date('end_date');
      $table->string('comparison_type')->default('none'); // previous_period, previous_year, budget, none
      $table->string('comparison_date_range_type')->nullable();
      $table->date('comparison_start_date')->nullable();
      $table->date('comparison_end_date')->nullable();
      $table->boolean('show_percentages')->default(false);
      $table->boolean('show_variances')->default(false);
      $table->boolean('include_zero_balances')->default(false);
      $table->text('notes')->nullable();
      $table->boolean('is_template')->default(false);
      $table->string('status')->default('active');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('type');
      $table->index('date_range_type');
      $table->index('start_date');
      $table->index('end_date');
      $table->index('comparison_type');
      $table->index('status');
      $table->index('is_template');
      $table->index(['type', 'status']);
      $table->index(['type', 'is_template']);
      $table->index(['start_date', 'end_date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_reports');
  }
};

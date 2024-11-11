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
    Schema::create('finance_cash_forecasts', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type'); // operating, investing, financing
      $table->string('category'); // revenue, expense, receivable, payable, investment, loan, other
      $table->date('forecast_date');
      $table->decimal('amount', 15, 2);
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->integer('probability')->default(100); // percentage
      $table->boolean('is_recurring')->default(false);
      $table->string('recurrence_pattern')->nullable(); // daily, weekly, monthly, quarterly, annually
      $table->date('recurrence_end_date')->nullable();
      $table->string('reference_type')->nullable(); // customer, vendor, project, investment, loan, other
      $table->unsignedBigInteger('reference_id')->nullable();
      $table->text('description')->nullable();
      $table->text('notes')->nullable();
      $table->string('status')->default('draft'); // draft, confirmed, cancelled
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('type');
      $table->index('category');
      $table->index('forecast_date');
      $table->index('currency');
      $table->index('probability');
      $table->index('is_recurring');
      $table->index('recurrence_pattern');
      $table->index('recurrence_end_date');
      $table->index('reference_type');
      $table->index('reference_id');
      $table->index('status');
      $table->index(['type', 'category']);
      $table->index(['type', 'status']);
      $table->index(['category', 'status']);
      $table->index(['forecast_date', 'status']);
      $table->index(['reference_type', 'reference_id']);
      $table->index(['is_recurring', 'recurrence_pattern']);
      $table->index(['is_recurring', 'recurrence_end_date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_cash_forecasts');
  }
};

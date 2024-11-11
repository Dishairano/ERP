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
    Schema::create('finance_cash_forecast_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('forecast_id')->constrained('finance_cash_forecasts')->cascadeOnDelete();
      $table->date('date');
      $table->decimal('amount', 15, 2);
      $table->string('currency', 3)->default('USD');
      $table->decimal('exchange_rate', 10, 4)->default(1.0000);
      $table->integer('probability')->default(100); // percentage
      $table->text('description')->nullable();
      $table->text('notes')->nullable();
      $table->string('status')->default('pending'); // pending, realized, cancelled
      $table->date('realization_date')->nullable();
      $table->decimal('realized_amount', 15, 2)->nullable();
      $table->decimal('variance_amount', 15, 2)->nullable();
      $table->decimal('variance_percentage', 7, 2)->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('date');
      $table->index('currency');
      $table->index('probability');
      $table->index('status');
      $table->index('realization_date');
      $table->index(['forecast_id', 'date']);
      $table->index(['forecast_id', 'status']);
      $table->index(['date', 'status']);
      $table->index(['realization_date', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_cash_forecast_items');
  }
};

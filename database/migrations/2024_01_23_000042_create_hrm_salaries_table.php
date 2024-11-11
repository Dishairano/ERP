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
    Schema::create('hrm_salaries', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained('hrm_employees')->cascadeOnDelete();
      $table->decimal('basic_salary', 15, 2);
      $table->string('currency', 3)->default('USD');
      $table->string('payment_type'); // monthly, hourly, daily, weekly
      $table->date('effective_date');
      $table->date('end_date')->nullable();
      $table->decimal('housing_allowance', 15, 2)->nullable();
      $table->decimal('transportation_allowance', 15, 2)->nullable();
      $table->decimal('meal_allowance', 15, 2)->nullable();
      $table->decimal('phone_allowance', 15, 2)->nullable();
      $table->decimal('other_allowances', 15, 2)->nullable();
      $table->decimal('bonus_rate', 5, 2)->nullable(); // percentage
      $table->decimal('overtime_rate', 5, 2)->nullable(); // percentage
      $table->decimal('weekend_rate', 5, 2)->nullable(); // percentage
      $table->decimal('holiday_rate', 5, 2)->nullable(); // percentage
      $table->decimal('night_shift_rate', 5, 2)->nullable(); // percentage
      $table->decimal('tax_rate', 5, 2)->nullable(); // percentage
      $table->decimal('social_security_rate', 5, 2)->nullable(); // percentage
      $table->decimal('health_insurance_deduction', 15, 2)->nullable();
      $table->decimal('pension_deduction', 15, 2)->nullable();
      $table->decimal('loan_deduction', 15, 2)->nullable();
      $table->decimal('other_deductions', 15, 2)->nullable();
      $table->string('bank_name')->nullable();
      $table->string('bank_account')->nullable();
      $table->string('bank_branch')->nullable();
      $table->string('payment_method')->nullable(); // bank transfer, cash, check
      $table->text('notes')->nullable();
      $table->string('status')->default('active'); // active, inactive
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('employee_id');
      $table->index('currency');
      $table->index('payment_type');
      $table->index('effective_date');
      $table->index('end_date');
      $table->index('payment_method');
      $table->index('status');
      $table->index(['employee_id', 'effective_date']);
      $table->index(['employee_id', 'status']);
      $table->index(['payment_type', 'status']);
      $table->index(['effective_date', 'end_date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hrm_salaries');
  }
};

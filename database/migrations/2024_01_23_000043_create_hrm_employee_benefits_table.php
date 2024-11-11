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
    Schema::create('hrm_employee_benefits', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained('hrm_employees')->cascadeOnDelete();
      $table->string('benefit_type'); // health_insurance, life_insurance, dental, vision, retirement, stock_options, etc.
      $table->string('provider');
      $table->string('policy_number')->nullable();
      $table->decimal('coverage_amount', 15, 2)->nullable();
      $table->decimal('premium_amount', 15, 2);
      $table->decimal('employer_contribution', 15, 2);
      $table->decimal('employee_contribution', 15, 2);
      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->date('renewal_date')->nullable();
      $table->boolean('dependents_covered')->default(false);
      $table->text('coverage_details')->nullable();
      $table->string('deduction_frequency'); // monthly, bi-weekly, weekly
      $table->string('payment_method')->nullable();
      $table->string('beneficiary_name')->nullable();
      $table->string('beneficiary_relation')->nullable();
      $table->string('beneficiary_contact')->nullable();
      $table->json('documents')->nullable();
      $table->text('notes')->nullable();
      $table->string('status')->default('active'); // active, inactive, pending
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('employee_id');
      $table->index('benefit_type');
      $table->index('provider');
      $table->index('policy_number');
      $table->index('start_date');
      $table->index('end_date');
      $table->index('renewal_date');
      $table->index('deduction_frequency');
      $table->index('status');
      $table->index(['employee_id', 'benefit_type']);
      $table->index(['employee_id', 'status']);
      $table->index(['benefit_type', 'status']);
      $table->index(['start_date', 'end_date']);
      $table->index(['provider', 'policy_number']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hrm_employee_benefits');
  }
};

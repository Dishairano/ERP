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
    if (!Schema::hasTable('payrolls')) {
      Schema::create('payrolls', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users');
        $table->string('payroll_number');
        $table->decimal('salary', 15, 2);
        $table->decimal('gross_amount', 15, 2);
        $table->decimal('net_amount', 15, 2);
        $table->decimal('tax_amount', 15, 2);
        $table->decimal('insurance_amount', 15, 2);
        $table->decimal('pension_amount', 15, 2);
        $table->decimal('overtime_hours', 8, 2)->default(0);
        $table->decimal('overtime_rate', 8, 2)->default(0);
        $table->decimal('bonus', 15, 2)->nullable();
        $table->decimal('deductions', 15, 2)->nullable();
        $table->json('deduction_details')->nullable();
        $table->json('allowances')->nullable();
        $table->date('pay_period_start');
        $table->date('pay_period_end');
        $table->date('payment_date');
        $table->string('payment_method');
        $table->string('bank_account_number')->nullable();
        $table->string('bank_name')->nullable();
        $table->string('bank_branch')->nullable();
        $table->string('tax_identifier')->nullable();
        $table->json('tax_details')->nullable();
        $table->string('status');
        $table->boolean('is_approved')->default(false);
        $table->foreignId('approved_by')->nullable()->constrained('users');
        $table->timestamp('approved_at')->nullable();
        $table->string('currency_code')->default('EUR');
        $table->decimal('exchange_rate', 10, 6)->default(1);
        $table->text('notes')->nullable();
        $table->json('additional_details')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('payrolls');
  }
};

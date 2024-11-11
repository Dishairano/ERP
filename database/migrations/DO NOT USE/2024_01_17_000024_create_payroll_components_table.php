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
    Schema::create('payroll_components', function (Blueprint $table) {
      $table->id();
      $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->enum('type', ['allowance', 'deduction', 'reimbursement', 'bonus']);
      $table->decimal('amount', 12, 2);
      $table->string('calculation_method')->nullable();
      $table->string('calculation_basis')->nullable();
      $table->boolean('is_taxable')->default(true);
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('payroll_components');
  }
};

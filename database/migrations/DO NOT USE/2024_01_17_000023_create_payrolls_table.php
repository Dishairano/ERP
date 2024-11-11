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
    Schema::create('payrolls', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained();
      $table->date('period');
      $table->decimal('base_salary', 12, 2);
      $table->decimal('total_allowances', 12, 2)->default(0);
      $table->decimal('total_deductions', 12, 2)->default(0);
      $table->decimal('net_salary', 12, 2);
      $table->enum('status', ['draft', 'processed', 'paid', 'cancelled'])->default('draft');
      $table->timestamp('processed_at')->nullable();
      $table->foreignId('processed_by')->nullable()->constrained('users');
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->unique(['employee_id', 'period']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('payrolls');
  }
};

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
    Schema::create('tax_records', function (Blueprint $table) {
      $table->id();
      $table->foreignId('type_id')->constrained('tax_types');
      $table->foreignId('period_id')->constrained('tax_periods');
      $table->decimal('amount', 12, 2);
      $table->date('due_date');
      $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tax_records');
  }
};

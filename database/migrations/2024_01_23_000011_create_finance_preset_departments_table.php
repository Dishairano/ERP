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
    Schema::create('finance_preset_departments', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->foreignId('department_id')->constrained('finance_departments')->cascadeOnDelete();
      $table->integer('fiscal_year');
      $table->decimal('total_amount', 15, 2)->default(0);
      $table->text('description')->nullable();
      $table->string('status')->default('draft');
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('fiscal_year');
      $table->index('status');
      $table->index(['department_id', 'fiscal_year']);
      $table->index('approved_at');

      // Unique constraint to prevent duplicate presets for the same department and fiscal year
      $table->unique(['department_id', 'fiscal_year', 'name']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_preset_departments');
  }
};

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
    Schema::create('finance_departments', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code')->unique();
      $table->text('description')->nullable();
      $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('parent_id')->nullable()->constrained('finance_departments')->nullOnDelete();
      $table->decimal('budget_limit', 15, 2)->default(0);
      $table->string('status')->default('active');
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('code');
      $table->index('status');
      $table->index(['parent_id', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_departments');
  }
};

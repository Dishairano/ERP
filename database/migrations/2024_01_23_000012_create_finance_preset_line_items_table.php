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
    Schema::create('finance_preset_line_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('preset_id')->constrained('finance_preset_departments')->cascadeOnDelete();
      $table->string('name');
      $table->text('description')->nullable();
      $table->decimal('amount', 15, 2)->default(0);
      $table->string('category');
      $table->date('start_date');
      $table->date('end_date');
      $table->string('status')->default('active');
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('category');
      $table->index('status');
      $table->index(['preset_id', 'category']);
      $table->index(['start_date', 'end_date']);

      // Unique constraint to prevent duplicate line items within a preset
      $table->unique(['preset_id', 'name', 'category']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_preset_line_items');
  }
};
